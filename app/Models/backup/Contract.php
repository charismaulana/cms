<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Contract extends Model
{
    protected $fillable = [
        'field',
        'fungsi',
        'contract_number',
        'title',
        'vendor_name',
        'vendor_number',
        'status',
        'start_date',
        'end_date',
        'total_value',
        'realization_value',
        'last_realization_update',
        'prognosa_realization_ids',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_realization_update' => 'date',
        'total_value' => 'decimal:2',
        'realization_value' => 'decimal:2',
        'prognosa_realization_ids' => 'array',
    ];

    /**
     * Field options for dropdown
     */
    public static function getFieldOptions(): array
    {
        return [
            'zona_4' => 'Zona 4',
            'prabumulih' => 'Prabumulih',
            'limau' => 'Limau',
            'adera' => 'Adera',
            'ramba' => 'Ramba',
            'pendopo' => 'Pendopo',
            'okrt' => 'OKRT',
        ];
    }

    /**
     * Status options for dropdown
     */
    public static function getStatusOptions(): array
    {
        return [
            'kontrak_awal' => 'Kontrak Awal',
            'amandemen_1' => 'Amandemen I',
            'amandemen_2' => 'Amandemen II',
            'amandemen_3' => 'Amandemen III',
            'amandemen_4' => 'Amandemen IV',
        ];
    }

    /**
     * Fungsi options for dropdown
     */
    public static function getFungsiOptions(): array
    {
        return [
            'general_services' => 'General Services',
            'warehouse' => 'Warehouse',
        ];
    }

    /**
     * Get fungsi label
     */
    public function getFungsiLabelAttribute(): string
    {
        return self::getFungsiOptions()[$this->fungsi] ?? $this->fungsi;
    }

    /**
     * Get the realizations for the contract
     */
    public function realizations(): HasMany
    {
        return $this->hasMany(ContractRealization::class);
    }

    /**
     * Get the amendments for the contract
     */
    public function amendments(): HasMany
    {
        return $this->hasMany(ContractAmendment::class)->orderBy('amendment_number');
    }

    /**
     * Get field label
     */
    public function getFieldLabelAttribute(): string
    {
        return self::getFieldOptions()[$this->field] ?? $this->field;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Get current status based on amendments
     */
    public function getCurrentStatusAttribute(): string
    {
        $amendmentCount = $this->amendments()->count();
        if ($amendmentCount === 0) {
            return 'Kontrak Awal';
        }

        $roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
        return 'Amandemen ' . ($roman[$amendmentCount - 1] ?? $amendmentCount);
    }

    /**
     * Get total contract value including amendments
     */
    public function getTotalValueWithAmendmentsAttribute(): float
    {
        $baseValue = (float) $this->total_value;
        // Use loaded relationship to avoid N+1 and ensure consistency
        $amendmentValue = (float) $this->amendments->sum('added_value');
        return $baseValue + $amendmentValue;
    }

    /**
     * Get effective end date (considering amendments)
     */
    public function getEffectiveEndDateAttribute()
    {
        // Use loaded relationship to avoid N+1 and ensure consistency
        $latestAmendment = $this->amendments
            ->whereNotNull('new_end_date')
            ->sortByDesc('amendment_number')
            ->first();

        if ($latestAmendment && $latestAmendment->new_end_date) {
            return $latestAmendment->new_end_date;
        }

        return $this->end_date;
    }

    /**
     * Get contract duration in months
     * Uses effective_end_date to account for amendments
     */
    public function getContractDurationAttribute(): string
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->effective_end_date);

        // Use Carbon's diff which properly handles varying month lengths
        $diff = $start->diff($end);

        $months = ($diff->y * 12) + $diff->m;
        $days = $diff->d;

        // Round to nearest month: >= 15 days rounds up
        if ($days >= 15) {
            $months++;
        }

        return $months . ' bulan';
    }

    /**
     * Calculate remaining value (Sisa Nilai Harga)
     * Uses total_value_with_amendments to account for amendments
     */
    public function getRemainingValueAttribute(): float
    {
        return max(0, $this->total_value_with_amendments - $this->realization_value);
    }

    /**
     * Calculate remaining percentage (Sisa Nilai Persen)
     * Uses total_value_with_amendments to account for amendments
     */
    public function getRemainingPercentAttribute(): float
    {
        $totalValue = $this->total_value_with_amendments;
        if ($totalValue <= 0) {
            return 0;
        }
        return round(($this->remaining_value / $totalValue) * 100, 2);
    }

    /**
     * Calculate monthly prognosis (average monthly spending)
     * Uses selected realizations if configured, otherwise uses all months from start
     */
    public function getMonthlyPrognosisAttribute(): float
    {
        // If custom prognosa realizations are selected, use those
        if (!empty($this->prognosa_realization_ids)) {
            $selectedRealizations = $this->realizations()
                ->whereIn('id', $this->prognosa_realization_ids)
                ->get();

            if ($selectedRealizations->count() > 0) {
                $totalValue = $selectedRealizations->sum('realization_value');
                // Count unique months (year-month combinations)
                $uniqueMonths = $selectedRealizations->unique(function ($item) {
                    return $item->year . '-' . $item->month;
                })->count();
                return round($totalValue / max(1, $uniqueMonths), 2);
            }
        }

        // Default: use total realization / months elapsed
        $start = Carbon::parse($this->start_date);
        $now = Carbon::now();

        // If contract hasn't started yet
        if ($now->lt($start)) {
            return 0;
        }

        $monthsElapsed = max(1, $start->diffInMonths($now));

        return round($this->realization_value / $monthsElapsed, 2);
    }

    /**
     * Get prognosa calculation details for display
     */
    public function getPrognosisDetailsAttribute(): array
    {
        if (!empty($this->prognosa_realization_ids)) {
            $selectedRealizations = $this->realizations()
                ->whereIn('id', $this->prognosa_realization_ids)
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            if ($selectedRealizations->count() > 0) {
                // Count unique months (year-month combinations)
                $uniqueMonths = $selectedRealizations->unique(function ($item) {
                    return $item->year . '-' . $item->month;
                })->count();
                return [
                    'type' => 'custom',
                    'realizations' => $selectedRealizations,
                    'total_value' => $selectedRealizations->sum('realization_value'),
                    'month_count' => $uniqueMonths,
                ];
            }
        }

        // Default calculation
        $start = Carbon::parse($this->start_date);
        $now = Carbon::now();
        $monthsElapsed = max(1, $start->diffInMonths($now));

        return [
            'type' => 'default',
            'start_date' => $start,
            'end_date' => $now,
            'total_value' => $this->realization_value,
            'month_count' => $monthsElapsed,
        ];
    }

    /**
     * Calculate months until budget depleted
     */
    public function getMonthsUntilDepletedAttribute(): ?float
    {
        if ($this->monthly_prognosis <= 0) {
            return null;
        }

        return round($this->remaining_value / $this->monthly_prognosis, 1);
    }

    /**
     * Calculate estimated depletion date (from last SA date)
     */
    public function getEstimatedDepletionMonthAttribute(): ?string
    {
        if ($this->months_until_depleted === null) {
            return null;
        }

        // Get the last SA date from realizations (use loaded relationship)
        $lastRealization = $this->realizations
            ->whereNotNull('sa_date')
            ->sortByDesc('sa_date')
            ->first();

        $lastSaDate = $lastRealization?->sa_date;

        // If no SA date, use today
        $baseDate = $lastSaDate ? Carbon::parse($lastSaDate) : Carbon::now();

        // Add full months and remaining days (fractional months converted to days)
        $months = (int) floor($this->months_until_depleted);
        $fractionalMonths = $this->months_until_depleted - $months;
        $additionalDays = (int) round($fractionalMonths * 30); // Approx 30 days per month

        $depletionDate = $baseDate->copy()->addMonths($months)->addDays($additionalDays);

        return $depletionDate->format('d M Y');
    }

    /**
     * Calculate difference between work completion and budget depletion in days
     * Uses effective_end_date to account for amendments
     */
    public function getWorkBudgetDiffDaysAttribute(): ?int
    {
        if ($this->months_until_depleted === null) {
            return null;
        }

        // Get the last SA date from realizations (use loaded relationship)
        $lastRealization = $this->realizations
            ->whereNotNull('sa_date')
            ->sortByDesc('sa_date')
            ->first();

        $lastSaDate = $lastRealization?->sa_date;

        // If no SA date, use today
        $baseDate = $lastSaDate ? Carbon::parse($lastSaDate) : Carbon::now();

        $endDate = Carbon::parse($this->effective_end_date);

        // Add full months and remaining days (fractional months converted to days)
        $months = (int) floor($this->months_until_depleted);
        $fractionalMonths = $this->months_until_depleted - $months;
        $additionalDays = (int) round($fractionalMonths * 30);

        $depletionDate = $baseDate->copy()->addMonths($months)->addDays($additionalDays);

        return $endDate->diffInDays($depletionDate, false);
    }

    /**
     * Get depletion calculation details for modal display
     */
    public function getDepletionDetailsAttribute(): array
    {
        // Get the last SA date from realizations
        $lastRealization = $this->realizations
            ->whereNotNull('sa_date')
            ->sortByDesc('sa_date')
            ->first();

        $lastSaDate = $lastRealization?->sa_date;
        $baseDate = $lastSaDate ? Carbon::parse($lastSaDate) : Carbon::now();
        $baseDateFormatted = $baseDate->format('d M Y');
        $baseDateLabel = $lastSaDate ? 'Tanggal SA Terakhir' : 'Hari Ini';

        $monthlyPrognosis = $this->monthly_prognosis;
        $remainingValue = $this->remaining_value;
        $monthsUntilDepleted = $this->months_until_depleted;

        $depletionDate = null;
        $effectiveEndDate = Carbon::parse($this->effective_end_date);
        $diffDays = null;

        if ($monthsUntilDepleted !== null) {
            // Add full months and remaining days (fractional months converted to days)
            $months = (int) floor($monthsUntilDepleted);
            $fractionalMonths = $monthsUntilDepleted - $months;
            $additionalDays = (int) round($fractionalMonths * 30);

            $depletionDate = $baseDate->copy()->addMonths($months)->addDays($additionalDays);
            $diffDays = $effectiveEndDate->diffInDays($depletionDate, false);
        }

        return [
            'base_date' => $baseDateFormatted,
            'base_date_label' => $baseDateLabel,
            'remaining_value' => $remainingValue,
            'monthly_prognosis' => $monthlyPrognosis,
            'months_until_depleted' => $monthsUntilDepleted,
            'depletion_date' => $depletionDate?->format('d M Y'),
            'effective_end_date' => $effectiveEndDate->format('d M Y'),
            'diff_days' => $diffDays,
        ];
    }

    /**
     * Get total realization from related records
     */
    public function calculateTotalRealization(): float
    {
        return $this->realizations()->sum('realization_value');
    }

    /**
     * Update realization value from realizations table
     */
    public function syncRealizationValue(): void
    {
        $this->realization_value = $this->calculateTotalRealization();
        $latestRealization = $this->realizations()->latest()->first();

        if ($latestRealization) {
            $this->last_realization_update = Carbon::create(
                $latestRealization->year,
                $latestRealization->month,
                1
            );
        }

        $this->save();
    }

    /**
     * Get realizations grouped by year
     */
    public function getRealizationsByYear(): array
    {
        return $this->realizations()
            ->selectRaw('year, SUM(realization_value) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->toArray();
    }
}
