<template>
  <label class="block font-medium text-gray-700">
    {{ value }}
  </label>
</template>

<script setup>
defineProps({
	value: String,
})
</script>