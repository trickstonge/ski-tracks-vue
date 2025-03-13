<template>
  <div class="relative">
    <input v-bind="$attrs" v-model="value" class="block mt-1 w-full border-gray-300 focus:border-sky-600 focus:ring-sky-600 rounded-md shadow-xs" x-ref="input-{{ $name }}" />
    <button
      v-if="close" v-show="value.length > 0" type="submit" class="absolute top-1 right-2 text-2xl text-gray-600 cursor-pointer" @click="value = '';"
    >
      &times;
    </button>
  </div>
</template>

<script setup>
defineProps({
	close: {
		type: Boolean,
		default: false,
	},
})

//don't add attributes, because they would be on the div, we wan them on the input (added with v-bind)
defineOptions({
	inheritAttrs: false,
})

//this allows v-model to work when coming in through the parent component
const value = defineModel({ type: String });
</script>