<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractRealization extends Model
{
    protected $fillable = [
        'contract_id',
        'month',
        'year',
        'pr_number',
        'po_number',
        'sa_number',
        'sa_date',
        'realization_value',
        'notes',
    ];

    protected $casts = [
        'sa_date' => 'date',
        'realization_value' => 'decimal:2',
    ];

    /**
     * Get the contract that owns the realization
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get month name
     */
    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$this->month] ?? '';
    }

    /**
     * Get period label (e.g., "Januari 2026")
     */
    public function getPeriodLabelAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Boot method to sync contract realization after save
     */
    protected static function booted(): void
    {
        static::saved(function (ContractRealization $realization) {
            $realization->contract->syncRealizationValue();
        });

        static::deleted(function (ContractRealization $realization) {
            $realization->contract->syncRealizationValue();
        });
    }
}
