<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmProfile extends Model
{
    protected $fillable = [
        'user_id',
        'farm_name',
        'location',
        'contact',
        'address',
        'barangay',
        'municipality',
        'province',
        'farm_size_hectares',
        'contact_no',
        'latitude',
        'longitude',
    ];
}