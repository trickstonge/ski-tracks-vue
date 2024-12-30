<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackRequest;
use App\Http\Requests\UpdateTrackRequest;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('track.index', [
            'tracks' => Track::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('track.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrackRequest $request)
    {
        //todo add gate
        
        $validated = $request->validated();

        $files = $request->file('files');
        foreach ($files as $file)
        {
            $jsonTrack = json_decode(file_get_contents($file), true);

            //adjust times to timezone
            $timezone = new \DateTimeZone($jsonTrack['tz']);
            $start = new \DateTime($jsonTrack['start']);
            $start->setTimezone($timezone);
            $finish = new \DateTime($jsonTrack['finish']);
            $finish->setTimezone($timezone);
            $jsonTrack['start'] = $start->format('Y-m-d H:i:s');
            $jsonTrack['finish'] = $finish->format('Y-m-d H:i:s');
            
            $jsonTrack['latitude'] = $jsonTrack['trackNodes'][0]['latitude'];
            $jsonTrack['longitude'] = $jsonTrack['trackNodes'][0]['longitude'];
            $jsonTrackMetrics = $jsonTrack['trackMetrics'];
            //change the keys of jsonTrackMetrics from camelCase to snake_case
            $dbMetrics = array();
            array_walk($jsonTrackMetrics, function($value, $key) use (&$dbMetrics)
                {
                    $key = strtolower(preg_replace('/([A-Z])/', '_$1', $key));
                    $dbMetrics[$key] = $value;
                }
            );

            //the line below defines a DocBlock. It fixes a false error from intelephense that tracks is undefined
        /** @var \App\Models\Track $user */
            $user = Auth::user();
            $track = $user->tracks()->create($jsonTrack);
            $track->metrics()->create($dbMetrics);
        }

        return redirect()->route('track.index')
            ->with('success', 'Tracks uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Track $track)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Track $track)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrackRequest $request, Track $track)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
        //
    }
}
