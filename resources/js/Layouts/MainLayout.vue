<template>
  <!-- Nav items are in HandleInertiaRequests.php, so they're loaded on every view -->

  <nav class="bg-sky-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-8 md:px-6 sm:px-4">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="shrink-0 flex items-center">
            <Link :href="route('track.index')">
              <font-awesome-icon class="block h-9 w-auto fill-current text-sky-100" :icon="['far', 'snowflake']" />
            </Link>
          </div>
          <!-- Navigation Links -->
          <div class="sm:hidden space-x-8 ms-10 flex">
            <NavLink v-for="(routeName, label) in nav" :key="routeName" :route-name="routeName" :label="label" />
          </div>
        </div>

        <div v-click-outside="closeDropdown" class="flex items-center ms-6">
          <!-- Settings Dropdown -->
          <div v-if="user" class="relative sm:hidden">
            <div @click="open = !open">
              <button class="inline-flex items-center px-3 pt-1 border-b-4 border-transparent text-base leading-4 font-medium text-sky-200 hover:text-sky-50 focus:outline-none transition ease-in-out duration-150">
                <div>{{ user.name }}</div>

                <div class="ms-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </div>

            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="opacity-0 scale-95"
              enter-to-class="opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="opacity-100 scale-100"
              leave-to-class="opacity-0 scale-95"
            >
              <div v-show="open" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg ltr:origin-top-right rtl:origin-top-left end-0">
                <div class="rounded-md bg-sky-800 py-1">
                  <DropdownLink route-name="profile.edit" label="Profile" />
                  <DropdownLink route-name="logout" label="Logout" method="post" />
                </div>
              </div>
            </transition>
          </div>

          <!-- Hamburger -->
          <div class="-me-2 hidden items-center sm:flex">
            <button class="inline-flex items-center cursor-pointer justify-center p-2 rounded-md text-sky-100 hover:text-sky-800 hover:bg-sky-100 focus:outline-none focus:bg-sky-100 focus:text-sky-800 transition duration-150 ease-in-out" @click="open = !open">
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path v-show="!open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-show="open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div v-show="open" class="hidden sm:block">
      <div class="pt-2 pb-3 space-y-1">
        <ResponsiveNavLink v-for="(routeName, label) in nav" :key="routeName" :route-name="routeName" :label="label" />
      </div>

      <!-- Responsive Settings Options -->
      <div v-if="user" class="pt-4 pb-1 border-t border-sky-200">
        <div class="px-4">
          <div class="font-medium text-base text-sky-50">{{ user.name }}</div>
          <div class="font-medium text-sm text-sky-200">{{ user.email }}</div>
        </div>

        <div class="mt-3 space-y-1">
          <ResponsiveNavLink route-name="profile.edit" label="Profile" />
          <ResponsiveNavLink route-name="logout" label="Logout" method="post" />
        </div>
      </div>
    </div>
  </nav>

  <!-- Page Heading -->
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-8 md:px-6 sm:px-4">
      <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $page.props.pageTitle }}
      </h1>
    </div>
  </header>

  <!-- Page Content -->
  <main>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-0 md:px-6 px-8">
        <div class="font-medium text-green-700 mb-2">{{ $page.props.success }}</div>
        <slot />
      </div>
    </div>
  </main>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import NavLink from '@/Components/NavLink.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

//trying props for nav to see if it works differently than usePage
defineProps({
	nav: Object,
})

const page = usePage();
//computed automatically updates when the dependencies change
//flash message defined in HandleInertiaRequests.php for props that are shared. usePage above allows reading from that file.
// const success = computed(
// 	() => page.props.success,
// )

//usePage also allows to read the user data
const user = computed(
	() => page.props.user,
);

//usePage for page title/h1 at top
// const pageTitle = computed(
// 	() => page.props.pageTitle,
// );


//state to toggle dropdown visibility
const open = ref(false);
//method to close the dropdown
const closeDropdown = () => {
	open.value = false;
};

</script>