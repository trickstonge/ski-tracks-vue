<x-app-layout title="Profile">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h1>
    </x-slot>

    <div class="p-8 sm:p-4 bg-white shadow rounded-lg sm:rounded-none">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="p-8 sm:p-4 bg-white shadow rounded-lg sm:rounded-none">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-8 sm:p-4 bg-white shadow rounded-lg sm:rounded-none">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
