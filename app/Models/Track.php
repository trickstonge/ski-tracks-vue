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
    protected static function booted()
    {
        static::retrieved(function ($track) {
            //add icon based on activity
            $track->icon = match($track->activity)
            {
                'skiing' => 'fas-person-skiing',
                'ski-touring' => 'ski-touring-icon',
                'x-country' => 'fas-skiing-nordic'
            };
            //convert duration to hours and minutes
            if ($track->duration > 3600)
            { $track->duration = date('g:i', $track->duration); }
            else
            { $track->duration = date('i', $track->duration); }

        });
    }

    public function scopeFilterTracks(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query->when($filters['description'] ?? null, function ($query, $search) {
            $query->where('description', 'like', '%' . $search . '%');
        });
    }
    
    public function scopeOrderSeason(Builder|QueryBuilder $query): Builder|QueryBuilder
    {
        return $query->orderBy('season', 'desc')->orderBy('start', 'asc');
    }
}
