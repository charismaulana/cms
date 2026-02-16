<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAmendment extends Model
{
    protected $fillable = [
        'contract_id',
        'amendment_number',
        'amendment_type',
        'is_bridging',
        'added_value',
        'new_end_date',
        'notes',
    ];

    protected $casts = [
        'is_bridging' => 'boolean',
        'added_value' => 'decimal:2',
        'new_end_date' => 'date',
    ];

    /**
     * Get the contract that owns this amendment.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the amendment label (Amandemen I, II, III, etc.)
     */
    public function getLabelAttribute(): string
    {
        $roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
        $num = $this->amendment_number;
        return 'Amandemen ' . ($roman[$num - 1] ?? $num);
    }

    /**
     * Get the amendment type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->amendment_type) {
            'value_only' => 'Tambah Nilai',
            'time_only' => 'Tambah Waktu',
            'value_and_time' => 'Tambah Nilai & Waktu',
            default => $this->amendment_type,
        };
    }
}
