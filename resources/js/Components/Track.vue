<template>
  <div>
    <div class="grid grid-cols-12 gap-x-3 items-center text-lg">
      <ActivityIcon :icon="icons[track.activity]" />
      
      <div class="col-span-3 md:col-span-4 sm:col-span-6 sm:order-first">
        <!-- if we're on the track.show view, don't print the link -->
        <p v-if="route().current() == 'track.show' ">{{ track.name }}</p>
        <Link v-else :href="route('track.show', track.id)" class="underline">{{ track.name }}</Link>
      </div>
      <div class="col-span-5 md:col-span-7 sm:col-span-12">{{ track.description }}</div>
      <div class="col-span-2 md:col-span-6">{{ new Date(track.start).toLocaleString('en-US', dateOptions) }}</div>
      <div class="col-span-1 md:col-span-6 text-right">{{ durationFormatted }}</div>
    </div>
    <div v-if="track.activity == 'x-country'" class="grid grid-cols-12 gap-x-3 items-center mt-4 text-gray-600 sm:block">
      <div class="col-span-1 lg:hidden" />
      <div class="col-span-3 lg:col-span-6">Avg Speed: {{ track.metrics.average_speed }} {{ user.units.speed }}</div>
      <div class="col-span-3 lg:col-span-6">Max Speed: {{ track.metrics.max_speed }} {{ user.units.speed }}</div>
      <div class="col-span-2 lg:col-span-6">Vertical: {{ track.metrics.total_descent }} {{ user.units.vertical }}</div>
      <div class="col-span-3 lg:col-span-6">Distance: {{ track.metrics.distance }} {{ user.units.distance }}</div>
    </div>
    <div v-else class="grid grid-cols-12 gap-x-3 items-center mt-4 text-gray-600 sm:block">
      <div class="col-span-1 lg:col-span-6">{{ track.metrics.descents }} {{ track.metrics.descents === 1 ? 'Run' : 'Runs' }} </div>
      <div class="col-span-3 lg:col-span-6">Avg Descent Speed: {{ track.metrics.average_descent_speed }} {{ user.units.speed }}</div>
      <div class="col-span-3 lg:col-span-6">Max Speed: {{ track.metrics.max_speed }} {{ user.units.speed }}</div>
      <div class="col-span-2 lg:col-span-6">Vertical: {{ track.metrics.total_descent }} {{ user.units.vertical }}</div>
      <div class="col-span-3 lg:col-span-6">Ski Distance: {{ track.metrics.descent_distance }} {{ user.units.distance }}</div>
    </div>

    <slot />
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ActivityIcon from './ActivityIcon.vue';

const dateOptions = { timeZone: 'UTC', dateStyle: 'short', timeStyle: 'short' };

const props = defineProps({
	track: Object,
})

const user = usePage().props.user;


const icons = {
	'ski-touring': 'SkiTouringIcon',
	'skiing': 'person-skiing',
	'x-country': 'skiing-nordic',
};

const durationFormatted = computed(() => {
	const duration = props.track.duration;
	if (duration > 3600) {
		const hours = Math.floor(duration / 3600);
		const minutes = Math.floor((duration % 3600) / 60);
		return `${hours}:${minutes.toString().padStart(2, '0')}`;
	} else {
		const minutes = Math.floor(duration / 60);
		return `${minutes}`;
	}
});
</script>