import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';
import ClickOutside from '@/Directives/ClickOutside';
import MainLayout from '@/Layouts/MainLayout.vue'

import { library, config } from '@fortawesome/fontawesome-svg-core';
import { faSnowflake, faClock } from '@fortawesome/free-regular-svg-icons';
import { faPersonSkiing, faSkiingNordic, faArrowDownLong, faArrowRightLong, faCalendarDays, faMountainSun, faArrowTrendDown } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faSnowflake, faPersonSkiing, faSkiingNordic, faArrowDownLong, faArrowRightLong, faCalendarDays, faMountainSun, faArrowTrendDown, faClock);
config.autoAddCss = false;

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
				.directive('click-outside', ClickOutside)
				.mount(el)
		},
	})
}