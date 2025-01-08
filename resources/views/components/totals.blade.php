@php
    $units = Auth::user()->units;
@endphp

@props (['totals'])

<div {{ $attributes->class(["grid grid-cols-9 gap-x-3 gap-y-4 lg:grid-cols-3 sm:grid-cols-2"]) }}>
    <x-activity-icon-total :total="$totals['days']" :desc="Str::plural('Day', $totals['days'])" icon="fas-calendar-days" />
    <x-activity-icon-total :total="$totals['activities']['total']" :desc="Str::plural('Activity', $totals['activities']['total'])" icon="fas-mountain-sun" />
    @foreach(App\Models\Track::$activities as $key => $value)
        <x-activity-icon-total :total="$totals['activities'][$key]" :desc="Str::plural('Day', $totals['activities'][$key])" :icon="App\Models\Track::$icons[$key]" />
    @endforeach
    <x-activity-icon-total :total="number_format($totals['runs'])" :desc="Str::plural('Run', $totals['runs'])" icon="fas-arrow-trend-down" />
    <x-activity-icon-total :total="number_format($totals['descent'])" :desc="$units['vertical']" icon="fas-arrow-down-long" />
    <x-activity-icon-total :total="number_format($totals['distance'])" :desc="$units['distance']" icon="fas-arrow-right-long" />
    <x-activity-icon-total :total="number_format($totals['time'])" desc=" Hours" icon="far-clock" class="sm:col-span-2" />
</div>