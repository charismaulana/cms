<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('nav.email_recipients') }}
            </h2>
            <a href="{{ route('email-recipients.create') }}"
                class="inline-flex items-center px-4 py-2 bg-pertamina-red text-white text-sm rounded-md hover:bg-pertamina-red-dark transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('recipients.add') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('recipients.name') }}
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('recipients.email') }}
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assignments</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('recipients.status') }}
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('contracts.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recipients as $recipient)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $recipient->name }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">{{ $recipient->email }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if($recipient->is_global)
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                Global
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                                Specific
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-600">
                                        @if($recipient->is_global)
                                            <span class="text-gray-500">All contracts</span>
                                        @elseif($recipient->assignments->isEmpty())
                                            <span class="text-gray-400">None</span>
                                        @else
                                            @php
                                                $fieldOptions = \App\Models\Contract::getFieldOptions();
                                                $fungsiOptions = \App\Models\Contract::getFungsiOptions();
                                            @endphp
                                            @foreach($recipient->assignments as $assignment)
                                                <span class="inline-block bg-gray-100 rounded px-2 py-0.5 mr-1 mb-1">
                                                    {{ $fieldOptions[$assignment->field] ?? $assignment->field ?? 'All' }} /
                                                    {{ $fungsiOptions[$assignment->fungsi] ?? $assignment->fungsi ?? 'All' }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full {{ $recipient->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $recipient->is_active ? __('recipients.active') : __('recipients.inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('email-recipients.edit', $recipient) }}"
                                                class="text-yellow-600 hover:text-yellow-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('email-recipients.destroy', $recipient) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('{{ __('recipients.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        {{ __('recipients.no_recipients') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>