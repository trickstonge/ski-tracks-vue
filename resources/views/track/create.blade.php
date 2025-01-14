<x-app-layout title="Upload Tracks">
	<x-slot name="header">
		Upload Tracks
	</x-slot>

	<x-card class="max-w-md m-auto">

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