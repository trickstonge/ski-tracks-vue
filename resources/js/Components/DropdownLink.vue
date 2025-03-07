<template>
  <Link :href="route(routeName)" :method="method" class="block w-full px-4 py-2 text-start text-sm leading-5 text-sky-200 hover:text-sky-50 hover:bg-sky-900 focus:outline-hidden transition duration-150 ease-in-out'" v-html="label" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
	routeName: String,
	label: String,
	method: {
		type: String,
		default: 'get',
	},
})
</script>