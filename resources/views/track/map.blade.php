<x-app-layout title="Map">
	<x-slot name="header">
		Map
	</x-slot>

	<script>
		(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
		  key: "{{ config('app.google_api_key')}} ",
		  v: "beta",
		});

		//converts json to js object
		let tracks = {{ Js::from($tracks) }};
		let map;

		async function initMap() {
			const { Map, InfoWindow } = await google.maps.importLibrary("maps");
			const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
			const bounds = new google.maps.LatLngBounds();
			const parser = new DOMParser();
			const infoWindow = new InfoWindow();

			map = new Map(document.getElementById("map"), {
				center: { lat: 40, lng: -100 },
				zoom: 4,
				mapId: 'ab8c626e70aa41c5'
			});

			tracks.forEach(track => {
				//define svg icons.
				const skiingSvg = parser.parseFromString(
				`<x-fas-person-skiing class="fill-sky-800 w-full h-full" />`,
				"image/svg+xml",
				).documentElement;
				const touringSvg = parser.parseFromString(
				`<x-ski-touring-icon class="fill-sky-800 w-full h-full" />`,
				"image/svg+xml",
				).documentElement;
				const xcSvg = parser.parseFromString(
				`<x-fas-skiing-nordic class="fill-sky-800 w-full h-full" />`,
				"image/svg+xml",
				).documentElement;
				
				//create new pin element with the icon
				const iconElement = new PinElement({
					//set icon based on track.activity
					glyph: track.activity === 'skiing' ? skiingSvg : track.activity === 'ski-touring' ? touringSvg : xcSvg,
					background: '#fff',
					borderColor: '#fff',
				});

				//add the marker to the map
				const marker = new AdvancedMarkerElement({
					map,
					position: { lat: parseFloat(track.latitude), lng: parseFloat(track.longitude) },
					title: track.description,
					content: iconElement.element,
				});

				//save the marker to bounds for fitBounds
				bounds.extend(marker.position);

				//event listener for marker info window
				marker.addListener("gmp-click", () => {
					infoWindow.setContent(track.name + ",<br>" + track.description);
					infoWindow.open(map, marker);
				});
				
			});
    		map.fitBounds(bounds);
		}

		if (tracks.length)
		{ initMap(); }

	</script>

	<x-card>
		<form method="POST" action="{{ route('track.map') }}" class="flex mb-8 gap-5 md:block">
			@csrf
			<div class="grow md:mt-4">
				<x-input-label for="activity" value="Activity" />
				<x-select name="activity" :options="App\Models\Track::$activities" all />
			</div>

			<div class="grow md:mt-4">
				<x-input-label for="season" value="Season" />
				<x-select name="season" :options="$seasons" all />
			</div>

			<div class="pt-7">
				<x-primary-button>
					Filter
				</x-primary-button>
			</div>
		</form>
		@if($tracks->isNotEmpty())
			<div id="map" style="height: 60vh"></div>
		@else
			<p>No tracks match the selected filters.</p>
		@endif

	</x-card>
</x-app-layout>