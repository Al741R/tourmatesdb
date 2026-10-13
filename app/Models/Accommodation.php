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
        'contact_number',
        'review',
        'rating',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'plan_trip_id', 'plan_trip_id');
    }

    // Access destination through trip relationship (3NF compliant)
    public function destination()
    {
        return $this->hasOneThrough(
            Destination::class,
            Trip::class,
            'plan_trip_id',      // Foreign key on trips table
            'destination_id',    // Foreign key on destinations table
            'plan_trip_id',      // Local key on accommodations table
            'destination_id'     // Local key on trips table
        );
    }
}
