@auth
    <x-app-layout>
        <x-slot name="header">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Ski Tracks
            </h1>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-0 md:px-6 px-8">
                <x-auth-session-status class="mb-4" :status="session('success')" />
                @forelse ($tracks as $season)
                    <x-card class="mb-8">
                        <h2 class="text-2xl text-gray-800 font-semibold">{{ $season->first()->season }}</h2>
                        <div class="flex justify-evenly my-8">
                            <x-activity-icon-total :total="$season->totals['activities']['days']" desc="Days" icon="fas-calendar-days" />
                            <x-activity-icon-total :total="$season->totals['activities']['total']" desc="Activities" icon="fas-mountain-sun" />
                            <x-activity-icon-total :total="$season->totals['activities']['skiing']" :desc="Str::plural('Day', $season->totals['activities']['skiing'])" :icon="App\Models\Track::getIcon('skiing')" />
                            <x-activity-icon-total :total="$season->totals['activities']['ski-touring']" :desc="Str::plural('Day', $season->totals['activities']['ski-touring'])" :icon="App\Models\Track::getIcon('ski-touring')" />
                            <x-activity-icon-total :total="$season->totals['activities']['x-country']" :desc="Str::plural('Day', $season->totals['activities']['x-country'])" :icon="App\Models\Track::getIcon('x-country')" />
                            <x-activity-icon-total :total="$season->totals['runs']" desc="Runs" icon="fas-arrow-trend-down" />
                            <x-activity-icon-total :total="$season->totals['descent']" desc="m" icon="fas-arrow-down-long" />
                            <x-activity-icon-total :total="$season->totals['distance']" desc="km" icon="fas-arrow-right-long" />
                        </div>

                        @foreach ($season as $track)
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-sky-500 text-lg">
                                {{-- x-dynamic-component is the equivalent of doing <x-fas-person-skiing /> --}}
                                <div class="w-1/12"><x-dynamic-component :component="$track->getIcon($track->activity)" class="fill-sky-800 h-9" /></div>
                                <div class="w-3/12">{{ $track->name }}</div>
                                <div class="w-5/12">{{ $track->description }}</div>
                                <div class="w-2/12">{{ $track->start }}</div>
                                <div class="w-1/12 text-right">{{ $track->duration }}</div>
                            </div>
                            <div class="flex justify-between items-center mt-4 text-gray-600">
                                @if ($track->activity == 'x-country')
                                    <div class="w-1/12"></div>
                                    <div class="w-3/12">Avg Speed: {{ $track->metrics->average_speed }}km/h</div>
                                    <div class="w-3/12">Max Speed: {{ $track->metrics->max_speed }}km/h</div>
                                    <div class="w-2/12">Vertical: {{ $track->metrics->total_descent }}m</div>
                                    <div class="w-3/12">Distance: {{ $track->metrics->distance }}km</div>
                                @else
                                    <div class="w-1/12">{{ $track->metrics->descents }} {{ Str::plural('Run', $track->metrics->descents) }} </div>
                                    <div class="w-3/12">Avg Descent Speed: {{ $track->metrics->average_descent_speed }}km/h</div>
                                    <div class="w-3/12">Max Speed: {{ $track->metrics->max_speed }}km/h</div>
                                    <div class="w-2/12">Vertical: {{ $track->metrics->total_descent }}m</div>
                                    <div class="w-3/12">Ski Distance: {{ $track->metrics->descent_distance }}km</div>
                                @endif
                            </div>
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
