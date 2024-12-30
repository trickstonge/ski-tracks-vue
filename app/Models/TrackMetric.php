<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackMetric extends Model
{
    /** @use HasFactory<\Database\Factories\TrackMetricFactory> */
    use HasFactory;

    protected $fillable = [
        'max_speed',
        'max_descent_speed',
        'max_ascent_speed',
        'max_descent_steepness',
        'max_ascent_steepness',
        'total_ascent',
        'total_descent',
        'max_altitude',
        'min_altitude',
        'distance',
        'descent_distance',
        'ascent_distance',
        'average_speed',
        'average_descent_speed',
        'average_ascent_speed',
        'start_altitude',
        'finish_altitude',
        'ascents',
        'descents'
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
