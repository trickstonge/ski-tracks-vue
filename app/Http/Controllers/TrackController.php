<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackRequest;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Track::class);

        if (Auth::guest())
        { 
            Inertia::share('pageTitle', 'About');
            
            return inertia('About/Index');
        }

        //activity is required if filterType is since
        $validator = Validator::make(request()->all(), [
            'activity' => 'required_if:filterType,since',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
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

        Inertia::share('pageTitle', 'Ski Tracks');

        return inertia('Track/Index', [
            'tracks' => $tracks ?? null,
            'totals' => $totals ?? null,
            'firstTrackID' => $firstTrack->id ?? null,
            'noTracks' => $noTracks ?? false,
            'activities' => Track::$activities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Track::class);

        Inertia::share('pageTitle', 'Upload Tracks');

        return inertia('Track/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrackRequest $request)
    {
        Gate::authorize('create', Track::class);
        
        $validated = $request->validated();

        $files = $validated['files'];
        //save each file to storage
        foreach ($files as $file)
        {
            $skizPath = $file->store('skiz', 'local');
            // Convert .skiz file to .json file with node.js script
            $command = "node ../resources/js/skiz-json.js ../storage/app/private/$skizPath";
            //exec appends each itiration to $paths
            exec($command, $paths);
        }

        /** @var \App\Models\Track $user */
        $user = Auth::user();

        //process each json file
        foreach($paths as $file)
        {
            $result = Track::processTrack($file, $user);

            if ($result)
            {
                $track = $user->tracks()->create($result['jsonTrack']);
                $track->metrics()->create($result['dbMetrics']);
            }
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

        Inertia::share('pageTitle', $track->name);

        return inertia('Track/Show', [
            'track' => $track->load('metrics')
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

    public function map()
    {
        Gate::authorize('viewMap', Track::class);

        $filters = request()->only(['activity', 'season']);

        /** @var \App\Models\Track $user */
        $user = Auth::user();

        //get seasons for the dropdown
        $seasons = $user->tracks()->select('season')->distinct()->orderBy('season', 'desc')->get()
            //pluck gets the values of the season, used twice to get a key and value
            ->pluck('season', 'season')->all();

        $tracks = $user->tracks()->filterTracks($filters)
            ->select('name','description','activity','latitude','longitude')->get();

        return view('track.map', [
            'tracks' => $tracks,
            'seasons' => $seasons
        ]);
    }

    public function chart()
    {
        Gate::authorize('viewMap', Track::class);

        /** @var \App\Models\Track $user */
        $user = Auth::user();

        $tracks = $user->tracks()
            ->select('season','name','start')->orderBy('start')->get();

        $tracks = Track::chart($tracks);

        return view('track.chart', [
            'tracks' => $tracks
        ]);
    }
}
