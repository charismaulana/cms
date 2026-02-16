<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractAmendment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AmendmentController extends Controller
{
    /**
     * Store a newly created amendment.
     */
    public function store(Request $request, Contract $contract): RedirectResponse
    {
        $validated = $request->validate([
            'amendment_type' => 'required|in:value_only,time_only,value_and_time',
            'is_bridging' => 'boolean',
            'added_value' => 'nullable|numeric|min:0',
            'new_end_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate based on type
        if ($validated['amendment_type'] === 'value_only' || $validated['amendment_type'] === 'value_and_time') {
            if (empty($validated['added_value'])) {
                return back()->withErrors(['added_value' => 'Nilai tambahan harus diisi untuk tipe ini.']);
            }
        }

        if ($validated['amendment_type'] === 'time_only' || $validated['amendment_type'] === 'value_and_time') {
            if (empty($validated['new_end_date'])) {
                return back()->withErrors(['new_end_date' => 'Tanggal akhir baru harus diisi untuk tipe ini.']);
            }
        }

        // Get next amendment number
        $nextNumber = $contract->amendments()->max('amendment_number') + 1;

        $contract->amendments()->create([
            'amendment_number' => $nextNumber,
            'amendment_type' => $validated['amendment_type'],
            'is_bridging' => $validated['is_bridging'] ?? false,
            'added_value' => $validated['added_value'] ?? null,
            'new_end_date' => $validated['new_end_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('contracts.edit', $contract)
            ->with('success', 'Amandemen berhasil ditambahkan');
    }

    /**
     * Update the specified amendment.
     */
    public function update(Request $request, ContractAmendment $amendment): RedirectResponse
    {
        $validated = $request->validate([
            'amendment_type' => 'required|in:value_only,time_only,value_and_time',
            'is_bridging' => 'boolean',
            'added_value' => 'nullable|numeric|min:0',
            'new_end_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $amendment->update([
            'amendment_type' => $validated['amendment_type'],
            'is_bridging' => $validated['is_bridging'] ?? false,
            'added_value' => $validated['added_value'] ?? null,
            'new_end_date' => $validated['new_end_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('contracts.edit', $amendment->contract_id)
            ->with('success', 'Amandemen berhasil diperbarui');
    }

    /**
     * Remove the specified amendment.
     */
    public function destroy(ContractAmendment $amendment): RedirectResponse
    {
        $contractId = $amendment->contract_id;
        $amendment->delete();

        return redirect()->route('contracts.edit', $contractId)
            ->with('success', 'Amandemen berhasil dihapus');
    }
}
