{{-- $attributes->class takes classes passed into the component and adds them to the ones below, empty in this case --}}
<div {{ $attributes->class([]) }}>
    <div class="flex justify-between items-center text-lg">
        {{-- x-dynamic-component is the equivalent of doing <x-fas-person-skiing />. Allows using variable for component name. --}}
        <div class="w-1/12"><x-dynamic-component :component="App\Models\Track::$icons[$track->activity]" class="fill-sky-800 h-9" /></div>
        <div class="w-3/12">
            {{-- if we're on the track.show view, don't print the link --}}
            @if(Route::is('track.show'))
                {{ $track->name }}
            @else
                <a href="{{ route('track.show', $track) }}" class="underline">{{ $track->name }}</a>
            @endif
        </div>
        <div class="w-5/12">{{ $track->description }}</div>
        <div class="w-2/12">{{ $track->start->format('n/d/y g:i A') }}</div>
        <div class="w-1/12 text-right">{{ $track->duration_formated }}</div>
    </div>
    <div class="flex justify-between items-center mt-4 text-gray-600">
        @if ($track->activity == 'x-country')
            <div class="w-1/12"></div>
            <div class="w-3/12">Avg Speed: {{ $track->metrics->average_speed }} km/h</div>
            <div class="w-3/12">Max Speed: {{ $track->metrics->max_speed }} km/h</div>
            <div class="w-2/12">Vertical: {{ $track->metrics->total_descent }} m</div>
            <div class="w-3/12">Distance: {{ $track->metrics->distance }} km</div>
        @else
            <div class="w-1/12">{{ $track->metrics->descents }} {{ Str::plural('Run', $track->metrics->descents) }} </div>
            <div class="w-3/12">Avg Descent Speed: {{ $track->metrics->average_descent_speed }} km/h</div>
            <div class="w-3/12">Max Speed: {{ $track->metrics->max_speed }} km/h</div>
            <div class="w-2/12">Vertical: {{ $track->metrics->total_descent }} m</div>
            <div class="w-3/12">Ski Distance: {{ $track->metrics->descent_distance }} km</div>
        @endif
    </div>

    {{ $slot }}

</div>