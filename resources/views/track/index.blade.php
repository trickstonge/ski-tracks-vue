@php
    use App\Models\Track;
@endphp

@auth
    <x-app-layout>
        <x-slot name="header">
            Ski Tracks
        </x-slot>

        {{-- x-data sets it as an alpine JS component, so it starts paying attention to it. Used in text-input.blade.php. Sets value for description. --}}
        <x-card class="mb-8" x-data="{ description: '{{ request()->input('description', old('description')) }}' }">
            <form method="GET" action="{{ route('track.index') }}" x-ref="filters" class="flex gap-5">
                {{-- @csrf --}}
                <div class="grow">
                    <x-input-label for="description" value="Description Search" />
                    {{-- x-model makes this input use the value of description, set above in x-data --}}
                    <x-text-input id="description" type="text" name="description" x-model="description" close />
                </div>

                <div class="grow">
                    <x-input-label for="filterType" value="Filter Type" />
                    {{-- x-bind:disabled changes if there's a value in the description field --}}
                    <x-select name="filterType" :options="['normal' => 'Normal', 'since' => 'Days Since']" x-bind:disabled="description.length == 0" />
                </div>
            
                <div class="grow">
                    <x-input-label for="activity" value="Activity" />
                    <x-select name="activity" :options="Track::$activities" all />
                    <x-input-error :messages="$errors->get('activity')" class="mt-2" />
                </div>

                <div class="pt-7">
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
                    <x-activity-icon-total :total="$season->totals['days']" desc="{{ Str::plural('Day', $season->totals['days']) }}" icon="fas-calendar-days" />
                    <x-activity-icon-total :total="$season->totals['activities']['total']" desc="{{ Str::plural('Activity', $season->totals['activities']['total']) }}" icon="fas-mountain-sun" />
                    @foreach(Track::$activities as $key => $value)
                        <x-activity-icon-total :total="$season->totals['activities'][$key]" :desc="Str::plural('Day', $season->totals['activities'][$key])" icon="{{ Track::$icons[$key] }}" />
                    @endforeach
                    <x-activity-icon-total :total="$season->totals['runs']" desc="{{ Str::plural('Run', $season->totals['runs']) }}" icon="fas-arrow-trend-down" />
                    <x-activity-icon-total :total="$season->totals['descent']" desc="m" icon="fas-arrow-down-long" />
                    <x-activity-icon-total :total="$season->totals['distance']" desc="km" icon="fas-arrow-right-long" />
                    <x-activity-icon-total :total="$season->totals['time']" desc=" Hours" icon="far-clock" />
                </div>

                @foreach ($season as $track)
                    <x-track :$track @class(['py-4 border-t border-sky-500', 'bg-sky-100' => $track->id == $firstTrackID]) />
                @endforeach
            </x-card>
        @empty
        {{-- todo no results vs no tracks yet --}}
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
