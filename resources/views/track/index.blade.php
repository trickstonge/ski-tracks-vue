@php
    use App\Models\Track;
@endphp

@auth
    <x-app-layout>
        <x-slot name="header">
            Ski Tracks
        </x-slot>

        <x-card class="mb-8">
            <form method="GET" action="{{ route('track.index') }}">
                {{-- @csrf --}}
                <div>
                    <x-input-label for="description" value="Description Search" />
                    <x-text-input id="description" type="text" name="description" value="{{ request('description') }}" />
                </div>

                <div>
                    <x-input-label for="activity" value="Activity" />
                    <x-select name="activity" :options="Track::$activities" />
                </div>
            
                <div class="flex justify-end mt-4">
                    <x-primary-button>
                        Filter
                    </x-primary-button>
                </div>
            </form>
        </x-card>

        @forelse ($tracks as $season)
            <x-card class="mb-8">
                <h2 class="text-2xl text-gray-800 font-semibold">{{ $season->first()->season }}</h2>
                <div class="flex justify-evenly my-8">
                    <x-activity-icon-total :total="$season->totals['days']" desc="Days" icon="fas-calendar-days" />
                    <x-activity-icon-total :total="$season->totals['activities']['total']" desc="Activities" icon="fas-mountain-sun" />
                    @foreach(Track::$activities as $key => $value)
                        <x-activity-icon-total :total="$season->totals['activities'][$key]" :desc="Str::plural('Day', $season->totals['activities'][$key])" icon="{{ Track::$icons[$key] }}" />
                    @endforeach
                    <x-activity-icon-total :total="$season->totals['runs']" desc="Runs" icon="fas-arrow-trend-down" />
                    <x-activity-icon-total :total="$season->totals['descent']" desc="m" icon="fas-arrow-down-long" />
                    <x-activity-icon-total :total="$season->totals['distance']" desc="km" icon="fas-arrow-right-long" />
                </div>

                @foreach ($season as $track)
                    <x-track :$track class="mt-4 pt-4 border-t border-sky-500" />
                @endforeach
            </x-card>
        @empty
        @php
            dd($tracks);
        @endphp
            <x-card>
                <p>No tracks yet! <a href="{{ route('track.create') }}" class="underline">Upload some now</a>.</p>
            </x-card>
        @endforelse
    </x-app-layout>

@else
    <x-app-layout>
        <x-slot name="header">
            Ski Tracks
        </x-slot>

        <x-card>
            Not authenticated
        </x-card>
    </x-app-layout>

@endauth
