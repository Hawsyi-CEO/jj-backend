<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'price_range',
        'features',
        'duration',
        'category',
        'image_url',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMcServices($query)
    {
        return $query->where('category', 'mc');
    }

    public function scopeWeddingOrganizerServices($query)
    {
        return $query->where('category', 'wedding_organizer');
    }
}
