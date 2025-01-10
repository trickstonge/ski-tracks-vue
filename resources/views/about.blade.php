<x-app-layout title="About">
    <x-slot name="header">
        About
    </x-slot>

    <x-card>
        <p class="mb-4">This website allows you search and filter your ski days from the Ski Tracks mobile app. Ski tracks must first be exported from your phone, and then uploaded here.</p>
        <p class="mb-4">This site is most useful if you've added a description to your ski days, as you can then search for specific words or phrases. The default in Ski Tracks is to add the name of the mountain in the description, so at the very least you can easily filter all your days at a specific mountain. Say you also add which ski you used for each day, that would also allow you to find out how many ski days you have on that ski.</p>
        <p class="mb-4">The filter type of "Days Since" is useful for searching for a description that has only been used once. Say you start using a new pair of boots, and in the description you've added "first day on X boots". Search for the name of the boots, use "Days Since" and choose the activity type, and you can see all your ski days since you've had those boots. When using this filter, the matched ski day will be highlighted with a blue background.</p> 

        <p class="mb-2">Other caveats:</p>
        <ul class="list-disc ml-8">
            <li>Calculating the number of ski days and separating seasons relies on the fact that the name of the track in Ski Tracks has not been changed from the default. The format must be "Day 1 - 2024/2025".</li>
            <li>Only skiing, ski touring, and cross country activities are imported. Others will be ignored.</li>
            <li>With the ski touring activity, Ski Tracks doesn't count runs, but counts ascents and descents instead. This website doesn't count every descent as a run, otherwise a small grade reversal on the way up may count as one run. Only descents that are more than 50 vertical meters and near the max altitude reached will be counted. For this reason, the number of runs may not always be accurate.</li>
    </x-card>
</x-app-layout>
