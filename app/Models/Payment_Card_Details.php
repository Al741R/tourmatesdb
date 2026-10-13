<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payment_Card_Details extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'payment_card_id';
    protected $table = 'payment_card_details';

    protected $fillable = [
        'user_id',
        'card_name',
        'card_number',
        'expiry_date',
        'postal_code',
        'payment_id',
    ];

    protected $casts = [
        'card_number' => 'encrypted', // Automatically encrypt/decrypt card numbers
    ];

    public function payment()
    {
        return $this->belongsTo(Payment_Method::class, 'payment_id', 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
