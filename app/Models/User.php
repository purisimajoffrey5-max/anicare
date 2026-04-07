<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fullname',
        'username',
        'email',
        'barangay',
        'password',
        'role',
        'is_approved',
        'approved_at',
        'is_open',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at'       => 'datetime',
        'is_approved'       => 'boolean',
        'is_open'           => 'boolean',
        'password'          => 'hashed', // Laravel 10+
        'latitude'          => 'float',
        'longitude'         => 'float',
    ];
}