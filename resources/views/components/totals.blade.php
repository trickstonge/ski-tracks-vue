@props (['totals'])

<div {{ $attributes->class(["flex justify-evenly"]) }}>
    <x-activity-icon-total :total="$totals['days']" desc="{{ Str::plural('Day', $totals['days']) }}" icon="fas-calendar-days" />
    <x-activity-icon-total :total="$totals['activities']['total']" desc="{{ Str::plural('Activity', $totals['activities']['total']) }}" icon="fas-mountain-sun" />
    @foreach(App\Models\Track::$activities as $key => $value)
        <x-activity-icon-total :total="$totals['activities'][$key]" :desc="Str::plural('Day', $totals['activities'][$key])" icon="{{ App\Models\Track::$icons[$key] }}" />
    @endforeach
    <x-activity-icon-total :total="number_format($totals['runs'])" desc="{{ Str::plural('Run', $totals['runs']) }}" icon="fas-arrow-trend-down" />
    <x-activity-icon-total :total="number_format($totals['descent'])" desc="m" icon="fas-arrow-down-long" />
    <x-activity-icon-total :total="number_format($totals['distance'])" desc="km" icon="fas-arrow-right-long" />
    <x-activity-icon-total :total="number_format($totals['time'])" desc=" Hours" icon="far-clock" />
</div>