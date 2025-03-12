<template>
  <Head :title="track.name" />

  <Card>
    <Track :track="track">
      <div class="mt-4 text-right">
        <form @submit.prevent="form.delete(route('track.destroy', { track: track.id }))">
          <DangerButton>
            Delete Track
          </DangerButton>
        </form>
      </div>
    </Track>
  </Card>
</template>

<script setup>
import Card from '@/Components/Card.vue';
import Track from '@/Components/Track.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { useForm, Head } from '@inertiajs/vue3';

defineProps({
	track: Object,
})

const form = useForm()
</script>