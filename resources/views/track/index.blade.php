{{-- to delete --}}
@auth
    <x-app-layout>
        <x-slot name="header">
            Ski Tracks
        </x-slot>

        {{-- x-data sets it as an alpine JS component, so it starts paying attention to it. Used in text-input.blade.php. Sets value for description. --}}
        <x-card class="mb-8" x-data="{ description: '{{ request()->input('description', old('description')) }}' }">
            <form method="POST" action="{{ route('track.index') }}" x-ref="filters" class="flex gap-5 md:block">
                @csrf
                <div class="grow">
                    <x-input-label for="description" value="Description Search" />
                    {{-- x-model makes this input use the value of description, set above in x-data --}}
                    <x-text-input id="description" type="text" name="description" x-model="description" close />
                </div>

                <div class="grow md:mt-4">
                    <x-input-label for="filterType" value="Filter Type" />
                    {{-- x-bind:disabled changes if there's a value in the description field --}}
                    <x-select name="filterType" :options="['normal' => 'Normal', 'since' => 'Days Since']" x-bind:disabled="description.length == 0" />
                </div>
            
                <div class="grow md:mt-4">
                    <x-input-label for="activity" value="Activity" />
                    <x-select name="activity" :options="App\Models\Track::$activities" all />
                    <x-input-error :messages="$errors->get('activity')" class="mt-2" />
                </div>

                <div class="pt-7">
                    <x-primary-button>
                        Filter
                    </x-primary-button>
                </div>
            </form>
        </x-card>

        @if ($totals)
            <x-card class="mb-8">
                <h2 class="text-2xl text-gray-800 font-semibold">All Seasons</h2>
                <x-totals :totals="$totals" class="mt-6" />
                <h3 class="text-xl text-gray-800 mt-6 sm:text-center">Jump to Season</h3>
                <div class="flex flex-wrap gap-y-2 sm:text-center">
                    @foreach ($tracks as $season)
                        <a href="#{{ str_replace('/', '-', $season['tracks']->first()->season) }}" class="underline w-1/6 md:w-1/4 sm:w-1/2">{{ $season['tracks']->first()->season }}</a>
                    @endforeach
                </div>
            </x-card>
        @endif
        
        @forelse ($tracks as $season)
            <x-card class="mb-8" id="{{ str_replace('/', '-', $season['tracks']->first()->season) }}">
                <h2 class="text-2xl text-gray-800 font-semibold">{{ $season['tracks']->first()->season }}</h2>
                <x-totals :totals="$season['totals']" class="my-6" />

                @foreach ($season['tracks'] as $track)
                    <x-track :$track @class(['py-4 border-t border-sky-500', 'bg-sky-100' => $track->id == $firstTrackID]) />
                @endforeach
            </x-card>
        @empty
            <x-card>
                @if ($noTracks)
                    <p>You haven't uploaded any tracks yet! <a href="{{ route('track.create') }}" class="underline">Upload some now</a>.</p>
                @else
                    <p>No tracks match the selected filters.</p>
                @endif
            </x-card>
        @endforelse
    </x-app-layout>

@else
    @include('about')
@endauth
