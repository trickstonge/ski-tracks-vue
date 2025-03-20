<template>
  <!-- component :is allows dynamically named components. Inertia Link or normal anchor in this case. -->
  <component :is="linkTag" :href="route(routeName)" class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium focus:outline-hidden focus:text-sky-900 focus:bg-sky-100 transition duration-150 ease-in-out" :class="[isActive ? 'text-sky-50 bg-sky-900' : 'hover:text-sky-50 hover:bg-sky-900 text-sky-200']">{{ label }}</component>
</template>

<script setup>
import { useLinkActive } from '@/Composables/useLinkActive';

const props = defineProps({
	routeName: String,
	label: String,
})

const { linkTag, isActive } = useLinkActive(props.routeName);
</script>