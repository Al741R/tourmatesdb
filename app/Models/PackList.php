<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackList extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'pack_list_id';

    protected $fillable = [
        'plan_trip_id',
        'list_title',
        'item',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'plan_trip_id', 'plan_trip_id');
    }
}
