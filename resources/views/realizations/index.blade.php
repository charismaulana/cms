<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('nav.add_realization') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('realizations.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contract Selection -->
                            <div class="md:col-span-2">
                                <label for="contract_id"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.select_contract') }}
                                    *</label>
                                <select id="contract_id" name="contract_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    <option value="">{{ __('realizations.select_contract') }}</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                            {{ $contract->contract_number }} - {{ $contract->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contract_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Month -->
                            <div>
                                <label for="month"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.month') }}
                                    *</label>
                                <select id="month" name="month" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                @error('month')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Year -->
                            <div>
                                <label for="year"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.year') }}
                                    *</label>
                                <select id="year" name="year" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @for($y = date('Y') - 5; $y <= date('Y') + 5; $y++)
                                        <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PR Number -->
                            <div>
                                <label for="pr_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.pr_number') }}</label>
                                <input type="text" id="pr_number" name="pr_number" value="{{ old('pr_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('pr_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PO Number -->
                            <div>
                                <label for="po_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.po_number') }}</label>
                                <input type="text" id="po_number" name="po_number" value="{{ old('po_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('po_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SA Number -->
                            <div>
                                <label for="sa_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.sa_number') }}</label>
                                <input type="text" id="sa_number" name="sa_number" value="{{ old('sa_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('sa_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SA Date -->
                            <div>
                                <label for="sa_date"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.sa_date') }}</label>
                                <input type="date" id="sa_date" value="{{ date('Y-m-d') }}" disabled
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm cursor-not-allowed">
                                <input type="hidden" name="sa_date" id="sa_date_hidden" value="{{ date('Y-m-d') }}">
                                @error('sa_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Realization Value -->
                            <div>
                                <label for="realization_value"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.value') }} (Rp)
                                    *</label>
                                <input type="number" id="realization_value" name="realization_value"
                                    value="{{ old('realization_value') }}" required min="0" step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('realization_value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.notes') }}</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">{{ old('notes') }}</textarea>
                                @error('notes')
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

    <script>
        function updateSADate() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;

            // Get last day of selected month
            const lastDay = new Date(year, month, 0).getDate();

            // Format date as YYYY-MM-DD
            const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(lastDay).padStart(2, '0')}`;

            // Update both visible and hidden inputs
            document.getElementById('sa_date').value = formattedDate;
            document.getElementById('sa_date_hidden').value = formattedDate;
        }

        // Update on page load
        document.addEventListener('DOMContentLoaded', updateSADate);

        // Update when month or year changes
        document.getElementById('month').addEventListener('change', updateSADate);
        document.getElementById('year').addEventListener('change', updateSADate);
    </script>
</x-app-layout>