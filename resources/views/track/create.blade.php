<x-app-layout title="Upload Tracks">
	<x-slot name="header">
		<h1 class="font-semibold text-xl text-gray-800 leading-tight">
			Upload Tracks
		</h1>
	</x-slot>

	<x-card class="max-w-md m-auto">

		<form method="POST" action="{{ route('track.store') }}" enctype="multipart/form-data">
			@csrf
		
			<div>
				<x-input-label for="files" value="Files" />
				<x-text-input id="files" class="block mt-1 w-full" type="file" name="files[]" accept="application/json" autofocus multiple  />
				<x-input-error :messages="$errors->get('files')" class="mt-2" />
			</div>
		
			<div class="flex items-center justify-end mt-4">
				<x-primary-button class="ms-3">
					Upload Files
				</x-primary-button>
			</div>
		</form>

	</x-card>
</x-app-layout>