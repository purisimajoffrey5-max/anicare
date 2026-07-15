<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';

    protected $fillable = [
        'order_id',
        'milling_request_id',
        'name',
        'product_type',
        'kilos_available',
        'price_per_kg',
        'status',
        'notes',
    ];

    protected $casts = [
        'kilos_available' => 'float',
        'price_per_kg' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function millingRequest()
    {
        return $this->hasOne(MillingRequest::class, 'inventory_item_id');
    }
}
