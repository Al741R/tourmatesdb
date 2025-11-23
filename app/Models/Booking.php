<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'plan_trip_id',
        'accommodation_id',
        'user_id',
        'check_in',
        'check_out',
        'number_of_people',
        'price_per_night',
        'number_of_nights',
        'total_amount',
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id', 'accommodation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
