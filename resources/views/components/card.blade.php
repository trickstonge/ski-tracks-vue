<div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-none mb-8">
    <div class="p-6 text-gray-900">
        <x-auth-session-status class="mb-4" :status="session('success')" />
        {{ $slot}}
    </div>
</div>