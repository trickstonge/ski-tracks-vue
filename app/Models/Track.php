<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Track extends Model
{
    protected $fillable = [
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metrics()
    {
        return $this->hasOne(TrackMetric::class);
    }
    
    //event to modify data when retrieved from DB
    //todo should this logic be in the model? Resources seem specific to building an API, and I found something called transformers but those seem to only be in old versions of laravel.
    protected static function booted()
    {
        static::retrieved(function ($track) {
            //convert duration to hours and minutes
            if ($track->duration > 3600)
            { $track->duration = date('g:i', $track->duration); }
            else
            { $track->duration = date('i', $track->duration); }
            //format start time
            $track->start = date('m/d/y g:i A', strtotime($track->start));

        });
    }

    public static function getIcon($activity)
    {
        return match($activity)
        {
            'skiing' => 'fas-person-skiing',
            'ski-touring' => 'ski-touring-icon',
            'x-country' => 'fas-skiing-nordic'
        };
    }

    public function scopeFilterTracks(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query->when($filters['description'] ?? null, function ($query, $search) {
            $query->where('description', 'like', '%' . $search . '%');
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
            $season->totals['activities'] = array_merge(['skiing' => 0, 'ski-touring' => 0, 'x-country' => 0], $season->totals['activities']->toArray());
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

            return $season;
        });
    }
}
