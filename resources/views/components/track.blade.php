@php
    $units = Auth::user()->units;
@endphp

{{-- $attributes->class takes classes passed into the component and adds them to the ones below, empty in this case --}}
<div {{ $attributes->class([]) }}>
    <div class="grid grid-cols-12 gap-x-3 items-center text-lg">
        {{-- x-dynamic-component is the equivalent of doing <x-fas-person-skiing />. Allows using variable for component name. --}}
        <div class="col-span-1 md:col-span-1 sm:col-span-6"><x-dynamic-component :component="App\Models\Track::$icons[$track->activity]" class="fill-sky-800 h-9 sm:ml-auto" /></div>
        <div class="col-span-3 md:col-span-4 sm:col-span-6 sm:order-first">
            {{-- if we're on the track.show view, don't print the link --}}
            @if(Route::is('track.show'))
                {{ $track->name }}
            @else
                <a href="{{ route('track.show', $track) }}" class="underline">{{ $track->name }}</a>
            @endif
        </div>
        <div class="col-span-5 md:col-span-7 sm:col-span-12">{{ $track->description }}</div>
        <div class="col-span-2 md:col-span-6">{{ $track->start->format('n/d/y g:i A') }}</div>
        <div class="col-span-1 md:col-span-6 text-right">{{ $track->duration_formated }}</div>
    </div>
    <div class="grid grid-cols-12 gap-x-3 items-center mt-4 text-gray-600 sm:block">
        @if ($track->activity == 'x-country')
            <div class="col-span-1 lg:hidden"></div>
            <div class="col-span-3 lg:col-span-6">Avg Speed: {{ $track->metrics->average_speed }} {{ $units['speed'] }}</div>
            <div class="col-span-3 lg:col-span-6">Max Speed: {{ $track->metrics->max_speed }} {{ $units['speed'] }}</div>
            <div class="col-span-2 lg:col-span-6">Vertical: {{ $track->metrics->total_descent }} {{ $units['vertical'] }}</div>
            <div class="col-span-3 lg:col-span-6">Distance: {{ $track->metrics->distance }} {{ $units['distance'] }}</div>
        @else
            <div class="col-span-1 lg:col-span-6">{{ $track->metrics->descents }} {{ Str::plural('Run', $track->metrics->descents) }} </div>
            <div class="col-span-3 lg:col-span-6">Avg Descent Speed: {{ $track->metrics->average_descent_speed }} {{ $units['speed'] }}</div>
            <div class="col-span-3 lg:col-span-6">Max Speed: {{ $track->metrics->max_speed }} {{ $units['speed'] }}</div>
            <div class="col-span-2 lg:col-span-6">Vertical: {{ $track->metrics->total_descent }} {{ $units['vertical'] }}</div>
            <div class="col-span-3 lg:col-span-6">Ski Distance: {{ $track->metrics->descent_distance }} {{ $units['distance'] }}</div>
        @endif
    </div>

    {{ $slot }}

</div>