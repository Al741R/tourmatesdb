<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
protected $primaryKey = 'notification_id';

protected $fillable = [
        'user_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
