<select id="{{ $name }}" name="{{ $name }}" class="block mt-1 w-full border-gray-300 focus:border-sky-600 focus:ring-sky-600 rounded-md shadow-sm">
    <option value="">All</option>
    @foreach ($options as $key => $value)
        <option value="{{ $key }}" @selected($key == request($name))>{{ $value }}</option>
    @endforeach
</select>