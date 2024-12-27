<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackMetric extends Model
{
    /** @use HasFactory<\Database\Factories\TrackMetricFactory> */
    use HasFactory;

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
