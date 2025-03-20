<template>
  <SkiTouringIcon v-if="icon === 'SkiTouringIcon'" class="fill-sky-800 h-9" />
  <font-awesome-icon v-else class="text-sky-800 h-9" :icon="icon" />
</template>

<script setup>
import SkiTouringIcon from './SkiTouringIcon.vue';

defineProps({
	icon: [String, Array],
})
</script>