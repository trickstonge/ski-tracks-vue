<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackRequest;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Track::class);

        if (Auth::guest())
        { return view('track.index'); }

        //activity is required if filterType is since
        $validator = Validator::make(request()->all(), [
            'activity' => 'required_if:filterType,since',
        ]);
        if ($validator->fails()) {
            return redirect()->route('track.index')
                ->withErrors($validator)
                ->withInput();
        }
        
        //the line below defines a DocBlock. It fixes a false error from intelephense that tracks is undefined
        /** @var \App\Models\Track $user */
        $user = Auth::user();

        $filters = request()->only(['description', 'filterType', 'activity']);
        //if filterType is since, find the first track that matches the description, and add date to filters. Doing it here so we can get and return the ID to highlight in the view.
        if (isset($filters['filterType']) && $filters['filterType'] == 'since')
        {
            $firstTrack = $user->tracks()->firstTrack($filters)->first();
            if ($firstTrack)
            {
                $filters['since'] = $firstTrack->start;
                //unset description so it's not filtered in the model
                unset($filters['description']);
            }
        }

        $tracks = $user->tracks()->with('metrics')->filterTracks($filters)->orderSeason()->get();
        
        if ($tracks->isEmpty())
        {
            //check if the user has any tracks, to determine between no results and no uploaded data
            if ($user->tracks->isEmpty())
            { $noTracks = true; }
        }
        else
        {
            if ($user->imperial)
            { $tracks = Track::convertUnits($tracks); }
            $tracks = Track::seasonTotals($tracks);
            $totals = Track::grandTotals($tracks);
        }

        return view('track.index', [
            'tracks' => $tracks ?? null,
            'totals' => $totals ?? null,
            'firstTrackID' => $firstTrack->id ?? null,
            'noTracks' => $noTracks ?? false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Track::class);

        return view('track.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrackRequest $request)
    {
        Gate::authorize('create', Track::class);
        
        $validated = $request->validated();

        $files = $validated['files'];
        foreach ($files as $file)
        {
            //todo should all this logic be in the model?
            
            $jsonTrack = json_decode(file_get_contents($file), true);

            //get season years from name
            preg_match('/(\d{4})\/(\d{4})/', $jsonTrack['name'], $season);
            $jsonTrack['season'] = $season[0];

            //remove weird and extra spaces from description
            $jsonTrack['description'] = preg_replace(['/\xc2\xa0/', '/\s+/'], [' ', ' '], $jsonTrack['description']);

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
                //convert distances from meters to kilometers
                elseif (strpos($key, 'distance') !== false)
                { $value = $value / 1000; }
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
        Gate::authorize('view', $track);

        return view('track.show', [
            'track' => $track
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
        Gate::authorize('delete', $track);

        $track->delete();

        return redirect()->route('track.index')
            ->with('success', 'Track deleted successfully.');
    }
}
