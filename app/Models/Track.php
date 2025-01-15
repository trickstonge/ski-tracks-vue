<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Track extends Model
{
    protected $fillable =
    [
        'user_id',
        'season',
        'name',
        'rating',
        'start',
        'finish',
        'description',
        'activity',
        'duration',
        'latitude',
        'longitude',
    ];

    public static array $activities =
    [
        'skiing' => 'Skiing', 
        'ski-touring' => 'Ski Touring', 
        'x-country' => 'Cross Country'
    ];

    public static array $icons =
    [
        'skiing' => 'fas-person-skiing', 
        'ski-touring' => 'ski-touring-icon', 
        'x-country' => 'fas-skiing-nordic'
    ];

    protected $casts = [
        'start' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metrics()
    {
        return $this->hasOne(TrackMetric::class);
    }

    //add an attribute for formated duration. Called an accessor
    protected function durationFormated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->duration > 3600 ? date('g:i', $this->duration) : date('i', $this->duration),
        );
    }
    
    //filters for search form
    public function scopeFilterTracks(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query->when($filters['description'] ?? null, function ($query, $description) {
            $query->where('description', 'like', '%' . $description . '%');
        })->when($filters['since'] ?? null, function ($query, $since) {
            $query->where('start', '>=', $since);
        })->when($filters['activity'] ?? null, function ($query, $activity) {
            $query->where('activity', $activity);
        });
    }
    
    public function scopeOrderSeason(Builder|QueryBuilder $query): Builder|QueryBuilder
    {
        return $query->orderBy('season', 'desc')->orderBy('start', 'asc');
    }

    //get the first track that matches description. Used when filter type is since.
    public function scopeFirstTrack(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query->where('description', 'like', '%' . $filters['description'] . '%')
            //need to filter by actvity, otherwise the match may not be returned if the description search was found in a different activity
            ->where('activity', $filters['activity'])
            ->orderBy('start', 'asc');
    }
    
    //calculate totals for all seasons
    public static function grandTotals($tracks)
    {
        $seasons = $tracks->pluck('totals');
        $totals = [];

        $seasons->each(function($season) use (&$totals)
        {
            foreach ($season as $key => $value)
            {
                if ($key == 'activities')
                {
                    foreach ($value as $activity => $count)
                    {
                        $totals['activities'][$activity] = ($totals['activities'][$activity] ?? 0) + $count;
                    }
                }
                else
                {
                    $totals[$key] = ($totals[$key] ?? 0) + $value;
                }
            }
        });
        
        return $totals;

    }

    public static function seasonTotals($tracks)
    {
        //working on the collection (this group by is not SQL but laravel), group by season
        $tracks = $tracks->groupBy('season');
        
        //calculate totals for each season
        $seasonTotals = $tracks->map(function ($season) {
            $totals = [];
            $totals['activities'] = $season->countBy('activity');
            //make sure all 3 keys exist
            $totals['activities'] = array_merge(
                array_fill_keys(array_keys(self::$activities), 0),
                $totals['activities']->toArray());
            $totals['activities']['total'] = $season->count();

            //get number of days
            $days = $season->map(function ($track)
            {
                preg_match('/Day (\d{1,3})/', $track->name, $matches);
                return $matches[1];
            });
            $totals['days'] = $days->unique()->count();

            //get total runs, only for skiing and ski-touring
            $totals['runs'] = $season->filter(function ($track)
            {
                return $track->activity != 'x-country';
            })->sum('metrics.descents');

            $totals['descent'] = $season->sum('metrics.total_descent');

            //distance, total for XC, descent only for others
            $totals['distance'] = $season->filter(function ($track)
            {
                return $track->activity == 'x-country';
            })->sum('metrics.distance');
            $totals['distance'] += $season->filter(function ($track)
            {
                return $track->activity != 'x-country';
            })->sum('metrics.descent_distance');

            $totals['time'] = round($season->sum('duration') / 3600);

            return $totals;
        });

        //get keys to use with map
        $seasons = $tracks->keys();
        //zip combines the two collections (tracks and seasonTotals) into one, then mapWithKeys creates a new collection with the season keys
        return $tracks->zip($seasonTotals)->mapWithKeys(function ($item, $key) use ($seasons) {
            //once again for the track and totals keys
            $item = $item->mapWithKeys(function ($value, $key) {
                return $key === 0 ? ['tracks' => $value] : ['totals' => $value];
            });
            return [$seasons[$key] => $item];
        });
    }

    //process the json track file when uploaded
    public static function processTrack($file, $user)
    {
        $jsonTrack = json_decode(file_get_contents($file), true);

        //only save skiing, ski-touring, and x-country
        if (!in_array($jsonTrack['activity'], ['skiing', 'ski-touring', 'x-country']))
        { return; }
        
        //remove weird and extra spaces from description
        $jsonTrack['description'] = preg_replace(['/\xc2\xa0/', '/\s+/'], [' ', ' '], $jsonTrack['description']);

        //check if track exists using name, description, and activity
        if ($user->tracks()->where('name', $jsonTrack['name'])
            ->where('description', $jsonTrack['description'])
            ->where('activity', $jsonTrack['activity'])
            ->exists())
        { return; }

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

        return ['jsonTrack' => $jsonTrack, 'dbMetrics' => $dbMetrics];
    }

    public static function convertUnits($tracks)
    {
        return $tracks->each(function ($track)
        {
            $track->metrics->distance = round($track->kmToMiles($track->metrics->distance), 1);
            $track->metrics->descent_distance = round($track->kmToMiles($track->metrics->descent_distance), 1);
            $track->metrics->average_speed = round($track->kmToMiles($track->metrics->average_speed), 1);
            $track->metrics->max_speed = round($track->kmToMiles($track->metrics->max_speed), 1);
            $track->metrics->average_descent_speed = round($track->kmToMiles($track->metrics->average_descent_speed), 1);
            $track->metrics->total_descent = round($track->metersToFeet($track->metrics->total_descent), 1);
        });
    }

    private function kmToMiles($km): float
    {
        return $km * 0.621371;
    }

    private function metersToFeet($meters): float
    {
        return $meters * 3.28084;
    }
}