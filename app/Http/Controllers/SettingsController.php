<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Contract;
use App\Models\EmailRecipient;
use App\Models\EmailLog;
use App\Mail\ContractExpiryReminder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index(): View
    {
        $reminderMonths = Setting::getReminderMonths();
        $budgetWarningPercent = Setting::getBudgetWarningPercent();
        $emailSubject = Setting::getEmailSubject();
        $emailGreeting = Setting::getEmailGreeting();
        $emailFooter = Setting::getEmailFooter();

        return view('settings.index', compact(
            'reminderMonths',
            'budgetWarningPercent',
            'emailSubject',
            'emailGreeting',
            'emailFooter'
        ));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reminder_months_before' => 'required|integer|min:1|max:24',
            'budget_warning_percentage' => 'required|integer|min:1|max:100',
            'email_subject' => 'nullable|string|max:255',
            'email_greeting' => 'nullable|string|max:2000',
            'email_footer' => 'nullable|string|max:2000',
        ]);

        Setting::set('reminder_months_before', $validated['reminder_months_before']);
        Setting::set('budget_warning_percentage', $validated['budget_warning_percentage']);

        if (isset($validated['email_subject'])) {
            Setting::set('email_subject', $validated['email_subject']);
        }
        if (isset($validated['email_greeting'])) {
            Setting::set('email_greeting', $validated['email_greeting']);
        }
        if (isset($validated['email_footer'])) {
            Setting::set('email_footer', $validated['email_footer']);
        }

        return redirect()->route('settings.index')
            ->with('success', __('messages.settings_updated'));
    }

    /**
     * Send manual reminder emails.
     */
    public function sendReminder(): RedirectResponse
    {
        $reminderMonths = Setting::getReminderMonths();
        $budgetWarningPercent = Setting::getBudgetWarningPercent();

        // Get all active recipients with their assignments
        $allRecipients = EmailRecipient::active()->with('assignments')->get();

        if ($allRecipients->isEmpty()) {
            return redirect()->route('settings.index')
                ->with('error', __('messages.no_recipients'));
        }

        // Get contracts expiring soon (time-based on end_date)
        $expiringContracts = Contract::where('end_date', '<=', Carbon::now()->addMonths($reminderMonths))
            ->where('end_date', '>=', Carbon::now())
            ->get();

        // Get contracts with low budget (value-based)
        $lowBudgetContracts = Contract::all()->filter(function ($contract) use ($budgetWarningPercent) {
            return $contract->remaining_percent <= $budgetWarningPercent && $contract->remaining_percent > 0;
        });

        // Get contracts with duration expiring soon (based on effective_end_date considering amendments)
        $allContractsForDuration = Contract::with('amendments')->get();
        $durationExpiringContracts = $allContractsForDuration->filter(function ($contract) use ($reminderMonths) {
            $effectiveEndDate = Carbon::parse($contract->effective_end_date);
            return $effectiveEndDate->greaterThanOrEqualTo(Carbon::now())
                && $effectiveEndDate->lessThanOrEqualTo(Carbon::now()->addMonths($reminderMonths));
        });

        // Exclude contracts already in expiringContracts (avoid duplicates where end_date == effective_end_date)
        $durationExpiringContracts = $durationExpiringContracts->filter(function ($contract) use ($expiringContracts) {
            return !$expiringContracts->contains('id', $contract->id);
        });

        if ($expiringContracts->isEmpty() && $lowBudgetContracts->isEmpty() && $durationExpiringContracts->isEmpty()) {
            return redirect()->route('settings.index')
                ->with('info', __('messages.no_contracts_to_remind'));
        }

        $allContracts = $expiringContracts->merge($lowBudgetContracts)->merge($durationExpiringContracts)->unique('id');
        $emailsSent = 0;
        $subject = Setting::getEmailSubject();

        // Group contracts by field/fungsi
        $contractGroups = [];
        foreach ($allContracts as $contract) {
            $key = ($contract->field ?? 'none') . '_' . ($contract->fungsi ?? 'none');
            if (!isset($contractGroups[$key])) {
                $contractGroups[$key] = [
                    'field' => $contract->field,
                    'fungsi' => $contract->fungsi,
                    'expiring' => collect(),
                    'low_budget' => collect(),
                    'duration_expiring' => collect(),
                ];
            }
            if ($expiringContracts->contains('id', $contract->id)) {
                $contractGroups[$key]['expiring']->push($contract);
            }
            if ($lowBudgetContracts->contains('id', $contract->id)) {
                $contractGroups[$key]['low_budget']->push($contract);
            }
            if ($durationExpiringContracts->contains('id', $contract->id)) {
                $contractGroups[$key]['duration_expiring']->push($contract);
            }
        }

        // Send to each recipient based on their assignments
        foreach ($allRecipients as $recipient) {
            $recipientExpiring = collect();
            $recipientLowBudget = collect();
            $recipientDurationExpiring = collect();

            if ($recipient->is_global) {
                // Global recipients get all contracts
                $recipientExpiring = $expiringContracts;
                $recipientLowBudget = $lowBudgetContracts;
                $recipientDurationExpiring = $durationExpiringContracts;
            } else {
                // Check each assignment
                foreach ($recipient->assignments as $assignment) {
                    foreach ($contractGroups as $group) {
                        $fieldMatch = is_null($assignment->field) || $assignment->field === $group['field'];
                        $fungsiMatch = is_null($assignment->fungsi) || $assignment->fungsi === $group['fungsi'];

                        if ($fieldMatch && $fungsiMatch) {
                            $recipientExpiring = $recipientExpiring->merge($group['expiring']);
                            $recipientLowBudget = $recipientLowBudget->merge($group['low_budget']);
                            $recipientDurationExpiring = $recipientDurationExpiring->merge($group['duration_expiring']);
                        }
                    }
                }
                $recipientExpiring = $recipientExpiring->unique('id');
                $recipientLowBudget = $recipientLowBudget->unique('id');
                $recipientDurationExpiring = $recipientDurationExpiring->unique('id');
            }

            if ($recipientExpiring->isEmpty() && $recipientLowBudget->isEmpty() && $recipientDurationExpiring->isEmpty()) {
                continue;
            }

            try {
                Mail::to($recipient->email)->send(new ContractExpiryReminder(
                    $recipientExpiring,
                    $recipientLowBudget,
                    $reminderMonths,
                    $budgetWarningPercent,
                    $recipientDurationExpiring
                ));

                // Log successful email
                EmailLog::logSent(
                    $recipient,
                    $subject,
                    $recipientExpiring->count(),
                    $recipientLowBudget->count(),
                    $recipientDurationExpiring->count(),
                    $recipientExpiring->pluck('id')->toArray(),
                    $recipientLowBudget->pluck('id')->toArray(),
                    $recipientDurationExpiring->pluck('id')->toArray()
                );
                $emailsSent++;
            } catch (\Exception $e) {
                // Log failed email
                EmailLog::logFailed($recipient, $subject, $e->getMessage());
            }
        }

        if ($emailsSent === 0) {
            return redirect()->route('settings.index')
                ->with('info', __('messages.no_matching_recipients'));
        }

        return redirect()->route('settings.index')
            ->with('success', __('messages.reminder_sent', ['count' => $emailsSent]));
    }
}
