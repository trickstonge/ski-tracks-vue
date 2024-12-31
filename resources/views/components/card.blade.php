{{-- $attributes->class takes classes passed into the component and adds them to the ones below --}}
<div {{ $attributes->class(["bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-none"]) }}>
    <div class="p-6 text-gray-900">
        <x-auth-session-status class="mb-4" :status="session('success')" />
        {{ $slot }}
    </div>
</div>