<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    /** @use HasFactory<\Database\Factories\TrackFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function metrics()
    {
        return $this->hasOne(TrackMetric::class);
    }
}
