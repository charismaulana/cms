<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\EmailRecipient;
use App\Models\EmailRecipientAssignment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailRecipientController extends Controller
{
    /**
     * Get field options from Contract model
     */
    private function getFieldOptions(): array
    {
        return Contract::getFieldOptions();
    }

    /**
     * Get fungsi options from Contract model
     */
    private function getFungsiOptions(): array
    {
        return Contract::getFungsiOptions();
    }

    /**
     * Display a listing of email recipients.
     */
    public function index(): View
    {
        $recipients = EmailRecipient::with('assignments')->orderBy('name')->get();

        return view('email-recipients.index', compact('recipients'));
    }

    /**
     * Show the form for creating a new recipient.
     */
    public function create(): View
    {
        $fieldOptions = $this->getFieldOptions();
        $fungsiOptions = $this->getFungsiOptions();

        return view('email-recipients.create', compact('fieldOptions', 'fungsiOptions'));
    }

    /**
     * Store a newly created recipient.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:email_recipients',
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'assignments' => 'array',
            'assignments.*.field' => 'nullable|string',
            'assignments.*.fungsi' => 'nullable|string|in:general_services,warehouse',
        ]);

        $recipient = EmailRecipient::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->has('is_active'),
            'is_global' => $request->has('is_global'),
        ]);

        // Create assignments if not global
        if (!$request->has('is_global') && isset($validated['assignments'])) {
            foreach ($validated['assignments'] as $assignment) {
                if (!empty($assignment['field']) || !empty($assignment['fungsi'])) {
                    $recipient->assignments()->create([
                        'field' => $assignment['field'] ?: null,
                        'fungsi' => $assignment['fungsi'] ?: null,
                    ]);
                }
            }
        }

        return redirect()->route('email-recipients.index')
            ->with('success', __('messages.recipient_added'));
    }

    /**
     * Show the form for editing the recipient.
     */
    public function edit(EmailRecipient $emailRecipient): View
    {
        $emailRecipient->load('assignments');
        $fieldOptions = $this->getFieldOptions();
        $fungsiOptions = $this->getFungsiOptions();

        return view('email-recipients.edit', compact('emailRecipient', 'fieldOptions', 'fungsiOptions'));
    }

    /**
     * Update the specified recipient.
     */
    public function update(Request $request, EmailRecipient $emailRecipient): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:email_recipients,email,' . $emailRecipient->id,
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'assignments' => 'array',
            'assignments.*.field' => 'nullable|string',
            'assignments.*.fungsi' => 'nullable|string|in:general_services,warehouse',
        ]);

        $emailRecipient->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->has('is_active'),
            'is_global' => $request->has('is_global'),
        ]);

        // Clear existing assignments and recreate
        $emailRecipient->assignments()->delete();

        // Create assignments if not global
        if (!$request->has('is_global') && isset($validated['assignments'])) {
            foreach ($validated['assignments'] as $assignment) {
                if (!empty($assignment['field']) || !empty($assignment['fungsi'])) {
                    $emailRecipient->assignments()->create([
                        'field' => $assignment['field'] ?: null,
                        'fungsi' => $assignment['fungsi'] ?: null,
                    ]);
                }
            }
        }

        return redirect()->route('email-recipients.index')
            ->with('success', __('messages.recipient_updated'));
    }

    /**
     * Remove the specified recipient.
     */
    public function destroy(EmailRecipient $emailRecipient): RedirectResponse
    {
        $emailRecipient->delete();

        return redirect()->route('email-recipients.index')
            ->with('success', __('messages.recipient_deleted'));
    }
}
