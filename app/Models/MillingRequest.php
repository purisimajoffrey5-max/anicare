<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MillingRequest extends Model
{
    protected $fillable = [
        'user_id','miller_id','kilos','notes','status','scheduled_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}