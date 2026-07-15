<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'beneficiary_name',
        'beneficiary_email',
        'barangay',
        'rice_qty',
        'scheduled_at',
        'status',
        'processed_by',
        'notes',
    ];

    protected $casts = [
        'rice_qty' => 'float',
        'scheduled_at' => 'datetime',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
