<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('contracts.edit') }}: {{ $contract->contract_number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('contracts.update', $contract) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Field -->
                            <div>
                                <label for="field"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.field') }}
                                    *</label>
                                <select id="field" name="field" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @foreach($fieldOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('field', $contract->field) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('field')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fungsi -->
                            <div>
                                <label for="fungsi"
                                    class="block text-sm font-medium text-gray-700">Fungsi *</label>
                                <select id="fungsi" name="fungsi" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @foreach(\App\Models\Contract::getFungsiOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('fungsi', $contract->fungsi) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('fungsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contract Number -->
                            <div>
                                <label for="contract_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.contract_number') }}
                                    *</label>
                                <input type="text" id="contract_number" name="contract_number"
                                    value="{{ old('contract_number', $contract->contract_number) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('contract_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.title') }}
                                    *</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $contract->title) }}"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vendor Name -->
                            <div>
                                <label for="vendor_name"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.vendor_name') }}
                                    *</label>
                                <input type="text" id="vendor_name" name="vendor_name"
                                    value="{{ old('vendor_name', $contract->vendor_name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('vendor_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vendor Number -->
                            <div>
                                <label for="vendor_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.vendor_number') }}</label>
                                <input type="text" id="vendor_number" name="vendor_number"
                                    value="{{ old('vendor_number', $contract->vendor_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('vendor_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.status') }}
                                    *</label>
                                <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $contract->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Value -->
                            <div>
                                <label for="total_value"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.total_value') }}
                                    (Rp) *</label>
                                <input type="number" id="total_value" name="total_value"
                                    value="{{ old('total_value', $contract->total_value) }}" required min="0"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('total_value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.start_date') }}
                                    *</label>
                                <input type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.end_date') }}
                                    *</label>
                                <input type="date" id="end_date" name="end_date"
                                    value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('contracts.show', $contract) }}"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-pertamina-red text-white rounded-md hover:bg-pertamina-red-dark transition">
                                {{ __('common.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Amendments Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Amandemen Kontrak</h3>

                    <!-- Existing Amendments List -->
                    @if($contract->amendments->count() > 0)
                        <div class="space-y-4 mb-6">
                            @foreach($contract->amendments as $amendment)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-pertamina-blue">
                                                {{ $amendment->label }}
                                                @if($amendment->is_bridging)
                                                    <span
                                                        class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Bridging</span>
                                                @endif
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $amendment->type_label }}</p>
                                            @if($amendment->added_value)
                                                <p class="text-sm mt-1">Nilai Tambahan: <span class="font-medium">Rp
                                                        {{ number_format($amendment->added_value, 0, ',', '.') }}</span></p>
                                            @endif
                                            @if($amendment->new_end_date)
                                                <p class="text-sm mt-1">Tanggal Akhir Baru: <span
                                                        class="font-medium">{{ $amendment->new_end_date->format('d/m/Y') }}</span>
                                                </p>
                                            @endif
                                            @if($amendment->notes)
                                                <p class="text-sm text-gray-500 mt-1">{{ $amendment->notes }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('amendments.destroy', $amendment) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Hapus amandemen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Add New Amendment Form -->
                    <div class="border-t pt-4">
                        <h4 class="font-medium text-gray-700 mb-3">
                            Tambah
                            {{ $contract->amendments->count() > 0 ? 'Amandemen ' . ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'][$contract->amendments->count()] ?? ($contract->amendments->count() + 1) : 'Amandemen I' }}
                        </h4>
                        <form action="{{ route('amendments.store', $contract) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Amendment Type -->
                                <div>
                                    <label for="amendment_type" class="block text-sm font-medium text-gray-700">Tipe
                                        Amandemen *</label>
                                    <select id="amendment_type" name="amendment_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue"
                                        onchange="toggleAmendmentFields()">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="value_only">Tambah Nilai Saja</option>
                                        <option value="time_only">Tambah Waktu Saja</option>
                                        <option value="value_and_time">Tambah Nilai dan Waktu</option>
                                    </select>
                                    @error('amendment_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Is Bridging -->
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_bridging" name="is_bridging" value="1"
                                        class="rounded border-gray-300 text-pertamina-blue focus:ring-pertamina-blue">
                                    <label for="is_bridging" class="ml-2 text-sm text-gray-700">Amandemen
                                        Bridging</label>
                                </div>

                                <!-- Added Value -->
                                <div id="value_field" class="hidden">
                                    <label for="added_value" class="block text-sm font-medium text-gray-700">Nilai
                                        Tambahan (Rp)</label>
                                    <input type="number" id="added_value" name="added_value" min="0" step="0.01"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @error('added_value')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New End Date -->
                                <div id="date_field" class="hidden">
                                    <label for="new_end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                        Akhir Baru</label>
                                    <input type="date" id="new_end_date" name="new_end_date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @error('new_end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="md:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                                    <textarea id="notes" name="notes" rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue"></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit"
                                    class="px-4 py-2 bg-pertamina-blue text-white rounded-md hover:bg-pertamina-blue-dark transition">
                                    Tambah Amandemen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAmendmentFields() {
            const type = document.getElementById('amendment_type').value;
            const valueField = document.getElementById('value_field');
            const dateField = document.getElementById('date_field');

            // Hide all first
            valueField.classList.add('hidden');
            dateField.classList.add('hidden');

            // Show based on type
            if (type === 'value_only' || type === 'value_and_time') {
                valueField.classList.remove('hidden');
            }
            if (type === 'time_only' || type === 'value_and_time') {
                dateField.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>