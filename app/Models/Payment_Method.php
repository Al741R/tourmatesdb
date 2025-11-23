<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_Method extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'payment_id';
    protected $table = 'payment_method';
    protected $fillable = [
        'booking_id',
        'payment_options',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
