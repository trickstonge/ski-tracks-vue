import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

export function useLinkActive(routeName) {
	const page = usePage();

	const user = computed(() => page.props.user);

	//use inertia Link for nav when verified/logged in, normal anchor when not since auth pages still use blade templates.
	const linkTag = computed(() => (user.value?.verified ? Link : 'a'));

	const isActive = ref(route().current() == routeName);
	//watcher to make current route reactive, so correct nav item can be highlited when changing page
	watch(() => page.props.nav, () => {
		isActive.value = route().current() == routeName;
	});

	return {
		linkTag,
		isActive,
	};
}