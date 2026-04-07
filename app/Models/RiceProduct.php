<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiceProduct extends Model
{
    use HasFactory;

    protected $table = 'rice_products';

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'price_per_kg',
        'kilos_available',
        'photo_path',
        'is_active',
    ];

    protected $casts = [
        'price_per_kg'    => 'float',
        'kilos_available' => 'float',
        'is_active'       => 'boolean',
    ];

    // ✅ farmer relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ✅ Compatibility Accessors / Mutators
    | So your existing code can use: $p->price, $p->kilos, $p->photo
    |--------------------------------------------------------------------------
    */

    public function getPriceAttribute()
    {
        return (float) ($this->price_per_kg ?? 0);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price_per_kg'] = $value;
    }

    public function getKilosAttribute()
    {
        return (float) ($this->kilos_available ?? 0);
    }

    public function setKilosAttribute($value)
    {
        $this->attributes['kilos_available'] = $value;
    }

    public function getPhotoAttribute()
    {
        return $this->photo_path;
    }

    public function setPhotoAttribute($value)
    {
        $this->attributes['photo_path'] = $value;
    }
}