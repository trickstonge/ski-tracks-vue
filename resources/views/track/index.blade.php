@auth
    <x-app-layout>
        <x-slot name="header">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h1>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-0 md:px-6 px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-none">
                    <div class="p-6 text-gray-900">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

@else
    <x-app-layout>
        <x-slot name="header">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h1>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-0 md:px-6 px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-none">
                    <div class="p-6 text-gray-900">
                        Not authenticated
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

@endauth
