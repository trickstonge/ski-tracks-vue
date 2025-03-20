<template>
  <div class="grid lg:grid-cols-9 gap-x-3 gap-y-4 md:grid-cols-3 grid-cols-2">
    <ActivityIconTotal :total="totals.days" :desc="dayPlural(totals.days)" icon="calendar-days" />
    <ActivityIconTotal :total="totals.activities.total" :desc="totals.activities.total === 1 ? 'Activity' : 'Activities'" icon="mountain-sun" />
    <ActivityIconTotal :total="totals.activities.skiing" :desc="dayPlural(totals.activities.skiing)" icon="person-skiing" />
    <ActivityIconTotal :total="totals.activities['ski-touring']" :desc="dayPlural(totals.activities['ski-touring'])" icon="SkiTouringIcon" />
    <ActivityIconTotal :total="totals.activities['x-country']" :desc="dayPlural(totals.activities['x-country'])" icon="skiing-nordic" />
    <ActivityIconTotal :total="totals.runs" :desc="totals.runs === 1 ? 'Run' : 'Runs'" icon="arrow-trend-down" />
    <ActivityIconTotal :total="totals.descent" :desc="user.units.vertical" icon="arrow-down-long" />
    <ActivityIconTotal :total="totals.distance" :desc="user.units.distance" icon="arrow-right-long" />
    <ActivityIconTotal :total="totals.time" desc="Hours" :icon="['far', 'clock']" class="md:col-span-1 col-span-2" />
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ActivityIconTotal from './ActivityIconTotal.vue';

defineProps({
	totals: Object,
})

const user = usePage().props.user;

const dayPlural = (value) => {
	return value === 1 ? 'Day' : 'Days'
}
</script>