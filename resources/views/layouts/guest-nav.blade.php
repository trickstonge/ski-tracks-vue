@php
    //todo I wonder if there's a better place to put this, like in a controller
    $nav = [
        'Register' => 'register',
        'Login' => 'login',
    ]
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-8 md:px-6 sm:px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('track.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="sm:hidden space-x-8 -my-px ms-10 flex">
                    @foreach ($nav as $text => $route)
                        <x-nav-link :href="route($route)" :active="request()->routeIs($route)">
                            {{ $text }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 hidden items-center sm:flex">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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

    </div>
</nav>
