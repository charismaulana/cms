<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use App\Models\Setting;

class ContractExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $expiringContracts;
    public Collection $lowBudgetContracts;
    public Collection $durationExpiringContracts;
    public int $reminderMonths;
    public int $budgetWarningPercent;
    public string $greeting;
    public string $footerMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $expiringContracts,
        $lowBudgetContracts,
        int $reminderMonths,
        int $budgetWarningPercent,
        $durationExpiringContracts = null
    ) {
        $this->expiringContracts = $expiringContracts instanceof Collection ? $expiringContracts : collect($expiringContracts);
        $this->lowBudgetContracts = $lowBudgetContracts instanceof Collection ? $lowBudgetContracts : collect($lowBudgetContracts);
        $this->durationExpiringContracts = $durationExpiringContracts instanceof Collection ? $durationExpiringContracts : collect($durationExpiringContracts ?? []);
        $this->reminderMonths = $reminderMonths;
        $this->budgetWarningPercent = $budgetWarningPercent;
        $this->greeting = Setting::getEmailGreeting();
        $this->footerMessage = Setting::getEmailFooter();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: Setting::getEmailSubject(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contract-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

