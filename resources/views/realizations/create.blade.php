<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('realizations.add') }} - {{ $contract->contract_number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('realizations.store') }}">
                        @csrf
                        <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Month -->
                            <div>
                                <label for="month"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.month') }}
                                    *</label>
                                <select id="month" name="month" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @foreach($months as $num => $name)
                                        <option value="{{ $num }}" {{ old('month', $defaultMonth) == $num ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
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
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ old('year', $defaultYear) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
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
                            </div>

                            <!-- PO Number -->
                            <div>
                                <label for="po_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.po_number') }}</label>
                                <input type="text" id="po_number" name="po_number" value="{{ old('po_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                            </div>

                            <!-- SA Number -->
                            <div>
                                <label for="sa_number"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.sa_number') }}</label>
                                <input type="text" id="sa_number" name="sa_number" value="{{ old('sa_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue @error('sa_number') border-red-500 @enderror">
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
                            </div>

                            <!-- Realization Value -->
                            <div>
                                <label for="realization_value"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.value') }} (Rp)
                                    *</label>
                                <input type="number" id="realization_value" name="realization_value"
                                    value="{{ old('realization_value') }}" required min="0" step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes"
                                    class="block text-sm font-medium text-gray-700">{{ __('realizations.notes') }}</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('contracts.show', $contract) }}"
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
        function updateSaDate() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;

            if (month && year) {
                // Get last day of the selected month
                const lastDay = new Date(year, month, 0).getDate();
                const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${lastDay.toString().padStart(2, '0')}`;
                document.getElementById('sa_date').value = formattedDate;
                document.getElementById('sa_date_hidden').value = formattedDate;
            }
        }

        document.getElementById('month').addEventListener('change', updateSaDate);
        document.getElementById('year').addEventListener('change', updateSaDate);

        // Auto-fill on page load
        document.addEventListener('DOMContentLoaded', updateSaDate);
    </script>
</x-app-layout>