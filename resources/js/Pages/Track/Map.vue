<template>
  <Head :title="pageTitle" />

  <Card>
    <form class="flex mb-8 gap-5 md:block" @submit.prevent="form.post(route('track.map'))">
      <div class="grow md:mt-4">
        <InputLabel for="activity" value="Activity" />
        <Select v-model="form.activity" name="activity" :options="activities" all />
      </div>

      <div class="grow md:mt-4">
        <InputLabel for="season" value="Season" />
        <Select v-model="form.season" name="season" :options="seasons" all />
      </div>

      <div class="pt-7">
        <PrimaryButton>
          Filter
        </PrimaryButton>
      </div>
    </form>
		
    <GoogleMap
      v-if="tracks.length !== 0" ref="mapRef"
      api-key="AIzaSyA9x7cjOvUtJ9Tq8nUKJE5laIerQ4DhhXs"
      map-id="ab8c626e70aa41c5"
      style="width: 100%; height: 60vh"
      :center="{ lat: 40, lng: -100 }"
      :zoom="4"
    >
      <AdvancedMarker
        v-for="track in tracks" :key="track.id" 
        :options="{ position: { lat: parseFloat(track.latitude), lng: parseFloat(track.longitude) }, title: track.description }" 
        :pin-options="{ glyph: getIcon(track.activity), background: '#fff', borderColor: '#fff' }"
      >
        <InfoWindow>
          {{ track.name }}<br />
          {{ track.description }}
        </InfoWindow>
      </AdvancedMarker>
    </GoogleMap>
    <p v-else>No tracks match the selected filters.</p>
  </Card>
</template>

<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map';
import { ref, watch } from 'vue';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();

const pageTitle = page.props.pageTitle;

const props = defineProps({
	tracks: Object,
	seasons: Object,
	activities: Object,
})

const form = useForm({
	activity: '',
	season: '',

})


//reference and watcher for when map is ready, to reset bounds.
const mapRef = ref(null)
watch(() => mapRef.value?.ready, (ready) => {
	if (!ready) return
  
	mapBounds();
})

//watcher for changes in tracks, to reset bounds
watch(() => props.tracks, () => {
	mapBounds();
})

const mapBounds = () => {
	const bounds = new google.maps.LatLngBounds();
	for(let track of props.tracks) {
		bounds.extend({ lat: parseFloat(track.latitude), lng: parseFloat(track.longitude) });
	}
	mapRef.value?.map.fitBounds(bounds);
}

const getIcon = (activity) => { 
	const parser = new DOMParser();
	const svgs = {
		'skiing': '<svg class="text-sky-800 w-full h-full" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-skiing" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="" fill="currentColor" d="M380.7 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM2.7 268.9c6.1-11.8 20.6-16.3 32.4-10.2L232.7 361.3l46.2-69.2-75.1-75.1c-14.6-14.6-20.4-33.9-18.4-52.1l108.8 52 39.3 39.3c16.2 16.2 18.7 41.5 6 60.6L289.8 391l128.7 66.8c13.6 7.1 29.8 7.2 43.6 .3l15.2-7.6c11.9-5.9 26.3-1.1 32.2 10.7s1.1 26.3-10.7 32.2l-15.2 7.6c-27.5 13.7-59.9 13.5-87.2-.7L12.9 301.3C1.2 295.2-3.4 280.7 2.7 268.9zM118.9 65.6L137 74.2l8.7-17.4c4-7.9 13.6-11.1 21.5-7.2s11.1 13.6 7.2 21.5l-8.5 16.9 54.7 26.2c1.5-.7 3.1-1.4 4.7-2.1l83.4-33.4c34.2-13.7 72.8 4.2 84.5 39.2l17.1 51.2 52.1 26.1c15.8 7.9 22.2 27.1 14.3 42.9s-27.1 22.2-42.9 14.3l-58.1-29c-11.4-5.7-20-15.7-24.1-27.8l-5.8-17.3-27.3 12.1-6.8 3-6.7-3.2L151.5 116.7l-9.2 18.4c-4 7.9-13.6 11.1-21.5 7.2s-11.1-13.6-7.2-21.5l9-18-17.6-8.4c-8-3.8-11.3-13.4-7.5-21.3s13.4-11.3 21.3-7.5z"></path></svg>',
		'ski-touring': '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" class="fill-sky-800 w-full h-full"><path d="M 17.909 50.4 C 21.253 50.507 23.853 47.705 24.101 46.303 L 25.092 21.619 C 25.092 20.218 22.615 17.307 19.271 17.199 C 15.927 17.092 13.202 19.894 13.077 21.187 L 12.088 45.873 C 12.088 47.381 14.564 50.292 17.909 50.4"></path><path d="M 83.735 51.227 C 82.735 51.527 81.505 53.663 81.805 54.663 C 82.405 56.863 79.53 60.428 79.43 60.428 L 70.677 64.222 L 64.504 24.912 L 65.889 23.778 C 67.089 22.578 67.089 20.578 65.889 19.278 C 64.689 17.978 62.438 17.778 61.038 18.978 L 52.093 27.007 C 50.593 25.707 49.111 24.563 47.511 23.263 C 46.211 22.263 47.811 23.363 43.511 20.263 C 40.211 17.363 36.3 17.9 36.3 17.9 C 31.3 18.2 27.5 20.4 27.1 30 L 26.6 44.5 C 26.7 47.9 26.467 50.704 27.867 53.704 L 34.867 65.904 L 28.89 81.381 C 28.59 82.481 28.79 83.581 29.29 84.481 L 10.993 93.528 C 9.993 94.028 9.697 96.938 10.197 97.938 C 10.697 98.938 12.9 100.4 13.9 99.9 L 84 65.8 C 86.5 64.6 89.489 57.542 88.189 53.242 C 87.889 52.142 85.771 50.572 84.671 50.872 M 37.39 80.481 L 44.3 67.2 C 44.6 66.3 44.6 65.3 44.2 64.3 L 44.1 64.1 L 41.3 57 L 48.2 58.9 L 50.49 71.381 C 50.69 72.281 51.19 72.981 51.89 73.481 L 37.39 80.481 Z M 58.09 70.481 C 58.09 70.281 58.09 70.081 58.09 69.881 L 56.814 53.717 C 56.614 52.217 55.514 50.917 54.014 50.417 L 53.814 50.317 L 43.839 47.475 L 44.137 31.528 C 46.337 33.128 47.7 33.9 49.7 35.3 L 49.9 35.4 C 51.3 36.6 53.5 36.5 54.8 35.1 L 61.099 28.284 L 66.657 66.45 L 58.09 70.481 Z"></path><path d="M 39.638 16.078 C 43.967 14.532 46.131 9.791 44.585 5.462 C 43.039 1.133 38.195 -1.031 33.969 0.515 C 29.64 2.061 27.476 6.802 29.022 11.131 C 30.568 15.46 35.309 17.624 39.638 16.078"></path></svg>',
		'x-country': '<svg class="text-sky-800 w-full h-full" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-skiing-nordic" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path class="" fill="currentColor" d="M336 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM227.2 160c1.9 0 3.8 .1 5.6 .3L201.6 254c-9.3 28 1.7 58.8 26.8 74.5l86.2 53.9L291.3 464l-88.5 0 41.1-88.1-32.4-20.3c-7.8-4.9-14.7-10.7-20.6-17.3L132.2 464l-32.4 0 54.2-257.6c4.6-1.5 9-4.1 12.7-7.8l23.1-23.1c9.9-9.9 23.4-15.5 37.5-15.5zM121.4 198.6c.4 .4 .8 .8 1.3 1.2L67 464l-43 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l135.3 0c.5 0 .9 0 1.4 0l158.6 0c.5 0 1 0 1.4 0L504 512c39.8 0 72-32.2 72-72l0-8c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 8c0 13.3-10.7 24-24 24l-69.4 0 27.6-179.3c10.5-5.2 17.8-16.1 17.8-28.7c0-17.7-14.3-32-32-32l-21.3 0c-12.9 0-24.6-7.8-29.5-19.7l-6.3-15c-14.6-35.1-44.1-61.9-80.5-73.1l-48.7-15c-11.1-3.4-22.7-5.2-34.4-5.2c-31 0-60.8 12.3-82.7 34.3l-23.1 23.1c-12.5 12.5-12.5 32.8 0 45.3zm308 89.4L402.3 464l-44.4 0 21.6-75.6c5.9-20.6-2.6-42.6-20.7-53.9L302 299l30.9-82.4 5.1 12.3C353 264.7 387.9 288 426.7 288l2.7 0z"></path></svg>',
	};
	return parser.parseFromString(svgs[activity], 'image/svg+xml').documentElement;
}
</script>