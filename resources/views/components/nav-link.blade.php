@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-4 border-sky-200 text-base font-medium leading-5 text-sky-50 focus:outline-hidden transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-base font-medium leading-5 text-sky-200 hover:text-sky-50 hover:border-sky-200 focus:outline-hidden transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
