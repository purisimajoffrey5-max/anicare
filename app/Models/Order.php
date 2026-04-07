<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'resident_id',
        'rice_product_id',
        'farmer_id',
        'buyer_name',
        'contact_number',
        'quantity_kilos',
        'unit_price',
        'total_price',
        'status',
        'fulfillment_type',
        'delivery_address',
        'pickup_address',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'quantity_kilos' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(RiceProduct::class, 'rice_product_id');
    }

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}