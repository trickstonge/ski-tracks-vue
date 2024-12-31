<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Track extends Model
{
    /** @use HasFactory<\Database\Factories\TrackFactory> */
    use HasFactory;

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

    protected $with = ['metrics'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metrics()
    {
        return $this->hasOne(TrackMetric::class);
    }
    
    //event to add icon when retrieved from DB
    protected static function booted()
    {
        static::retrieved(function ($track) {
            $track->icon = match($track->activity)
            {
                'skiing' => 'fas-person-skiing',
                'ski-touring' => 'eos-forest',
                'x-country' => 'fas-skiing-nordic'
            };
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
