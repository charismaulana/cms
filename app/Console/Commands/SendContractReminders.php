<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\EmailRecipient;
use App\Models\Setting;
use App\Mail\ContractExpiryReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendContractReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'contracts:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send reminder emails for expiring contracts and low budget';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $reminderMonths = Setting::getReminderMonths();
        $budgetWarningPercent = Setting::getBudgetWarningPercent();
        $recipients = EmailRecipient::getActiveEmails();

        if (empty($recipients)) {
            $this->info('No active email recipients found.');
            return Command::SUCCESS;
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
        $allContracts = Contract::with('amendments')->get();
        $durationExpiringContracts = $allContracts->filter(function ($contract) use ($reminderMonths) {
            $effectiveEndDate = Carbon::parse($contract->effective_end_date);
            return $effectiveEndDate->greaterThanOrEqualTo(Carbon::now())
                && $effectiveEndDate->lessThanOrEqualTo(Carbon::now()->addMonths($reminderMonths));
        });

        // Exclude contracts already in expiringContracts
        $durationExpiringContracts = $durationExpiringContracts->filter(function ($contract) use ($expiringContracts) {
            return !$expiringContracts->contains('id', $contract->id);
        });

        if ($expiringContracts->isEmpty() && $lowBudgetContracts->isEmpty() && $durationExpiringContracts->isEmpty()) {
            $this->info('No contracts to remind.');
            return Command::SUCCESS;
        }

        // Send email to all recipients
        foreach ($recipients as $email) {
            Mail::to($email)->send(new ContractExpiryReminder(
                $expiringContracts,
                $lowBudgetContracts,
                $reminderMonths,
                $budgetWarningPercent,
                $durationExpiringContracts
            ));
        }

        $totalCount = $expiringContracts->count() + $lowBudgetContracts->count() + $durationExpiringContracts->count();
        $recipientCount = count($recipients);

        $this->info("Sent {$totalCount} contract reminders to {$recipientCount} recipients.");

        return Command::SUCCESS;
    }
}
