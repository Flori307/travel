<?php
// app/Models/Tour.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $primaryKey = 'tour_id';
    public $timestamps = true;

    protected $fillable = [
        'hotel_id',
        'title',
        'description',
        'destination_country',
        'destination_city',
        'start_date',
        'end_date',
        'price',
        'available_spots',
        'image_url',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'tour_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'tour_id');
    }

    public function getDurationAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, '', ' ');
    }

    // Средний рейтинг тура
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Количество отзывов
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

   // Проверка, может ли пользователь оставить отзыв
public function canUserReview($userId): bool
{
    return $this->bookings()
        ->where('client_id', $userId)
        ->where(function($query) {
            $query->where('status', 'completed')
                  ->orWhere(function($q) {
                      $q->where('status', 'confirmed')
                        ->whereHas('tour', function($tourQuery) {
                            $tourQuery->where('end_date', '<', now());
                        });
                  });
        })
        ->exists();
}

// Получить отзыв пользователя (только одобренные или на модерации)
public function getUserReview($userId)
{
    return $this->reviews()->where('client_id', $userId)->first();
}

// Получить одобренный отзыв пользователя (для отображения на странице тура)
public function getApprovedUserReview($userId)
{
    return $this->reviews()
        ->where('client_id', $userId)
        ->where('moderation_status', 'approved')
        ->first();
}
}