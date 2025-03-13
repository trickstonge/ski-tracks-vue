<template>
  <Card class="mb-8">
    <form class="flex gap-5 md:block" @submit.prevent="filter">
      <div class="grow">
        <InputLabel for="description" value="Description Search" />
        <TextInput id="description" v-model="form.description" name="description" type="text" close />
      </div>

      <div class="grow md:mt-4">
        <InputLabel for="filterType" value="Filter Type" />
        <Select v-model="form.filterType" name="filterType" :options="{'normal': 'Normal', 'since': 'Days Since'}" :disabled="form.description.length === 0" />
      </div>
            
      <div class="grow md:mt-4">
        <InputLabel for="activity" value="Activity" />
        <Select v-model="form.activity" name="activity" :options="activities" all />
        <InputError class="mt-2" :messages="[form.errors.activity]" />
      </div>

      <div class="pt-7">
        <PrimaryButton>
          Filter
        </PrimaryButton>
      </div>
    </form>
  </Card>

  <Card v-if="totals" class="mb-8">
    <h2 class="text-2xl text-gray-800 font-semibold">All Seasons</h2>
    <Totals :totals="totals" class="mt-6" />
    <h3 class="text-xl text-gray-800 mt-6 sm:text-center">Jump to Season</h3>
    <div class="flex flex-wrap gap-y-2 sm:text-center">
      <a v-for="(seasonData, season) in tracks" :key="season" :href="'#' + season.replace('/', '-')" class="underline w-1/6 md:w-1/4 sm:w-1/2">{{ season }}</a>
    </div>
  </Card>


  <Card v-for="(seasonData, season) in tracks" :id="season.replace('/', '-')" :key="season" class="mb-8">
    <h2 class="text-2xl text-gray-800 font-semibold">{{ season }}</h2>
    
    <Totals :totals="seasonData.totals" class="my-6" />
    
    <Track v-for="track in seasonData.tracks" :key="track.id" :track="track" class="py-4 border-t border-sky-500" :class="{'bg-sky-100': track.id === firstTrackID}" />
  </Card>

  <Card v-if="tracks.length === 0">
    <p v-if="noTracks">You haven't uploaded any tracks yet! <Link :href="route('track.create')" class="underline">Upload some now</Link>.</p>
    <p v-else>No tracks match the selected filters.</p>
  </Card>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Track from '@/Components/Track.vue';
import Totals from '@/Components/Totals.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/Components/Select.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


defineProps({
	tracks: Object,
	totals: Object,
	firstTrackID: Number,
	noTracks: Boolean,
	activities: Object,
})

const form = useForm({
	description: '',
	filterType: 'normal',
	activity: '',
})

const filter = () => {
	if(!form.activity && form.filterType === 'since')
	{ form.setError('activity', 'The activity field is required when filter type is since.'); }
	else
	{ form.post(route('track.index')); }
}
</script>