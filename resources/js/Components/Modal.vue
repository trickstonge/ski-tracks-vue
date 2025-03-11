<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center bg-gray-500/75">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg">
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
	show: Boolean,
});
</script>