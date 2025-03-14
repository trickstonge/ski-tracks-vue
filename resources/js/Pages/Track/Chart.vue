<template>
  <Head :title="pageTitle" />

  <Card>
    <Line class="h-[70vh]" :data="chartData" :options="chartOptions" />
  </Card>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';

const page = usePage();

const pageTitle = page.props.pageTitle;

const props = defineProps({
	tracks: Object,
})

import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Colors } from 'chart.js'

ChartJS.register(Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Colors)

//get list of first and last dates for each season
const firstDates = Object.keys(props.tracks).map((season) => props.tracks[season][0].date);
const lastDates = Object.keys(props.tracks).map((season) => props.tracks[season][props.tracks[season].length - 1].date);
//Get earliest and latest dates. Hard coding leap year.
const earliest = new Date(Math.min(...firstDates.map((date) => new Date(`2023/${date}`))));
const latest = new Date(Math.max(...lastDates.map((date) => new Date(`2024/${date}`))));
const xLabels = [];
//make labels for all dates, with earliest and latest found
for (let d = new Date(earliest); d <= latest; d.setDate(d.getDate() + 1)) {
	const month = (d.getMonth() + 1).toString();
	const day = d.getDate().toString();
	xLabels.push(`${month}/${day}`);
}

//create datasets for each season
const datasets = Object.keys(props.tracks).map((season) => {
	//for each label
	const data = xLabels.map((date) => {
		//see if this date has an entry
		const track = props.tracks[season].find(track => track.date === date);
		return track ? track.name : null;
	});

	return {
		label: season,
		data: data,
	};
});
		
const chartData = {
	labels: xLabels,
	datasets: datasets,
};

const chartOptions = {
	spanGaps: true,
	maintainAspectRatio: false,
	scales: {
		x: {
			title: {
				display: true,
				text: 'Date',
			},
		},
		y: {
			title: {
				display: true,
				text: 'Day #',
			},
		},
	},
}
</script>