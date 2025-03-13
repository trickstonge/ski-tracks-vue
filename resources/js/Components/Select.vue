<template>
  <select :id="name" v-model="value" class="block mt-1 w-full border-gray-300 focus:border-sky-600 focus:ring-sky-600 disabled:text-gray-400 rounded-md shadow-xs" :name="name">
    <option v-if="all" value="">All</option>

    <option v-for="(option, key) in options" :key="key" :value="key">{{ option }}</option>
  </select>
</template>

<script setup>
defineProps({
	name: String,
	options: Object,
	all: Boolean,
})

//this allows v-model to work when coming in through the parent component
const value = defineModel({ type: String });
</script>