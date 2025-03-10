<template>
  {{ tracks }}
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';

const pageTitle = 'Ski Tracks';
//usePage used on all templates to set page title/h1 at top
usePage().props.pageTitle = pageTitle;


defineProps({
	tracks: Object,
})


</script>