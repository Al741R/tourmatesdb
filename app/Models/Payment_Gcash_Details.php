<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_Gcash_Details extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'payment_gcash_id';
    protected $table = 'payment_gcash_details';

    protected $fillable = [
        'payment_id',
        'booking_id',
        'gcash_number',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment_Method::class, 'payment_id', 'payment_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
