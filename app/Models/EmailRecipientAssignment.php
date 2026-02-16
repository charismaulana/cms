<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailRecipientAssignment extends Model
{
    protected $fillable = [
        'email_recipient_id',
        'field',
        'fungsi',
    ];

    /**
     * Get the email recipient
     */
    public function emailRecipient(): BelongsTo
    {
        return $this->belongsTo(EmailRecipient::class);
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
}
