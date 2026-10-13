<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'destination_id';

    protected $fillable = [
        'name',
        'city',
        'address',
        'details',
        'category',
        'rating',
        'reviews',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'destination_id', 'destination_id');
    }
}
