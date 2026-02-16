<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('auth.change_password_info') }}
    </div>

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('auth.new_password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth.confirm_password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="bg-pertamina-red hover:bg-pertamina-red-dark">
                {{ __('auth.change_password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>