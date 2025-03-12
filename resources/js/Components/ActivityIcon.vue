<template>
  <SkiTouringIcon v-if="icon === 'SkiTouringIcon'" class="fill-sky-800 h-9 sm:ml-auto" />
  <font-awesome-icon v-else class="text-sky-800 h-9 sm:ml-auto" :icon="icon" />
</template>

<script setup>
import SkiTouringIcon from './SkiTouringIcon.vue';

defineProps({
	icon: [String, Array],
})
</script>