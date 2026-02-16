<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Email Logs') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Back button -->
                    <div class="mb-4">
                        <a href="{{ route('settings.index') }}" class="text-pertamina-blue hover:underline">
                            &larr; {{ __('common.back') }}
                        </a>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('email-logs.index') }}"
                        class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">All</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Recipient</label>
                            <input type="text" name="recipient" value="{{ request('recipient') }}"
                                placeholder="Email..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-pertamina-blue text-white rounded-md hover:bg-blue-700 text-sm">
                                Filter
                            </button>
                            <a href="{{ route('email-logs.index') }}"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                                Reset
                            </a>
                        </div>
                    </form>

                    <!-- Logs Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-pertamina-blue text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Recipient</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Subject</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase">Duration</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase">Low Budget</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Error</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $log->sent_at ? $log->sent_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $log->recipient_name ?? 'Unknown' }}</div>
                                            <div class="text-gray-500 text-xs">{{ $log->recipient_email }}</div>
                                        </td>
                                        <td class="px-4 py-3">{{ $log->subject }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($log->duration_expiring_contracts_count > 0 && $log->duration_expiring_contract_ids)
                                                <button
                                                    onclick="showContracts({{ json_encode($log->duration_expiring_contract_ids) }}, 'Duration Expiring Contracts')"
                                                    class="text-orange-600 font-semibold hover:underline cursor-pointer">
                                                    {{ $log->duration_expiring_contracts_count }}
                                                </button>
                                            @else
                                                {{ $log->duration_expiring_contracts_count ?? 0 }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($log->low_budget_contracts_count > 0 && $log->low_budget_contract_ids)
                                                <button
                                                    onclick="showContracts({{ json_encode($log->low_budget_contract_ids) }}, 'Low Budget Contracts')"
                                                    class="text-red-600 font-semibold hover:underline cursor-pointer">
                                                    {{ $log->low_budget_contracts_count }}
                                                </button>
                                            @else
                                                {{ $log->low_budget_contracts_count }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($log->status === 'sent')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                    Sent
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                    Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-red-600 text-xs">
                                            {{ $log->error_message ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            No email logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract List Modal -->
    <div id="contractModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center"
        onclick="if(event.target===this)closeModal()">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[80vh] overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b bg-pertamina-blue text-white rounded-t-lg">
                <h3 id="modalTitle" class="text-lg font-semibold"></h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
            </div>
            <div id="modalBody" class="p-6 overflow-y-auto max-h-[60vh]">
                <div id="modalLoading" class="text-center py-8 text-gray-500">
                    Loading contracts...
                </div>
                <table id="modalTable" class="min-w-full divide-y divide-gray-200 text-sm hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contract Number
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Effective End Date</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showContracts(contractIds, title) {
            document.getElementById('contractModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalLoading').classList.remove('hidden');
            document.getElementById('modalTable').classList.add('hidden');

            fetch('/api/contracts-by-ids', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ids: contractIds })
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalLoading').classList.add('hidden');
                    const tbody = document.getElementById('modalTableBody');
                    tbody.innerHTML = '';

                    data.forEach((contract, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-gray-50';
                        tr.innerHTML = `
                        <td class="px-4 py-3">${index + 1}</td>
                        <td class="px-4 py-3 font-medium">${contract.contract_number || '-'}</td>
                        <td class="px-4 py-3">${contract.title || '-'}</td>
                        <td class="px-4 py-3">${contract.current_status || '-'}${contract.is_bridging ? ' <span style="color: #FF8C00; font-weight: bold;">(Bridging)</span>' : ''}</td>
                        <td class="px-4 py-3">${contract.effective_end_date || '-'}</td>
                    `;
                        tbody.appendChild(tr);
                    });

                    document.getElementById('modalTable').classList.remove('hidden');
                })
                .catch(err => {
                    document.getElementById('modalLoading').textContent = 'Error loading contracts.';
                    console.error(err);
                });
        }

        function closeModal() {
            document.getElementById('contractModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</x-app-layout>