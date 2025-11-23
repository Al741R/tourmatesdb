<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itinerary extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'itineraries_id';

    protected $fillable = [
        'plan_trip_id',
        'day',
        'title',
        'activity',
        'time',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'plan_trip_id', 'plan_trip_id');
    }
}
