@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block mt-1 w-full border-gray-300 focus:border-sky-600 focus:ring-sky-600 rounded-md shadow-sm']) }}>
