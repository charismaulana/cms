<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts.
     */
    public function index(): View
    {
        $contracts = Contract::with('realizations')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new contract.
     */
    public function create(): View
    {
        $fieldOptions = Contract::getFieldOptions();
        $statusOptions = Contract::getStatusOptions();

        return view('contracts.create', compact('fieldOptions', 'statusOptions'));
    }

    /**
     * Store a newly created contract.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'field' => 'required|in:' . implode(',', array_keys(Contract::getFieldOptions())),
            'contract_number' => 'required|string|max:255|unique:contracts',
            'title' => 'required|string|max:255',
            'vendor_name' => 'required|string|max:255',
            'vendor_number' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', array_keys(Contract::getStatusOptions())),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_value' => 'required|numeric|min:0',
        ]);

        Contract::create($validated);

        return redirect()->route('dashboard')
            ->with('success', __('messages.contract_created'));
    }

    /**
     * Display the specified contract.
     */
    public function show(Contract $contract): View
    {
        $contract->load('realizations');

        // Get realizations grouped by year
        $realizationsByYear = $contract->realizations()
            ->selectRaw('year, SUM(realization_value) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return view('contracts.show', compact('contract', 'realizationsByYear'));
    }

    /**
     * Show the form for editing the contract.
     */
    public function edit(Contract $contract): View
    {
        $fieldOptions = Contract::getFieldOptions();
        $statusOptions = Contract::getStatusOptions();

        return view('contracts.edit', compact('contract', 'fieldOptions', 'statusOptions'));
    }

    /**
     * Update the specified contract.
     */
    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $validated = $request->validate([
            'field' => 'required|in:' . implode(',', array_keys(Contract::getFieldOptions())),
            'contract_number' => 'required|string|max:255|unique:contracts,contract_number,' . $contract->id,
            'title' => 'required|string|max:255',
            'vendor_name' => 'required|string|max:255',
            'vendor_number' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', array_keys(Contract::getStatusOptions())),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_value' => 'required|numeric|min:0',
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.show', $contract)
            ->with('success', __('messages.contract_updated'));
    }

    /**
     * Remove the specified contract.
     */
    public function destroy(Contract $contract): RedirectResponse
    {
        $contract->delete();

        return redirect()->route('dashboard')
            ->with('success', __('messages.contract_deleted'));
    }

    /**
     * Update prognosa configuration for a contract.
     */
    public function updatePrognosa(Request $request, Contract $contract): RedirectResponse
    {
        $realizationIds = $request->input('realization_ids', []);

        // Flatten comma-separated IDs (from grouped months)
        $flattenedIds = [];
        foreach ($realizationIds as $idGroup) {
            $ids = explode(',', $idGroup);
            foreach ($ids as $id) {
                if (is_numeric($id)) {
                    $flattenedIds[] = (int) $id;
                }
            }
        }

        $contract->update([
            'prognosa_realization_ids' => !empty($flattenedIds) ? array_unique($flattenedIds) : null,
        ]);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Konfigurasi prognosa berhasil disimpan');
    }
}
