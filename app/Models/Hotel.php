<?php
// app/Models/Hotel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $primaryKey = 'hotel_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'amenities',
        'address',
        'city',
        'country',
        'star_rating',
        'contact_phone',
        'image_url',
        'check_in_time',
        'check_out_time',
        'room_count',
        'latitude',
        'longitude',
        'website',
        'email',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'room_count' => 'integer',
        'star_rating' => 'integer',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'hotel_id');
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->country}, {$this->city}, {$this->address}";
    }

    public function getAmenitiesListAttribute(): array
    {
        if (is_array($this->amenities)) {
            return $this->amenities;
        }
        return json_decode($this->amenities, true) ?? [];
    }

    public function getCoordinatesAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }
        return null;
    }
}