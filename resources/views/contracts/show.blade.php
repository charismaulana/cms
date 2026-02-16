<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('contracts.detail') }}: {{ $contract->contract_number }}
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->canEditContracts())
                    <a href="{{ route('realizations.create', $contract) }}"
                        class="inline-flex items-center px-3 py-2 bg-pertamina-blue text-white text-sm rounded-md hover:bg-pertamina-blue-dark transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('realizations.add') }}
                    </a>
                    <a href="{{ route('contracts.edit', $contract) }}"
                        class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-sm rounded-md hover:bg-yellow-600 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('contracts.edit') }}
                    </a>
                @endif
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-3 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Contract Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        {{ __('contracts.info') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('contracts.field') }}</p>
                            <p class="font-medium">{{ $contract->field_label }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fungsi</p>
                            <p class="font-medium">{{ $contract->fungsi_label }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('contracts.contract_number') }}</p>
                            <p class="font-medium font-mono">{{ $contract->contract_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('contracts.status') }}</p>
                            <span
                                class="px-2 py-1 text-sm rounded-full {{ $contract->amendments->count() == 0 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $contract->current_status }}
                            </span>
                            @if($contract->amendments->where('is_bridging', true)->count() > 0)
                                <span class="ml-2 px-2 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Bridging</span>
                            @endif
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-sm text-gray-500">{{ __('contracts.title') }}</p>
                            <p class="font-medium">{{ $contract->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('contracts.vendor_name') }}</p>
                            <p class="font-medium">{{ $contract->vendor_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('contracts.vendor_number') }}</p>
                            <p class="font-medium">{{ $contract->vendor_number ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('contracts.duration') }}</p>
                            <p class="font-medium">{{ $contract->start_date->format('d M Y') }} -
                                {{ $contract->effective_end_date->format('d M Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $contract->contract_duration }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contract Value & Amendment History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        Nilai Kontrak & Riwayat Amandemen
                    </h3>
                    
                    <!-- Initial Contract -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-green-800">Kontrak Awal</h4>
                                <p class="text-sm text-gray-600">{{ $contract->start_date->format('d M Y') }} - {{ $contract->end_date->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-800">Rp {{ number_format($contract->total_value, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Amendments History -->
                    @if($contract->amendments->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($contract->amendments as $amendment)
                                <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div>
                                        <h4 class="font-semibold text-blue-800">
                                            {{ $amendment->label }}
                                            @if($amendment->is_bridging)
                                                <span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Bridging</span>
                                            @endif
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $amendment->type_label }}</p>
                                        @if($amendment->new_end_date)
                                            <p class="text-sm text-gray-500">Tanggal Akhir Baru: {{ $amendment->new_end_date->format('d M Y') }}</p>
                                        @endif
                                        @if($amendment->notes)
                                            <p class="text-xs text-gray-400 mt-1">{{ $amendment->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if($amendment->added_value)
                                            <p class="text-lg font-bold text-blue-800">+ Rp {{ number_format($amendment->added_value, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-sm text-gray-500">-</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total After Amendments -->
                        <div class="border-t-2 border-gray-300 pt-4">
                            <div class="flex items-center justify-between p-4 bg-pertamina-blue/10 border border-pertamina-blue/30 rounded-lg">
                                <div>
                                    <h4 class="font-bold text-pertamina-blue">Total Nilai Kontrak (Setelah Amandemen)</h4>
                                    <p class="text-sm text-gray-600">{{ $contract->start_date->format('d M Y') }} - {{ $contract->effective_end_date->format('d M Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-pertamina-blue">Rp {{ number_format($contract->total_value_with_amendments, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        {{ __('contracts.financial_summary') }}
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">{{ __('contracts.total_value') }}</p>
                            <p class="text-xl font-bold text-pertamina-blue">Rp
                                {{ number_format($contract->total_value_with_amendments, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">{{ __('contracts.realization_value') }}</p>
                            <p class="text-xl font-bold text-green-600">Rp
                                {{ number_format($contract->realization_value, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">{{ __('contracts.remaining_value') }}</p>
                            <p class="text-xl font-bold text-pertamina-red">Rp
                                {{ number_format($contract->remaining_value, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">{{ __('contracts.remaining_percent') }}</p>
                            <p
                                class="text-xl font-bold {{ $contract->remaining_percent <= 20 ? 'text-red-600' : ($contract->remaining_percent <= 50 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $contract->remaining_percent }}%
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg cursor-pointer hover:bg-gray-100 transition" 
                             x-data="{ showModal: false }" 
                             @click="showModal = true">
                            <p class="text-sm text-gray-500 flex items-center">
                                {{ __('contracts.monthly_prognosis') }}
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </p>
                            <p class="font-bold">Rp {{ number_format($contract->monthly_prognosis, 0, ',', '.') }}</p>
                            
                            <!-- Modal showing calculation with month selection -->
                            <div x-show="showModal" 
                                 x-transition
                                 @click.stop
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto"
                                 @click.self="showModal = false">
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-lg mx-4 my-8 max-h-[90vh] overflow-y-auto" @click.stop>
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="font-semibold text-pertamina-blue text-lg">Konfigurasi Prognosa/Bulan</p>
                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    @php
                                        $prognosisDetails = $contract->prognosis_details;
                                        $allRealizations = $contract->realizations()->orderBy('year')->orderBy('month')->get();
                                        $selectedIds = $contract->prognosa_realization_ids ?? [];
                                        
                                        // Group realizations by year-month and sum values
                                        $groupedRealizations = $allRealizations->groupBy(function($item) {
                                            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                                        })->map(function($group) {
                                            return [
                                                'ids' => $group->pluck('id')->toArray(),
                                                'period_label' => $group->first()->period_label,
                                                'total_value' => $group->sum('realization_value'),
                                                'year' => $group->first()->year,
                                                'month' => $group->first()->month,
                                            ];
                                        })->sortKeys();
                                    @endphp
                                    
                                    <!-- Current Calculation Summary -->
                                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                        <p class="font-semibold mb-2">Hasil Perhitungan Saat Ini:</p>
                                        <div class="text-sm space-y-1">
                                            @if($prognosisDetails['type'] === 'custom')
                                                <p class="text-green-600 text-xs mb-2">✓ Menggunakan bulan yang dipilih</p>
                                                <p>Total Nilai: <span class="font-mono">Rp {{ number_format($prognosisDetails['total_value'], 0, ',', '.') }}</span></p>
                                                <p>Jumlah Bulan: <span class="font-mono">{{ $prognosisDetails['month_count'] }} bulan</span></p>
                                            @else
                                                <p class="text-gray-500 text-xs mb-2">Menggunakan semua bulan dari awal kontrak</p>
                                                <p>Total Realisasi: <span class="font-mono">Rp {{ number_format($prognosisDetails['total_value'], 0, ',', '.') }}</span></p>
                                                <p>Bulan Berjalan: <span class="font-mono">{{ $prognosisDetails['month_count'] }} bulan</span></p>
                                                <p class="text-xs text-gray-400">({{ $prognosisDetails['start_date']->format('d M Y') }} - {{ $prognosisDetails['end_date']->format('d M Y') }})</p>
                                            @endif
                                            <hr class="my-2">
                                            <p class="text-center font-bold text-pertamina-blue text-lg">
                                                = Rp {{ number_format($contract->monthly_prognosis, 0, ',', '.') }}/bulan
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Month Selection Form -->
                                    @if($groupedRealizations->count() > 0)
                                    <form action="{{ route('contracts.update-prognosa', $contract) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <p class="font-semibold mb-2">Pilih Bulan untuk Prognosa:</p>
                                            <p class="text-xs text-gray-500 mb-3">Centang bulan yang ingin dijadikan dasar perhitungan prognosa</p>
                                            
                                            <div class="max-h-48 overflow-y-auto border rounded-lg p-2 space-y-1">
                                                @foreach($groupedRealizations as $key => $monthData)
                                                    @php
                                                        // Check if all IDs in this month are selected
                                                        $isSelected = count(array_intersect($monthData['ids'], $selectedIds)) > 0;
                                                    @endphp
                                                    <label class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" name="realization_ids[]" value="{{ implode(',', $monthData['ids']) }}"
                                                                   {{ $isSelected ? 'checked' : '' }}
                                                                   class="rounded border-gray-300 text-pertamina-blue focus:ring-pertamina-blue mr-3">
                                                            <span class="text-sm">{{ $monthData['period_label'] }}</span>
                                                        </div>
                                                        <span class="text-sm font-mono text-gray-600">Rp {{ number_format($monthData['total_value'], 0, ',', '.') }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <button type="button" onclick="document.querySelectorAll('input[name=\'realization_ids[]\']').forEach(function(cb) { cb.checked = false; });" class="text-sm text-gray-500 hover:text-gray-700">
                                                Reset (Gunakan Semua)
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-pertamina-blue text-white rounded-md hover:bg-blue-700 transition">
                                                Simpan Konfigurasi
                                            </button>
                                        </div>
                                    </form>
                                    @else
                                        <p class="text-sm text-gray-500 text-center py-4">Belum ada data realisasi</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @php
                            $depletionDetails = $contract->depletion_details;
                        @endphp
                        
                        <!-- Depleted In -->
                        <div class="bg-gray-50 p-4 rounded-lg cursor-pointer hover:bg-gray-100 transition"
                             x-data="{ showModal: false }"
                             @click="showModal = true">
                            <p class="text-sm text-gray-500 flex items-center">
                                {{ __('contracts.months_until_depleted') }}
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </p>
                            <p class="font-bold">
                                {{ $contract->months_until_depleted !== null ? $contract->months_until_depleted . ' bulan' : '-' }}
                            </p>
                            
                            <!-- Modal -->
                            <div x-show="showModal" 
                                 x-transition
                                 @click.stop
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                 @click.self="showModal = false">
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-md mx-4" @click.stop>
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="font-semibold text-pertamina-blue text-lg">Perhitungan Depleted In</p>
                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <p><span class="text-gray-500">Sisa Nilai:</span> <span class="font-mono">Rp {{ number_format($depletionDetails['remaining_value'], 0, ',', '.') }}</span></p>
                                        <p><span class="text-gray-500">Prognosa/Bulan:</span> <span class="font-mono">Rp {{ number_format($depletionDetails['monthly_prognosis'], 0, ',', '.') }}</span></p>
                                        <hr class="my-2">
                                        <p class="font-semibold">Rumus: Sisa Nilai ÷ Prognosa/Bulan</p>
                                        <p class="text-center font-bold text-pertamina-blue text-lg">
                                            = {{ $depletionDetails['months_until_depleted'] !== null ? $depletionDetails['months_until_depleted'] . ' bulan' : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Est. Depletion -->
                        <div class="bg-gray-50 p-4 rounded-lg cursor-pointer hover:bg-gray-100 transition"
                             x-data="{ showModal: false }"
                             @click="showModal = true">
                            <p class="text-sm text-gray-500 flex items-center">
                                {{ __('contracts.estimated_depletion') }}
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </p>
                            <p class="font-bold">{{ $contract->estimated_depletion_month ?? '-' }}</p>
                            
                            <!-- Modal -->
                            <div x-show="showModal" 
                                 x-transition
                                 @click.stop
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                 @click.self="showModal = false">
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-md mx-4" @click.stop>
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="font-semibold text-pertamina-blue text-lg">Perhitungan Est. Depletion</p>
                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <p><span class="text-gray-500">{{ $depletionDetails['base_date_label'] }}:</span> <span class="font-mono">{{ $depletionDetails['base_date'] }}</span></p>
                                        <p><span class="text-gray-500">Depleted In:</span> <span class="font-mono">{{ $depletionDetails['months_until_depleted'] !== null ? $depletionDetails['months_until_depleted'] . ' bulan' : '-' }}</span></p>
                                        <hr class="my-2">
                                        <p class="font-semibold">Rumus: {{ $depletionDetails['base_date_label'] }} + Depleted In</p>
                                        <p class="text-center font-bold text-pertamina-blue text-lg">
                                            = {{ $depletionDetails['depletion_date'] ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Diff Days -->
                        <div class="bg-gray-50 p-4 rounded-lg cursor-pointer hover:bg-gray-100 transition"
                             x-data="{ showModal: false }"
                             @click="showModal = true">
                            <p class="text-sm text-gray-500 flex items-center">
                                {{ __('contracts.work_budget_diff') }}
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </p>
                            @if($contract->work_budget_diff_days !== null)
                                <p class="font-bold {{ $contract->work_budget_diff_days < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $contract->work_budget_diff_days }} hari
                                </p>
                            @else
                                <p class="font-bold">-</p>
                            @endif
                            
                            <!-- Modal -->
                            <div x-show="showModal" 
                                 x-transition
                                 @click.stop
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                 @click.self="showModal = false">
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-md mx-4" @click.stop>
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="font-semibold text-pertamina-blue text-lg">Perhitungan Diff Days</p>
                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <p><span class="text-gray-500">Tanggal Akhir Kontrak:</span> <span class="font-mono">{{ $depletionDetails['effective_end_date'] }}</span></p>
                                        <p><span class="text-gray-500">Est. Depletion:</span> <span class="font-mono">{{ $depletionDetails['depletion_date'] ?? '-' }}</span></p>
                                        <hr class="my-2">
                                        <p class="font-semibold">Rumus: Tanggal Akhir - Est. Depletion</p>
                                        <p class="text-center font-bold text-lg {{ ($depletionDetails['diff_days'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            = {{ $depletionDetails['diff_days'] !== null ? $depletionDetails['diff_days'] . ' hari' : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500 text-center">
                                            @if(($depletionDetails['diff_days'] ?? 0) < 0)
                                                (Anggaran habis sebelum kontrak berakhir)
                                            @elseif(($depletionDetails['diff_days'] ?? 0) > 0)
                                                (Anggaran masih tersisa saat kontrak berakhir)
                                            @else
                                                (Anggaran habis tepat saat kontrak berakhir)
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly Realization Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        {{ __('contracts.yearly_realization') }}
                    </h3>
                    @if($realizationsByYear->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($realizationsByYear as $yearly)
                                <div class="bg-pertamina-blue/10 p-4 rounded-lg text-center">
                                    <p class="text-sm text-gray-600">{{ $yearly->year }}</p>
                                    <p class="font-bold text-pertamina-blue">Rp {{ number_format($yearly->total, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">{{ __('contracts.no_realizations') }}</p>
                    @endif
                </div>
            </div>

            <!-- Realization Details (PO, PR, SA) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        {{ __('realizations.history') }} (PO, PR, SA)
                    </h3>
                    @if($contract->realizations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left">{{ __('realizations.period') }}</th>
                                        <th class="px-4 py-3 text-left">{{ __('realizations.pr_number') }}</th>
                                        <th class="px-4 py-3 text-left">{{ __('realizations.po_number') }}</th>
                                        <th class="px-4 py-3 text-left">{{ __('realizations.sa_number') }}</th>
                                        <th class="px-4 py-3 text-left">{{ __('realizations.sa_date') }}</th>
                                        <th class="px-4 py-3 text-right">{{ __('realizations.value') }}</th>
                                        <th class="px-4 py-3 text-left">{{ __('realizations.notes') }}</th>
                                        @if(auth()->user()->canEditContracts())
                                            <th class="px-4 py-3 text-center">{{ __('contracts.actions') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($contract->realizations->sortByDesc(fn($r) => $r->year * 100 + $r->month) as $realization)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $realization->period_label }}</td>
                                            <td class="px-4 py-3 font-mono text-xs">{{ $realization->pr_number ?: '-' }}</td>
                                            <td class="px-4 py-3 font-mono text-xs">{{ $realization->po_number ?: '-' }}</td>
                                            <td class="px-4 py-3 font-mono text-xs">{{ $realization->sa_number ?: '-' }}</td>
                                            <td class="px-4 py-3">
                                                {{ $realization->sa_date ? $realization->sa_date->format('d/m/Y') : '-' }}</td>
                                            <td class="px-4 py-3 text-right font-mono">Rp
                                                {{ number_format($realization->realization_value, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 max-w-xs truncate">{{ $realization->notes ?: '-' }}</td>
                                            @if(auth()->user()->canEditContracts())
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <a href="{{ route('realizations.edit', $realization) }}"
                                                            class="text-yellow-600 hover:text-yellow-800">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('realizations.destroy', $realization) }}"
                                                            method="POST" class="inline"
                                                            onsubmit="return confirm('{{ __('realizations.confirm_delete') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">{{ __('contracts.no_realizations') }}</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>