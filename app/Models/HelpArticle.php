<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $fillable = [
        'slug',
        'roles',
        'category',
        'title',
        'summary',
        'content',
        'keywords',
        'route_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'roles' => 'array',
        'keywords' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeForRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereJsonContains('roles', $role)
              ->orWhereJsonContains('roles', 'all');
        });
    }
}
