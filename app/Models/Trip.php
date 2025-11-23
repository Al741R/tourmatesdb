<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    // keep existing table name to preserve migrations
    protected $table = 'plan_trips';
    protected $primaryKey = 'plan_trip_id';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'destination_id',
        'user_id',
        'price',
        'category',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'destination_id');
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function packLists()
    {
        return $this->hasMany(PackList::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'plan_trip_id', 'plan_trip_id');
    }
}
