<template>
  <Link :href="route(routeName)" class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium focus:outline-hidden focus:text-sky-900 focus:bg-sky-100 transition duration-150 ease-in-out" :class="[route().current() == routeName ? 'text-sky-50 bg-sky-900' : 'hover:text-sky-50 hover:bg-sky-900 text-sky-200']" v-html="label" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
	routeName: String,
	label: String,
})
</script>