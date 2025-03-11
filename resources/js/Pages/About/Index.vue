<template>
  <Head :title="pageTitle" />

  <Card>
    <p class="mb-4">This website allows you to search and filter your ski days from the Ski Tracks mobile app. Ski tracks must first be exported from your phone, and then uploaded here. Instructions to do so are further down on this page.</p>

    <p v-if="!user" class="mb-4 font-semibold">To get started, <a :href="route('login')" class="underline">log in</a> or <a :href="route('register')" class="underline">register</a> for an account.</p>

    <p class="mb-4">This site is most useful if you've added a description to your ski days, as you can then search for specific words or phrases. The default in Ski Tracks is to add the name of the mountain in the description, so at the very least you can easily filter all your days at a specific mountain. Say you also add which ski you used for each day, that would also allow you to find out how many ski days you have on that ski.</p>
    <p class="mb-4">The filter type of "Days Since" is useful for searching for a description that has only been used once. Say you start using a new pair of boots, and in the description you've added "first day on X boots". Search for the name of the boots, use "Days Since" and choose the activity type, and you can see all your ski days since you've had those boots. When using this filter, the matched ski day will be highlighted with a blue background.</p> 

    <p class="mb-2">Other caveats:</p>
    <ul class="list-disc ml-8 mb-6">
      <li>Calculating the number of ski days and separating seasons relies on the fact that the name of the track in Ski Tracks has not been changed from the default. The format must be "Day 1 - 2024/2025".</li>
      <li>Only skiing, ski touring, and cross country activities are imported. Others will be ignored.</li>
      <li>With the ski touring activity, Ski Tracks doesn't count runs, but counts ascents and descents instead. This website doesn't count every descent as a run, otherwise a small grade reversal on the way up may count as one run. Only descents that are more than 50 vertical meters and near the max altitude reached will be counted. For this reason, the number of runs may not always be accurate.</li>
    </ul>
    <h2 class="font-semibold mb-4 text-lg">Importing Tracks</h2>
    <p class="mb-4">To import your ski tracks, you must first export them from the Ski Tracks app. Go to Settings, Tools, then Backup. Any previously backed up activity will not appear on this screen. Click the refresh icon on the top right of the screen to create the backup files. Once this step is done, it's time to move the files to your computer.</p>
    <p class="mb-4">The following is specific to Macs. I don't have access to a Windows computer, but there must be a way to transfer the .skiz files in Windows. <a href="https://support.apple.com/guide/itunes/transfer-files-itns32636/windows" class="underline">This guide from Apple may help.</a></p>
    <p class="mb-4">Connect your phone to your computer. In the Finder, go to your iPhone, and the Files tab. Open the Ski Tracks Folder, then copy the Backup folder to your computer. It doesn't seem possible to open this folder, so copying the entire folder is necessary. Once copied, upload the .skiz files in the backup folder to this website on the "Upload Tracks" page.</p>

    <p class="mb-4">Alternatively, if you only have a few tracks to export, they can be AirDropped individually. In the Ski Tracks app, go to the track you want to export, tap the share icon on the bottom right, and then AirDrop it to your computer as the SKIZ format. Repeat for other tracks, then upload the .skiz files to this website on the "Upload Tracks" page.</p>

    <h2 class="font-semibold mb-4 text-lg">Importing Tracks from your iPhone</h2>
    <p class="mb-4">It's also possible to upload the files directly from your iPhone. Create the backup files in Ski Tracks as explained above. On the Upload Tracks page, click Choose Files. On the screen that pops up, find "On My iPhone" and you should see a "Ski Tracks" folder. Click the Backup folder, and on the next screen you can select individual files to upload.</p>
  </Card>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Card from '@/Components/Card.vue';

const page = usePage();

const pageTitle = page.props.pageTitle;


const user = computed(
	() => page.props.user,
);

</script>
