<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('nav.settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Reminder Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        {{ __('settings.reminder_settings') }}
                    </h3>
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Reminder Months -->
                            <div>
                                <label for="reminder_months_before" class="block text-sm font-medium text-gray-700">
                                    {{ __('settings.reminder_months') }}
                                </label>
                                <p class="text-xs text-gray-500 mb-2">{{ __('settings.reminder_months_help') }}</p>
                                <select id="reminder_months_before" name="reminder_months_before"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $reminderMonths == $i ? 'selected' : '' }}>{{ $i }}
                                            {{ __('settings.months') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Budget Warning Percentage -->
                            <div>
                                <label for="budget_warning_percentage" class="block text-sm font-medium text-gray-700">
                                    {{ __('settings.budget_warning') }}
                                </label>
                                <p class="text-xs text-gray-500 mb-2">{{ __('settings.budget_warning_help') }}</p>
                                <select id="budget_warning_percentage" name="budget_warning_percentage"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue">
                                    @foreach([5, 10, 15, 20, 25, 30, 40, 50] as $pct)
                                        <option value="{{ $pct }}" {{ $budgetWarningPercent == $pct ? 'selected' : '' }}>
                                            {{ $pct }}%
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Email Template Editor -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-pertamina-blue mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email Template
                            </h4>

                            <!-- Email Subject -->
                            <div class="mb-5">
                                <label for="email_subject" class="block text-sm font-medium text-gray-700 mb-1">
                                    Subject
                                </label>
                                <input id="email_subject" name="email_subject" type="text"
                                    value="{{ old('email_subject', $emailSubject) }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm"
                                    placeholder="[CMS] Contract Expiry & Budget Warning Reminder">
                            </div>

                            <!-- Email Greeting -->
                            <div class="mb-5">
                                <label for="email_greeting" class="block text-sm font-medium text-gray-700 mb-1">
                                    Greeting / Opening Message
                                </label>
                                <p class="text-xs text-gray-500 mb-2">Message shown at the top of the email, before the
                                    contract tables.</p>
                                <textarea id="email_greeting" name="email_greeting" rows="4"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm"
                                    placeholder="Dear Team,&#10;&#10;This is an automated reminder...">{{ old('email_greeting', $emailGreeting) }}</textarea>
                            </div>

                            <!-- Email Footer -->
                            <div class="mb-2">
                                <label for="email_footer" class="block text-sm font-medium text-gray-700 mb-1">
                                    Footer Message
                                </label>
                                <p class="text-xs text-gray-500 mb-2">Message shown at the bottom of the email, after
                                    the contract tables.</p>
                                <textarea id="email_footer" name="email_footer" rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pertamina-blue focus:ring-pertamina-blue text-sm"
                                    placeholder="This is an automated email...">{{ old('email_footer', $emailFooter) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="px-4 py-2 bg-pertamina-blue text-white rounded-md hover:bg-pertamina-blue-dark transition">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Manual Reminder -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-pertamina-blue border-b border-gray-200 pb-2 mb-4">
                        {{ __('settings.manual_reminder') }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">{{ __('settings.manual_reminder_help') }}</p>
                    <div class="flex flex-row gap-4">
                        <form method="POST" action="{{ route('settings.send-reminder') }}"
                            onsubmit="return confirm('{{ __('settings.confirm_send') }}')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-pertamina-red text-white rounded-md hover:bg-pertamina-red-dark transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('settings.send_reminder_now') }}
                            </button>
                        </form>
                        <a href="{{ route('email-logs.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-pertamina-blue text-white rounded-md hover:bg-pertamina-blue-dark transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            View Email Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>