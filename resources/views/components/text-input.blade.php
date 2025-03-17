@props(['disabled' => false, 'name' => '', 'close' => false])

{{-- even though we use $attributes->merge to add the incoing attributes, because name is used above as a laravel variable, it has to be added in the html again. Otherwise it's only read as a variable and not printed. --}}

<div class="relative">
	{{-- x-ref, @click, and $refs is all from alpine JS --}}
	<input @disabled($disabled) {{ $attributes->merge(['class' => 'block mt-1 w-full border-gray-300 focus:border-sky-600 focus:ring-sky-600 rounded-md shadow-xs']) }} name="{{ $name }}" x-ref="input-{{ $name }}" />
	@if($close)
		{{-- x-show displays if we have discription --}}
		<button x-show=
		"description.length > 0" type="button" class="absolute top-1 right-2 text-2xl text-gray-600" @click="$refs['input-{{ $name }}'].value = ''; $refs['filters'].submit();">&times;</button>
	@endif
</div>