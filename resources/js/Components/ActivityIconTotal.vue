<template>
  <div class="flex flex-col items-center">
    <ActivityIcon :icon="icon" />
    <span class="font-bold text-lg text-center">{{ Math.round(total).toLocaleString() }} {{ desc }}</span>
  </div>
</template>

<script setup>
import ActivityIcon from './ActivityIcon.vue';

defineProps({
	icon: [String, Array],
	total: Number,
	desc: String,
})
</script>