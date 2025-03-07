{{-- to delete? --}}
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-sky-50 bg-sky-900 focus:outline-hidden focus:text-sky-900 focus:bg-sky-100 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-600 hover:text-sky-50 hover:bg-sky-900 text-sky-200 focus:outline-hidden focus:text-sky-900 focus:bg-sky-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
