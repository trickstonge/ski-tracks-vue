<div {{ $attributes->class(["flex flex-col items-center"]) }}>
    <x-dynamic-component :component="$icon" class="fill-sky-800 h-9" />
    <span class="font-bold text-lg text-center">{{ $total }} {{ $desc }}</span>
</div>