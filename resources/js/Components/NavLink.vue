<template>
  <!-- component :is allows dynamically named components. Inertia Link or normal anchor in this case. -->
  <component :is="linkTag" :href="route(routeName)" class="inline-flex items-center px-1 pt-1 border-b-4 text-base font-medium leading-5 hover:text-sky-50 hover:border-sky-200 focus:outline-hidden transition duration-150 ease-in-out" :class="[isActive ? 'border-sky-200 text-sky-50' : 'border-transparent text-sky-200']">{{ label }}</component>
</template>

<script setup>
import { useLinkActive } from '@/Composables/useLinkActive';

const props = defineProps({
	routeName: String,
	label: String,
})

const { linkTag, isActive } = useLinkActive(props.routeName);
</script>
