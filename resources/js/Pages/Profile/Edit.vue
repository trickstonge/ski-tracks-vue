<template>
  <Head :title="pageTitle" />

  <div class="p-8 mb-8 sm:p-4 bg-white shadow-sm rounded-lg sm:rounded-none">
    <div class="max-w-xl">
      <section>
        <header>
          <h1 class="text-lg font-medium text-gray-800">
            Profile Information
          </h1>

          <p class="mt-1 text-sm text-gray-600">
            Update your account's profile information and email address.
          </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="profileForm.patch(route('profile.update'))">
          <div>
            <InputLabel for="name" value="Name" />
            <TextInput id="name" v-model="profileForm.name" name="name" type="text" required autofocus autocomplete="name" />
            <InputError class="mt-2" :messages="[profileForm.errors.name]" />
          </div>

          <div>
            <InputLabel for="email" value="Email" />
            <TextInput id="email" v-model="profileForm.email" name="email" type="text" required autofocus autocomplete="email" />
            <InputError class="mt-2" :messages="[profileForm.errors.email]" />
            <div v-if="mustVerifyEmail && !user.email_verified_at">
              <p class="text-sm mt-2 text-gray-800">
                Your email address is unverified.

                <Link
                  :href="route('verification.send')"
                  method="post"
                  as="button"
                  class="underline text-sm text-gray-600 hover:text-gray-800 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-sky-600"
                >
                  Click here to re-send the verification email.
                </Link>
              </p>

              <p v-if="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                A new verification link has been sent to your email address.
              </p>
            </div>
          </div>

          <div>
            <InputLabel for="imperial" value="Default Units" class="mb-1" />
            <input id="unit_metric" v-model="profileForm.imperial" type="radio" name="imperial" value="0" /> <label for="unit_metric">Metric</label>
            <input id="unit_imperial" v-model="profileForm.imperial" type="radio" name="imperial" value="1" class="ml-4" /> <label for="unit_imperial">Imperial</label>
          </div>

          <div class="flex items-center gap-4">
            <PrimaryButton>Save</PrimaryButton>
          </div>
        </form>
      </section>
    </div>
  </div>

  <div class="p-8 mb-8 sm:p-4 bg-white shadow-sm rounded-lg sm:rounded-none">
    <div class="max-w-xl">
      @include('profile.partials.update-password-form')
    </div>
  </div>

  <div class="p-8 sm:p-4 bg-white shadow-sm rounded-lg sm:rounded-none">
    <div class="max-w-xl">
      @include('profile.partials.delete-user-form')
    </div>
  </div>
</template>

<script setup>
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();

const pageTitle = page.props.pageTitle;


const props = defineProps({
	user: Object,
	mustVerifyEmail: Boolean,
	status: String,
})

const profileForm = useForm({
	name: props.user.name,
	email: props.user.email,
	imperial: props.user.imperial,
})
</script>
