<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'email_recipient_id',
        'contract_id',
        'recipient_email',
        'recipient_name',
        'field',
        'fungsi',
        'subject',
        'status',
        'error_message',
        'expiring_contracts_count',
        'low_budget_contracts_count',
        'duration_expiring_contracts_count',
        'expiring_contract_ids',
        'low_budget_contract_ids',
        'duration_expiring_contract_ids',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'expiring_contracts_count' => 'integer',
        'low_budget_contracts_count' => 'integer',
        'duration_expiring_contracts_count' => 'integer',
        'expiring_contract_ids' => 'array',
        'low_budget_contract_ids' => 'array',
        'duration_expiring_contract_ids' => 'array',
    ];

    /**
     * Get the email recipient
     */
    public function emailRecipient(): BelongsTo
    {
        return $this->belongsTo(EmailRecipient::class);
    }

    /**
     * Get the contract (if single contract notification)
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Scope for sent emails
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed emails
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get field label
     */
    public function getFieldLabelAttribute(): string
    {
        if (!$this->field)
            return 'All';
        return Contract::getFieldOptions()[$this->field] ?? $this->field;
    }

    /**
     * Get fungsi label
     */
    public function getFungsiLabelAttribute(): string
    {
        if (!$this->fungsi)
            return 'All';
        return Contract::getFungsiOptions()[$this->fungsi] ?? $this->fungsi;
    }

    /**
     * Log a sent email
     */
    public static function logSent(
        EmailRecipient $recipient,
        string $subject,
        int $expiringCount,
        int $lowBudgetCount,
        int $durationExpiringCount = 0,
        ?array $expiringContractIds = null,
        ?array $lowBudgetContractIds = null,
        ?array $durationExpiringContractIds = null,
        ?string $field = null,
        ?string $fungsi = null
    ): self {
        return self::create([
            'email_recipient_id' => $recipient->id,
            'recipient_email' => $recipient->email,
            'recipient_name' => $recipient->name,
            'field' => $field,
            'fungsi' => $fungsi,
            'subject' => $subject,
            'status' => 'sent',
            'expiring_contracts_count' => $expiringCount,
            'low_budget_contracts_count' => $lowBudgetCount,
            'duration_expiring_contracts_count' => $durationExpiringCount,
            'expiring_contract_ids' => $expiringContractIds,
            'low_budget_contract_ids' => $lowBudgetContractIds,
            'duration_expiring_contract_ids' => $durationExpiringContractIds,
            'sent_at' => now(),
        ]);
    }

    /**
     * Log a failed email
     */
    public static function logFailed(
        EmailRecipient $recipient,
        string $subject,
        string $errorMessage,
        ?string $field = null,
        ?string $fungsi = null
    ): self {
        return self::create([
            'email_recipient_id' => $recipient->id,
            'recipient_email' => $recipient->email,
            'recipient_name' => $recipient->name,
            'field' => $field,
            'fungsi' => $fungsi,
            'subject' => $subject,
            'status' => 'failed',
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);
    }
}
