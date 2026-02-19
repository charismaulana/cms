<nav x-data="{ open: false }"
    class="bg-gradient-to-r from-pertamina-blue to-pertamina-blue-dark border-b border-pertamina-blue-dark">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-white font-bold text-xl">
                        📋 CMS
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-3 pt-1 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-white border-b-2 border-pertamina-red' : 'text-gray-300 hover:text-white' }}">
                        {{ __('nav.dashboard') }}
                    </a>

                    @if(auth()->user()->canEditContracts())
                        <a href="{{ route('realizations.index') }}"
                            class="inline-flex items-center px-3 pt-1 text-sm font-medium {{ request()->routeIs('realizations.*') ? 'text-white border-b-2 border-pertamina-red' : 'text-gray-300 hover:text-white' }}">
                            {{ __('nav.add_realization') }}
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Dropdown -->
                        <div x-data="{ adminOpen: false }" class="relative inline-flex items-center">
                            <button @click="adminOpen = !adminOpen"
                                class="inline-flex items-center px-3 pt-1 text-sm font-medium text-gray-300 hover:text-white">
                                {{ __('nav.management') }}
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="adminOpen" @click.away="adminOpen = false"
                                class="absolute left-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                                <a href="{{ route('users.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.users') }}
                                </a>
                                <a href="{{ route('email-recipients.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.email_recipients') }}
                                </a>
                                <a href="{{ route('settings.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.settings') }}
                                </a>
                                <a href="{{ route('register') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.register_user') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Language Switcher -->
                <div class="flex items-center space-x-1">
                    <a href="{{ route('language.switch', 'id') }}"
                        class="px-2 py-1 text-xs rounded {{ app()->getLocale() == 'id' ? 'bg-pertamina-red text-white' : 'bg-white/20 text-white hover:bg-white/30' }}">
                        ID
                    </a>
                    <a href="{{ route('language.switch', 'en') }}"
                        class="px-2 py-1 text-xs rounded {{ app()->getLocale() == 'en' ? 'bg-pertamina-red text-white' : 'bg-white/20 text-white hover:bg-white/30' }}">
                        EN
                    </a>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-pertamina-blue-light hover:bg-pertamina-blue focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <span
                                class="ml-2 px-2 py-0.5 text-xs rounded-full bg-pertamina-red">{{ Auth::user()->role }}</span>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-pertamina-blue-light focus:outline-none focus:bg-pertamina-blue-light focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-pertamina-blue-dark">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                {{ __('nav.dashboard') }}
            </a>
            @if(auth()->user()->canEditContracts())
                <a href="{{ route('realizations.index') }}"
                    class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                    {{ __('nav.add_realization') }}
                </a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('email-recipients.index') }}"
                    class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                    {{ __('nav.email_recipients') }}
                </a>
                <a href="{{ route('settings.index') }}"
                    class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                    {{ __('nav.settings') }}
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                    {{ __('nav.register_user') }}
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-pertamina-blue">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                    {{ __('Profile') }}
                </a>

                <!-- Language -->
                <div class="px-4 py-2 flex space-x-2">
                    <a href="{{ route('language.switch', 'id') }}"
                        class="px-3 py-1 text-xs rounded {{ app()->getLocale() == 'id' ? 'bg-pertamina-red text-white' : 'bg-white/20 text-white' }}">
                        ID
                    </a>
                    <a href="{{ route('language.switch', 'en') }}"
                        class="px-3 py-1 text-xs rounded {{ app()->getLocale() == 'en' ? 'bg-pertamina-red text-white' : 'bg-white/20 text-white' }}">
                        EN
                    </a>
                </div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block px-4 py-2 text-sm text-white hover:bg-pertamina-blue-light">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>