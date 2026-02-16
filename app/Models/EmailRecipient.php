<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailRecipient extends Model
{
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'is_global',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_global' => 'boolean',
    ];

    /**
     * Get assignments for this recipient
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(EmailRecipientAssignment::class);
    }

    /**
     * Get email logs for this recipient
     */
    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    /**
     * Scope to get only active recipients
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get global recipients
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Get all active email addresses (for backward compatibility)
     */
    public static function getActiveEmails(): array
    {
        return self::active()->pluck('email')->toArray();
    }

    /**
     * Get recipients for a specific contract based on field and fungsi
     */
    public static function getRecipientsForContract(Contract $contract): array
    {
        $recipients = collect();

        // Get global recipients (receive all)
        $globalRecipients = self::active()->global()->get();
        $recipients = $recipients->merge($globalRecipients);

        // Get recipients matching field and/or fungsi
        $matchingRecipients = self::active()
            ->where('is_global', false)
            ->whereHas('assignments', function ($query) use ($contract) {
                $query->where(function ($q) use ($contract) {
                    // Match field only
                    $q->where(function ($sub) use ($contract) {
                        $sub->where('field', $contract->field)
                            ->whereNull('fungsi');
                    })
                        // Match fungsi only
                        ->orWhere(function ($sub) use ($contract) {
                        $sub->whereNull('field')
                            ->where('fungsi', $contract->fungsi);
                    })
                        // Match both field and fungsi
                        ->orWhere(function ($sub) use ($contract) {
                        $sub->where('field', $contract->field)
                            ->where('fungsi', $contract->fungsi);
                    });
                });
            })
            ->get();

        $recipients = $recipients->merge($matchingRecipients);

        return $recipients->unique('id')->values()->all();
    }

    /**
     * Get grouped recipients by field/fungsi for batch sending
     */
    public static function getGroupedRecipients(): array
    {
        $result = [
            'global' => self::active()->global()->get()->toArray(),
            'specific' => [],
        ];

        $specificRecipients = self::active()
            ->where('is_global', false)
            ->with('assignments')
            ->get();

        foreach ($specificRecipients as $recipient) {
            foreach ($recipient->assignments as $assignment) {
                $key = ($assignment->field ?? 'all') . '_' . ($assignment->fungsi ?? 'all');
                if (!isset($result['specific'][$key])) {
                    $result['specific'][$key] = [
                        'field' => $assignment->field,
                        'fungsi' => $assignment->fungsi,
                        'recipients' => [],
                    ];
                }
                $result['specific'][$key]['recipients'][] = $recipient;
            }
        }

        return $result;
    }
}
