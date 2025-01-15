<x-app-layout title="Upload Tracks">
	<x-slot name="header">
		Upload Tracks
	</x-slot>

	<x-card class="max-w-xl m-auto">

		<p class="mb-8">Upload your .skiz files here. You can upload multiple files at once. These files can be big, so the upload process can take a few minutes if you upload many files at once. Let it be after clicking upload. I recommend not uploading much more than 200MB at a time. You may get a "gateway timeout" error after uploading. If this is the case, go back to the Tracks page, it's likely the upload worked despite the error.</p>

		<form method="POST" action="{{ route('track.store') }}" enctype="multipart/form-data">
			@csrf
		
			<div>
				<x-input-label for="files" value="Select .skiz files" />
				<x-text-input id="files" type="file" name="files[]" accept=".skiz" autofocus multiple  />
				<x-input-error :messages="$errors->get('files')" class="mt-2" />
			</div>
		
			<div class="flex justify-end mt-4">
				<x-primary-button>
					Upload Files
				</x-primary-button>
			</div>
		</form>

	</x-card>
</x-app-layout>