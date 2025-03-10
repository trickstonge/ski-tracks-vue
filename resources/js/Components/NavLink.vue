<template>
  <!-- component :is allows dynamically named components. Inertia Link or normal anchor in this case. -->
  <component :is="linkTag" :href="route(routeName)" class="inline-flex items-center px-1 pt-1 border-b-4 text-base font-medium leading-5 hover:text-sky-50 hover:border-sky-200 focus:outline-hidden transition duration-150 ease-in-out" :class="[isActive ? 'border-sky-200 text-sky-50' : 'border-transparent text-sky-200']">{{ label }}</component>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
	routeName: String,
	label: String,
})

const page = usePage();

const user = computed(
	() => page.props.user,
);

//use inertia Link for nav when logged in, normal anchor when not since auth pages still used blade templates.
const linkTag = user.value ? Link : 'a';

const isActive = ref(route().current() == props.routeName);
//watcher to make current route reactive, so correct nav item can be highhlited when changing page
watch(() => page.props.nav, () => {
	isActive.value = route().current() == props.routeName;
});

</script>