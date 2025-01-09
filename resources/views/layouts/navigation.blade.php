{{-- Nav items are in app/Providers/AppServiceProviders.php, so they're loaded on every view --}}

<nav x-data="{ open: false }" class="bg-sky-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-8 md:px-6 sm:px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('track.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-sky-100" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="sm:hidden space-x-8 ms-10 flex">
                    @foreach ($nav as $text => $route)
                        <x-nav-link :href="route($route)" :active="request()->routeIs($route)">
                            {{ $text }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            @auth
                <!-- Settings Dropdown -->
                <div class="sm:hidden flex items-center ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 pt-1 border-b-4 border-transparent text-base leading-4 font-medium text-sky-200 hover:text-sky-50 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
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

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 hidden items-center sm:flex">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-sky-100 hover:text-sky-800 hover:bg-sky-100 focus:outline-none focus:bg-sky-100 focus:text-sky-800 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($nav as $text => $route)
                <x-responsive-nav-link :href="route($route)" :active="request()->routeIs($route)">
                    {{ $text }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-sky-200">
                <div class="px-4">
                    <div class="font-medium text-base text-sky-50">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-sky-200">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
