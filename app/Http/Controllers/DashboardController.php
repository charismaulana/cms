<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with contracts table.
     */
    public function index(Request $request): View
    {
        $query = Contract::with(['realizations', 'amendments']);

        // Apply filters
        if ($request->filled('field')) {
            $query->where('field', $request->field);
        }
        if ($request->filled('fungsi')) {
            $query->where('fungsi', $request->fungsi);
        }
        if ($request->filled('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->contract_number . '%');
        }
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->filled('vendor')) {
            $query->where('vendor_name', 'like', '%' . $request->vendor . '%');
        }

        // Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Allowed columns for sorting (database columns)
        $allowedDbColumns = [
            'field',
            'fungsi',
            'contract_number',
            'title',
            'vendor_name',
            'status',
            'start_date',
            'end_date',
            'total_value',
            'realization_value',
            'created_at'
        ];

        // Computed attributes that need in-memory sorting
        $computedColumns = ['months_until_depleted'];

        if (!in_array($sortColumn, array_merge($allowedDbColumns, $computedColumns))) {
            $sortColumn = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Check if sorting by computed column
        if (in_array($sortColumn, $computedColumns)) {
            // Get all contracts and sort in memory
            $allContracts = $query->get();

            $sorted = $allContracts->sortBy(function ($contract) use ($sortColumn) {
                return $contract->$sortColumn ?? PHP_INT_MAX; // null values go to end
            }, SORT_REGULAR, $sortDirection === 'desc');

            // Manual pagination
            $page = $request->get('page', 1);
            $perPage = 15;
            $total = $sorted->count();
            $items = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

            $contracts = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $contracts = $query->orderBy($sortColumn, $sortDirection)
                ->paginate(15)
                ->withQueryString();
        }

        // Warning thresholds
        $budgetWarningPercent = Setting::getBudgetWarningPercent();
        $depleteInWarningMonths = Setting::getDepleteInWarningMonths();
        $reminderMonths = Setting::getReminderMonths();

        return view('dashboard', compact(
            'contracts',
            'sortColumn',
            'sortDirection',
            'budgetWarningPercent',
            'depleteInWarningMonths',
            'reminderMonths'
        ));
    }
}
