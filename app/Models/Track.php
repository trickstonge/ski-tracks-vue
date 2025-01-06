<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;

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

    public static function organizeSeasonTotals($tracks)
    {
        //working on the collection (this group by is not SQL but laravel), calculate totals for each season
        return $tracks->groupBy('season')->transform(function ($season) {
            $season->totals = [];
            $season->totals['activities'] = $season->countBy('activity');
            //make sure all 3 keys exist
            $season->totals['activities'] = array_merge(
                array_fill_keys(array_keys(self::$activities), 0),
                $season->totals['activities']->toArray());
            $season->totals['activities']['total'] = $season->count();

            //get number of days
            $days = $season->map(function ($track)
            {
                preg_match('/Day (\d{1,3})/', $track->name, $matches);
                return $matches[1];
            });
            $season->totals['days'] = $days->unique()->count();

            //get total runs, only for skiing and ski-touring
            $season->totals['runs'] = $season->filter(function ($track)
            {
                return $track->activity != 'x-country';
            })->sum('metrics.descents');

            $season->totals['descent'] = $season->sum('metrics.total_descent');

            //distance, total for XC, descent only for others
            $season->totals['distance'] = $season->filter(function ($track)
            {
                return $track->activity == 'x-country';
            })->sum('metrics.distance');
            $season->totals['distance'] += $season->filter(function ($track)
            {
                return $track->activity != 'x-country';
            })->sum('metrics.descent_distance');

            $season->totals['time'] = round($season->sum('duration') / 3600);

            return $season;
        });
    }

    //get the first track that matches description. Used when filter type is since.
    public static function getFirstTrack($filters)
    {
        /** @var \App\Models\Track $user */
        $user = Auth::user();
        return $user->tracks()
            ->where('description', 'like', '%' . $filters['description'] . '%')
            //need to filter by actvity, otherwise the match may not be returned if the description search was found in a different activity
            ->where('activity', $filters['activity'])
            ->orderBy('start', 'asc')->first();
    }
}
