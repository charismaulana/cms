<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('nav.dashboard') }}
            </h2>
            @if(auth()->user()->canEditContracts())
                <a href="{{ route('contracts.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-pertamina-red border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pertamina-red-dark focus:bg-pertamina-red-dark active:bg-pertamina-red-dark focus:outline-none focus:ring-2 focus:ring-pertamina-red focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('contracts.add_new') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contracts Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-pertamina-blue text-white">
                                @php
                                    function sortUrl($column, $currentSort, $currentDirection)
                                    {
                                        $direction = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';
                                        return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $direction]);
                                    }
                                    function sortIcon($column, $currentSort, $currentDirection)
                                    {
                                        $baseClass = 'w-3 h-3 ml-1';
                                        if ($currentSort !== $column) {
                                            // Neutral icon (both arrows)
                                            return '<svg class="' . $baseClass . ' opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                        }
                                        if ($currentDirection === 'asc') {
                                            // Up arrow
                                            return '<svg class="' . $baseClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>';
                                        }
                                        // Down arrow
                                        return '<svg class="' . $baseClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                                    }
                                @endphp
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('field', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center gap-1">
                                            {{ __('contracts.field') }}
                                            <span>{!! sortIcon('field', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('fungsi', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center gap-1">
                                            Fungsi
                                            <span>{!! sortIcon('fungsi', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('contract_number', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center gap-1">
                                            {{ __('contracts.contract_number') }}
                                            <span>{!! sortIcon('contract_number', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                        style="min-width: 400px;">
                                        <a href="{{ sortUrl('title', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center gap-1">
                                            {{ __('contracts.title') }}
                                            <span>{!! sortIcon('title', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('vendor_name', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center gap-1">
                                            {{ __('contracts.vendor') }}
                                            <span>{!! sortIcon('vendor_name', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('status', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center gap-1">
                                            {{ __('contracts.status') }}
                                            <span>{!! sortIcon('status', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.duration') }}
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('total_value', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center justify-end gap-1">
                                            {{ __('contracts.total_value') }}
                                            <span>{!! sortIcon('total_value', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('realization_value', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center justify-end gap-1">
                                            {{ __('contracts.realization_value') }}
                                            <span>{!! sortIcon('realization_value', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.last_update') }}
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.remaining_value') }}
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.remaining_percent') }}
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.monthly_prognosis') }}
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        <a href="{{ sortUrl('months_until_depleted', $sortColumn, $sortDirection) }}"
                                            class="hover:underline flex items-center justify-end gap-1">
                                            {{ __('contracts.months_until_depleted') }}
                                            <span>{!! sortIcon('months_until_depleted', $sortColumn, $sortDirection) !!}</span>
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.estimated_depletion') }}
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.work_budget_diff') }}
                                    </th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                        {{ __('contracts.actions') }}
                                    </th>
                                </tr>
                                <!-- Filter Row -->
                                <tr class="bg-gray-100">
                                    <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2">
                                            <select name="field"
                                                class="w-full text-xs text-gray-900 bg-white border-gray-300 rounded"
                                                onchange="document.getElementById('filterForm').submit()">
                                                <option value="">Semua</option>
                                                @foreach(\App\Models\Contract::getFieldOptions() as $value => $label)
                                                    <option value="{{ $value }}" {{ request('field') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1 py-2">
                                            <select name="fungsi"
                                                class="w-full text-xs text-gray-900 bg-white border-gray-300 rounded"
                                                onchange="document.getElementById('filterForm').submit()">
                                                <option value="">Semua</option>
                                                @foreach(\App\Models\Contract::getFungsiOptions() as $value => $label)
                                                    <option value="{{ $value }}" {{ request('fungsi') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1 py-2">
                                            <input type="text" name="contract_number"
                                                value="{{ request('contract_number') }}" placeholder="Filter..."
                                                class="w-full text-xs text-gray-900 bg-white border-gray-300 rounded"
                                                onkeypress="if(event.keyCode==13) document.getElementById('filterForm').submit()">
                                        </td>
                                        <td class="px-1 py-2">
                                            <input type="text" name="title" value="{{ request('title') }}"
                                                placeholder="Filter..."
                                                class="w-full text-xs text-gray-900 bg-white border-gray-300 rounded"
                                                onkeypress="if(event.keyCode==13) document.getElementById('filterForm').submit()">
                                        </td>
                                        <td class="px-1 py-2">
                                            <input type="text" name="vendor" value="{{ request('vendor') }}"
                                                placeholder="Filter..."
                                                class="w-full text-xs text-gray-900 bg-white border-gray-300 rounded"
                                                onkeypress="if(event.keyCode==13) document.getElementById('filterForm').submit()">
                                        </td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2"></td>
                                        <td class="px-1 py-2">
                                            <a href="{{ route('dashboard') }}"
                                                class="text-xs text-red-600 hover:underline">Reset</a>
                                        </td>
                                    </form>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($contracts as $index => $contract)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap">{{ $contracts->firstItem() + $index }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap">{{ $contract->field_label }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap">{{ $contract->fungsi_label }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap font-mono text-xs">
                                            @php
                                                $hasWarning = ($contract->remaining_percent <= $budgetWarningPercent) || 
                                                              ($contract->months_until_depleted !== null && $contract->months_until_depleted <= $depleteInWarningMonths);
                                            @endphp
                                            <div class="flex items-center gap-1" x-data="{ showWarningModal: false }">
                                                @if($hasWarning)
                                                    <button @click="showWarningModal = true" class="text-amber-500 hover:text-amber-600 focus:outline-none">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <!-- Warning Modal -->
                                                    <div x-show="showWarningModal" 
                                                         x-transition
                                                         @click.stop
                                                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                                         @click.self="showWarningModal = false">
                                                        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md mx-4" @click.stop>
                                                            <div class="flex justify-between items-center mb-4">
                                                                <p class="font-semibold text-amber-600 text-lg flex items-center gap-2">
                                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    Peringatan Kontrak
                                                                </p>
                                                                <button @click="showWarningModal = false" class="text-gray-400 hover:text-gray-600">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <!-- Contract Info -->
                                                            <div class="bg-gray-50 p-3 rounded mb-4 text-sm">
                                                                <div class="grid grid-cols-2 gap-2">
                                                                    <div>
                                                                        <span class="text-gray-500">Field:</span>
                                                                        <span class="font-medium">{{ $contract->field_label }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-500">Fungsi:</span>
                                                                        <span class="font-medium">{{ $contract->fungsi_label }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <span class="text-gray-500">No. Kontrak:</span>
                                                                    <span class="font-medium font-mono">{{ $contract->contract_number }}</span>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <span class="text-gray-500">Judul:</span>
                                                                    <span class="font-medium">{{ $contract->title }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="space-y-4">
                                                                @if($contract->remaining_percent <= $budgetWarningPercent)
                                                                    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                                                        <p class="font-semibold text-red-800">💰 Sisa Anggaran Rendah</p>
                                                                        <p class="text-sm text-red-700">Sisa anggaran hanya <strong>{{ $contract->remaining_percent }}%</strong> (di bawah batas {{ $budgetWarningPercent }}%)</p>
                                                                        <p class="text-xs text-red-600 mt-1">Sisa: Rp {{ number_format($contract->remaining_value, 0, ',', '.') }}</p>
                                                                    </div>
                                                                @endif
                                                                @if($contract->months_until_depleted !== null && $contract->months_until_depleted <= $depleteInWarningMonths)
                                                                    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                                                        <p class="font-semibold text-red-800">💸 Anggaran Akan Segera Habis</p>
                                                                        <p class="text-sm text-red-700">Anggaran diperkirakan habis dalam <strong>{{ $contract->months_until_depleted }} bulan</strong> (di bawah batas {{ $depleteInWarningMonths }} bulan)</p>
                                                                        <p class="text-xs text-red-600 mt-1">Est. Depletion: {{ $contract->estimated_depletion_month }}</p>
                                                                    </div>
                                                                @endif
                                                                @php
                                                                    $effectiveEnd = \Carbon\Carbon::parse($contract->effective_end_date);
                                                                    $baseEnd = $contract->end_date;
                                                                    $daysLeft = round(now()->diffInDays($effectiveEnd, false));
                                                                    $durationThresholdDays = $reminderMonths * 30;
                                                                @endphp
                                                                @if($effectiveEnd->ne($baseEnd) && $daysLeft >= 0)
                                                                    @if($daysLeft <= $durationThresholdDays)
                                                                        <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                                                            <p class="font-semibold text-red-800">📅 Durasi Kontrak Akan Berakhir</p>
                                                                            <p class="text-sm text-red-700">Effective End Date: <strong>{{ $effectiveEnd->format('d M Y') }}</strong></p>
                                                                            <p class="text-sm text-red-700">Sisa waktu: <strong>{{ $daysLeft }} hari</strong> (di bawah batas {{ $reminderMonths }} bulan)</p>
                                                                        </div>
                                                                    @else
                                                                        <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded">
                                                                            <p class="font-semibold text-green-800">📅 Durasi Kontrak</p>
                                                                            <p class="text-sm text-green-700">Effective End Date: <strong>{{ $effectiveEnd->format('d M Y') }}</strong></p>
                                                                            <p class="text-sm text-green-700">Sisa waktu: <strong>{{ $daysLeft }} hari</strong></p>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="mt-4 text-center">
                                                                <a href="{{ route('contracts.show', $contract) }}" class="text-sm text-pertamina-blue hover:underline">Lihat Detail Kontrak →</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <span>{{ $contract->contract_number }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4" style="min-width: 400px;">
                                            <div>{{ $contract->title }}</div>
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="max-w-xs truncate">{{ $contract->vendor_name }}</div>
                                            @if($contract->vendor_number)
                                                <div class="text-xs text-gray-500">{{ $contract->vendor_number }}</div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full 
                                                                                                {{ $contract->amendments->count() == 0 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $contract->current_status }}
                                            </span>
                                            @if($contract->amendments->where('is_bridging', true)->count() > 0)
                                                <span
                                                    class="ml-1 px-1 py-0.5 text-xs rounded bg-yellow-100 text-yellow-800">B</span>
                                            @endif
                                        </td>
                                        @php
                                            $durationEffectiveEnd = \Carbon\Carbon::parse($contract->effective_end_date);
                                            $durationDaysLeft = round(now()->diffInDays($durationEffectiveEnd, false));
                                            $durationThreshold = $reminderMonths * 30;
                                            $durationColor = ($durationDaysLeft >= 0 && $durationDaysLeft <= $durationThreshold) ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold';
                                        @endphp
                                        <td class="px-3 py-4 whitespace-nowrap text-xs">
                                            {{ $contract->start_date->format('d/m/Y') }} -
                                            <span class="{{ $durationColor }}">{{ $durationEffectiveEnd->format('d/m/Y') }}</span>
                                            <div class="text-gray-500">{{ $contract->contract_duration }}</div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right font-mono">
                                            Rp {{ number_format($contract->total_value_with_amendments, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right font-mono">
                                            Rp {{ number_format($contract->realization_value, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-xs">
                                            {{ $contract->last_realization_update ? $contract->last_realization_update->format('M Y') : '-' }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right font-mono">
                                            Rp {{ number_format($contract->remaining_value, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full 
                                                {{ $contract->remaining_percent <= $budgetWarningPercent ? 'bg-red-100 text-red-800' : ($contract->remaining_percent <= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ $contract->remaining_percent }}%
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right font-mono">
                                            Rp {{ number_format($contract->monthly_prognosis, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right {{ $contract->months_until_depleted !== null && $contract->months_until_depleted <= $depleteInWarningMonths ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $contract->months_until_depleted !== null ? $contract->months_until_depleted . ' bln' : '-' }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-xs font-semibold {{ $contract->months_until_depleted !== null && $contract->months_until_depleted <= $depleteInWarningMonths ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $contract->estimated_depletion_month ?? '-' }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-right">
                                            @if($contract->work_budget_diff_days !== null)
                                                <span
                                                    class="font-semibold {{ $contract->work_budget_diff_days < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $contract->work_budget_diff_days }} {{ __('contracts.days') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('contracts.show', $contract) }}"
                                                    class="text-pertamina-blue hover:text-pertamina-blue-dark"
                                                    title="{{ __('contracts.view') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @if(auth()->user()->canEditContracts())
                                                    <a href="{{ route('contracts.edit', $contract) }}"
                                                        class="text-yellow-600 hover:text-yellow-800"
                                                        title="{{ __('contracts.edit') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('contracts.destroy', $contract) }}" method="POST"
                                                        class="inline"
                                                        onsubmit="return confirm('{{ __('contracts.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                                            title="{{ __('contracts.delete') }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="px-6 py-10 text-center text-gray-500">
                                            {{ __('contracts.no_contracts') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>