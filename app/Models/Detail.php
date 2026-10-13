<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'details_id';

    protected $fillable = [
        'plan_trip_id',
        'accommodation_id',
        'itineraries_id',
        'list_id',
        'key',
        'value',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id', 'accommodation_id');
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class, 'itineraries_id', 'itineraries_id');
    }

    public function packList()
    {
        return $this->belongsTo(PackList::class, 'list_id', 'pack_list_id');
    }
}
