<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackRequest;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::guest())
        { return view('track.index'); }
        //the line below defines a DocBlock. It fixes a false error from intelephense that tracks is undefined
        /** @var \App\Models\Track $user */
        $user = Auth::user();

        $filters = [];

        return view('track.index', [
            'tracks' => $user->tracks()->with('metrics')->filterTracks($filters)->orderSeason()->get()
                //this groupBy is NOT done in SQL, it's done by laravel on the collection coming from the database
                ->groupBy('season')
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
            //todo should all this logic be in the model?
            
            $jsonTrack = json_decode(file_get_contents($file), true);

            //get season years from name
            preg_match('/(\d{4})\/(\d{4})/', $jsonTrack['name'], $season);
            $jsonTrack['season'] = $season[0];

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

            //round all values to 1 decimal place
            foreach ($dbMetrics as $key => &$value)
            {
                //convert speeds from m/s to km/h
                if (strpos($key, 'speed') !== false)
                { $value = $value * 3.6; }
                $value = round($value, 1);
            }

            //for ski touring, overwrite ascents and descents
            if ($jsonTrack['activity'] === 'ski-touring' && !is_null($jsonTrack['trackSegments'][0]['metrics']['maxAltitude']))
            {
                $dbMetrics['ascents'] = $dbMetrics['descents'] = 0;
                $maxAltitude = $jsonTrack['trackMetrics']['maxAltitude'];

                foreach ($jsonTrack['trackSegments'] as $segment)
                {
                    //calculate difference in altitude
                    $differenceMax = abs($segment['metrics']['maxAltitude'] - $maxAltitude);

                    //only count entries with more or less than 50 meters of vertical, and only if they're close to the max altitude
                    if ($segment['metrics']['vertical'] > 50 && $differenceMax < 40)
                    { $dbMetrics['ascents']++; }
                    elseif ($segment['metrics']['vertical'] < -50 && $differenceMax < 40)
                    { $dbMetrics['descents']++; }
                }
            }

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
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
        //
    }
}
