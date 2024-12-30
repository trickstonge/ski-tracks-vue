<x-app-layout>
	<x-slot name="header">
		<h1 class="font-semibold text-xl text-gray-800 leading-tight">
			Upload Tracks
		</h1>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-0 md:px-6 px-8">
			<div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-none max-w-md m-auto">
				<div class="p-6 text-gray-900">

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

				</div>
			</div>
		</div>
	</div>
</x-app-layout>