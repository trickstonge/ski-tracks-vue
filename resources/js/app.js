// import './bootstrap';

// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';
import MainLayout from '@/Layouts/MainLayout.vue'

import { library } from '@fortawesome/fontawesome-svg-core';
import { faSnowflake } from '@fortawesome/free-regular-svg-icons';
import { faPersonSkiing, faSkiingNordic } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faSnowflake, faPersonSkiing, faSkiingNordic);

if (document.getElementById('app')) {
	createInertiaApp({
		title: title => title !== '' ? `${title} - Ski Tracks` : 'Ski Tracks',
		resolve: name => {
			const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
			const page = pages[`./Pages/${name}.vue`]
			//set default layout
			page.default.layout = MainLayout
			return page
		},
		setup({ el, App, props, plugin }) {
			createApp({ render: () => h(App, props) })
				.component('font-awesome-icon', FontAwesomeIcon)
				.use(plugin)
				.use(ZiggyVue)
				.mount(el)
		},
	})
}