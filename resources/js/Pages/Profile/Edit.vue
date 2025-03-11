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
            <TextInput id="email" v-model="profileForm.email" name="email" type="text" required autocomplete="email" />
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
      <section>
        <header>
          <h1 class="text-lg font-medium text-gray-800">
            Update Password
          </h1>

          <p class="mt-1 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
          </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="passwordForm.put(route('password.update'))">
          <div>
            <InputLabel for="update_password_current_password" value="Current Password" />
            <TextInput id="update_password_current_password" v-model="passwordForm.current_password" name="current_password" type="password" autocomplete="current-password" />
            <InputError class="mt-2" :messages="[passwordForm.errors.updatePassword?.current_password]" />
          </div>

          <div>
            <InputLabel for="update_password_password" value="New Password" />
            <TextInput id="update_password_password" v-model="passwordForm.password" name="password" type="password" autocomplete="new-password" />
            <InputError class="mt-2" :messages="[passwordForm.errors.updatePassword?.password]" />
          </div>

          <div>
            <InputLabel for="update_password_password_confirmation" value="Confirm Password" />
            <TextInput id="update_password_password_confirmation" v-model="passwordForm.password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <InputError class="mt-2" :messages="[passwordForm.errors.updatePassword?.password_confirmation]" />
          </div>

          <div class="flex items-center gap-4">
            <PrimaryButton>Save</PrimaryButton>
          </div>
        </form>
      </section>
    </div>
  </div>

  <div class="p-8 sm:p-4 bg-white shadow-sm rounded-lg sm:rounded-none">
    <div class="max-w-xl">
      <section class="space-y-6">
        <header>
          <h1 class="text-lg font-medium text-gray-800">
            Delete Account
          </h1>

          <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
          </p>
        </header>

        <DangerButton @click="showModal = true">
          Delete Account
        </DangerButton>

        <Modal :show="showModal" @close="showModal = false">
          <form class="p-6" @submit.prevent="deleteForm.delete(route('profile.destroy'))">
            <h2 class="text-lg font-medium text-gray-800">
              Are you sure you want to delete your account?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
              Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mt-6">
              <InputLabel for="password" value="Password" class="sr-only" />
              <TextInput id="password" v-model="deleteForm.password" name="password" type="password" placeholder="Password" />
              <InputError class="mt-2" :messages="[deleteForm.errors.userDeletion?.password]" />
            </div>

            <div class="mt-6 flex justify-end">
              <SecondaryButton @click="showModal = false">
                Cancel
              </SecondaryButton>

              <DangerButton class="ms-3">
                Delete Account
              </DangerButton>
            </div>
          </form>
        </Modal>
      </section>
    </div>
  </div>
</template>

<script setup>
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';

const page = usePage();

const pageTitle = page.props.pageTitle;


const props = defineProps({
	user: Object,
	mustVerifyEmail: Boolean,
	status: String,
});

const profileForm = useForm({
	name: props.user.name,
	email: props.user.email,
	imperial: props.user.imperial,
});

const passwordForm = useForm({
	current_password: '',
	password: '',
	password_confirmation: '',
});

const deleteForm = useForm({
	password: '',
});

const showModal = ref(false);
</script>
