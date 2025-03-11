<template>
  <ul class="text-sm text-red-600 space-y-1">
    <li v-for="message in messages" :key="message">{{ message }}</li>
  </ul>
</template>

<script setup>
defineProps({
	messages: Array,
})
</script>