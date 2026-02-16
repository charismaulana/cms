<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('contracts.add_new') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('contracts.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Field -->
                            <div>
                                <label for="field"
                                    class="block text-sm font-medium text-gray-700">{{ __('contracts.field') }}
                                    *</label>
                                <select id="field" name="field" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    <option value="">{{ __('contracts.select_field') }}</option>
                                    @foreach($fieldOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('field') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('field')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fungsi -->
                            <div>
                                <label for="fungsi" class="block text-sm font-medium text-gray-700">Fungsi *</label>
                                <select id="fungsi" name="fungsi" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @foreach(\App\Models\Contract::getFungsiOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('fungsi', 'general_services') == $value ? 'selected' : '' }}>{{ $label }}</option>
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
                                    value="{{ old('contract_number') }}" required
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
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
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
                                <input type="text" id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}"
                                    required
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
                                    value="{{ old('vendor_number') }}"
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
                                        <option value="{{ $value }}" {{ old('status', 'kontrak_awal') == $value ? 'selected' : '' }}>{{ $label }}</option>
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
                                    value="{{ old('total_value') }}" required min="0" step="0.01"
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
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                    required
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
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('dashboard') }}"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-pertamina-red text-white rounded-md hover:bg-pertamina-red-dark transition">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>