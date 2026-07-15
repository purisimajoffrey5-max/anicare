<?php

namespace App\Models;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MillingRequest extends Model
{
    protected $fillable = [
        'user_id', 'miller_id', 'inventory_item_id', 'kilos', 'notes', 'status', 'scheduled_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function miller()
    {
        return $this->belongsTo(User::class, 'miller_id');
    }
}
