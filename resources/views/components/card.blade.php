{{-- $attributes->class takes classes passed into the component and adds them to the ones below --}}
<div {{ $attributes->class(["bg-white overflow-hidden shadow-xs rounded-lg sm:rounded-none"]) }}>
    <div class="p-6 text-gray-900 sm:p-3">
        {{ $slot }}
    </div>
</div>