<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'accommodation_id';

    protected $fillable = [
        'plan_trip_id',
        'name',
        'address',
        'category',
        'price',
        'room_type',
        'destination_id',
        'contact_number',
        'review',
        'rating',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'plan_trip_id', 'plan_trip_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'destination_id');
    }
}
