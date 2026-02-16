<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractRealization;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RealizationController extends Controller
{
    /**
     * Show the form for adding realization data.
     */
    public function index(): View
    {
        $contracts = Contract::orderBy('title')->get();

        return view('realizations.index', compact('contracts'));
    }

    /**
     * Show the form for creating realization for a specific contract.
     */
    public function create(Contract $contract): View
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

        $years = range(date('Y') - 5, date('Y') + 5);

        // Get last realization for this contract to auto-select next month
        $lastRealization = $contract->realizations()
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        if ($lastRealization) {
            // Calculate next month from last realization
            $nextMonth = $lastRealization->month + 1;
            $nextYear = $lastRealization->year;

            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear++;
            }

            $defaultMonth = $nextMonth;
            $defaultYear = $nextYear;
        } else {
            // No previous realization, use current month
            $defaultMonth = (int) date('n');
            $defaultYear = (int) date('Y');
        }

        return view('realizations.create', compact('contract', 'months', 'years', 'defaultMonth', 'defaultYear'));
    }

    /**
     * Store a newly created realization.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'pr_number' => 'nullable|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'sa_number' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if (
                        $value && ContractRealization::where('contract_id', $request->contract_id)
                            ->where('sa_number', $value)
                            ->exists()
                    ) {
                        $fail('Nomor SA sudah digunakan di kontrak ini.');
                    }
                },
            ],
            'sa_date' => 'nullable|date',
            'realization_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        ContractRealization::create($validated);

        return redirect()->route('contracts.show', $validated['contract_id'])
            ->with('success', __('messages.realization_added'));
    }

    /**
     * Show the form for editing realization.
     */
    public function edit(ContractRealization $realization): View
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

        $years = range(date('Y') - 5, date('Y') + 5);

        return view('realizations.edit', compact('realization', 'months', 'years'));
    }

    /**
     * Update the specified realization.
     */
    public function update(Request $request, ContractRealization $realization): RedirectResponse
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'pr_number' => 'nullable|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'sa_number' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($realization) {
                    if (
                        $value && ContractRealization::where('contract_id', $realization->contract_id)
                            ->where('sa_number', $value)
                            ->where('id', '!=', $realization->id)
                            ->exists()
                    ) {
                        $fail('Nomor SA sudah digunakan di kontrak ini.');
                    }
                },
            ],
            'sa_date' => 'nullable|date',
            'realization_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $realization->update($validated);

        return redirect()->route('contracts.show', $realization->contract_id)
            ->with('success', __('messages.realization_updated'));
    }

    /**
     * Remove the specified realization.
     */
    public function destroy(ContractRealization $realization): RedirectResponse
    {
        $contractId = $realization->contract_id;
        $realization->delete();

        return redirect()->route('contracts.show', $contractId)
            ->with('success', __('messages.realization_deleted'));
    }
}
