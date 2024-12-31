@auth
    <x-app-layout>
        <x-slot name="header">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h1>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-0 md:px-6 px-8">
                @forelse ($tracks as $season)
                    <x-card class="mb-8">
                        <h2 class="text-xl text-gray-800 font-semibold">{{ $season->first()->season }}</h2>
                        @foreach ($season as $track)
                            <p>{{ $track->name }} {{ $track->description }}</p>
                            {{-- x-dynamic-component is the equivalent of doing <x-fas-person-skiing /> --}}
                            <x-dynamic-component :component="$track->icon" class="text-sky-800 h-9" />
                        @endforeach
                    </x-card>
                @empty
                    <x-card>
                        <p>No tracks yet! <a href="{{ route('track.create') }}" class="underline">Upload some now</a>.</p>
                    </x-card>
                @endforelse
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
                <x-card>
                    Not authenticated
                </x-card>
            </div>
        </div>
    </x-app-layout>

@endauth
