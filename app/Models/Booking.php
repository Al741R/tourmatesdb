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
        'status',
    ];

    protected $appends = ['number_of_nights', 'total_amount'];

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

    public function paymentMethods()
    {
        return $this->hasMany(Payment_Method::class, 'booking_id', 'booking_id');
    }

    // Computed property: number_of_nights (3NF compliant)
    public function getNumberOfNightsAttribute()
    {
        if (!$this->check_in || !$this->check_out) {
            return 0;
        }
        return \Carbon\Carbon::parse($this->check_in)->diffInDays($this->check_out);
    }

    // Computed property: total_amount (3NF compliant)
    public function getTotalAmountAttribute()
    {
        return $this->price_per_night * $this->number_of_nights;
    }
}
