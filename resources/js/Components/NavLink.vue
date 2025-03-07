<template>
  <Link :href="route(routeName)" class="inline-flex items-center px-1 pt-1 border-b-4 text-base font-medium leading-5 hover:text-sky-50 hover:border-sky-200 focus:outline-hidden transition duration-150 ease-in-out" :class="[route().current() == routeName ? 'border-sky-200 text-sky-50' : 'border-transparent text-sky-200']" v-html="label" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
	routeName: String,
	label: String,
})
</script>