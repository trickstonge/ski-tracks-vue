<template>
  <Head :title="pageTitle" />

  <Card class="max-w-xl m-auto">
    <p class="mb-8">Upload your .skiz files here. You can upload multiple files at once. These files can be big, so the upload process can take a few minutes if you upload many files at once. Let it be after clicking upload. I recommend not uploading much more than 200MB at a time. You may get a "gateway timeout" error after uploading. If this is the case, go back to the Tracks page, it's likely the upload worked despite the error.</p>

    <form @submit.prevent="form.post(route('track.store'))">
      <div>
        <InputLabel for="files" value="Select .skiz files" />
        <TextInput id="files" type="file" name="files[]" accept=".skiz" multiple class="file:bg-sky-800 file:rounded-md file:text-white file:px-2 file:py-0.5" @input="form.files = $event.target.files" />
        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
          {{ form.progress.percentage }}%
        </progress>
        <InputError :messages="[form.errors.files]" class="mt-2" />
      </div>
		
      <div class="flex justify-end mt-4">
        <PrimaryButton>
          Upload Files
        </PrimaryButton>
      </div>
    </form>
  </Card>
</template>

<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();

const pageTitle = page.props.pageTitle;

const form = useForm({
	files: null,
})
</script>