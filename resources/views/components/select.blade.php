{{-- to delete --}}
@props(['name' => '', 'options' => null, 'all' => false])

<select {{ $attributes->merge(['class' => 'block mt-1 w-full border-gray-300 focus:border-sky-600 focus:ring-sky-600 disabled:text-gray-400 rounded-md shadow-xs']) }} id="{{ $name }}" name="{{ $name }}">

    @if($all)
        <option value="">All</option>
    @endif
    
    @foreach ($options as $key => $value)
        <option value="{{ $key }}" @selected($key == request()->input($name, old($name)))>{{ $value }}</option>
    @endforeach
</select>