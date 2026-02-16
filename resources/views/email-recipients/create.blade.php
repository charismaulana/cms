<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('recipients.add') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('email-recipients.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700">{{ __('recipients.name') }}
                                    *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700">{{ __('recipients.email') }}
                                    *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                        class="rounded border-gray-300 text-pertamina-blue focus:ring-pertamina-blue">
                                    <label for="is_active"
                                        class="ml-2 text-sm text-gray-700">{{ __('recipients.active') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_global" name="is_global" value="1"
                                        class="rounded border-gray-300 text-pertamina-blue focus:ring-pertamina-blue"
                                        onchange="toggleAssignments()">
                                    <label for="is_global" class="ml-2 text-sm text-gray-700">Global PIC (Receive All
                                        Reminders)</label>
                                </div>
                            </div>

                            <!-- Assignment Section -->
                            <div id="assignments-section" class="border-t pt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Field/Fungsi Assignments</h4>
                                <p class="text-xs text-gray-500 mb-3">Add field/fungsi combinations this PIC is
                                    responsible for. Leave empty for all.</p>

                                <div id="assignments-container">
                                    <div class="assignment-row flex gap-4 mb-3">
                                        <div class="flex-1">
                                            <select name="assignments[0][field]"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm">
                                                <option value="">All Fields</option>
                                                @foreach($fieldOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <select name="assignments[0][fungsi]"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm">
                                                <option value="">All Fungsi</option>
                                                @foreach($fungsiOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" onclick="removeAssignment(this)"
                                            class="text-red-600 hover:text-red-800 px-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" onclick="addAssignment()"
                                    class="text-sm text-pertamina-blue hover:underline">
                                    + Add Another Assignment
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('email-recipients.index') }}"
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
        let assignmentIndex = 1;
        const fieldOptions = @json($fieldOptions);
        const fungsiOptions = @json($fungsiOptions);

        function toggleAssignments() {
            const isGlobal = document.getElementById('is_global').checked;
            document.getElementById('assignments-section').style.display = isGlobal ? 'none' : 'block';
        }

        function addAssignment() {
            const container = document.getElementById('assignments-container');
            const row = document.createElement('div');
            row.className = 'assignment-row flex gap-4 mb-3';

            let fieldOptionsHtml = '<option value="">All Fields</option>';
            for (const [value, label] of Object.entries(fieldOptions)) {
                fieldOptionsHtml += `<option value="${value}">${label}</option>`;
            }

            let fungsiOptionsHtml = '<option value="">All Fungsi</option>';
            for (const [value, label] of Object.entries(fungsiOptions)) {
                fungsiOptionsHtml += `<option value="${value}">${label}</option>`;
            }

            row.innerHTML = `
                <div class="flex-1">
                    <select name="assignments[${assignmentIndex}][field]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm">
                        ${fieldOptionsHtml}
                    </select>
                </div>
                <div class="flex-1">
                    <select name="assignments[${assignmentIndex}][fungsi]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm">
                        ${fungsiOptionsHtml}
                    </select>
                </div>
                <button type="button" onclick="removeAssignment(this)" class="text-red-600 hover:text-red-800 px-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(row);
            assignmentIndex++;
        }

        function removeAssignment(btn) {
            const rows = document.querySelectorAll('.assignment-row');
            if (rows.length > 1) {
                btn.closest('.assignment-row').remove();
            }
        }

        // Initialize on page load
        toggleAssignments();
    </script>
</x-app-layout>