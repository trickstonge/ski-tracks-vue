<x-app-layout :title="$track->name">
	<x-slot name="header">
		{{ $track->name }}
	</x-slot>

	<x-card>
		<x-track :$track>
			<div class="mt-4 text-right">
				<form method="post" action="{{ route('track.destroy', $track) }}">
					@csrf
					@method('delete')
					<x-danger-button>
						Delete Track
					</x-danger-button>
				</form>
			</div>
		</x-track>
	</x-card>
</x-app-layout>