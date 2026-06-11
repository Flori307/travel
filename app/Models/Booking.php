<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';
    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'tour_id',
        'booking_date',
        'number_of_people',
        'status',
        'total_price',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    // Accessor для форматированной даты бронирования
    public function getFormattedBookingDateAttribute(): string
    {
        return $this->booking_date->format('d.m.Y H:i');
    }

    // Accessor для форматированной общей стоимости
    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 0, '', ' ');
    }

    // Метод для проверки возможности отмены
    public function canBeCancelled(): bool
    {
        return $this->status === 'confirmed';
    }

    // Метод для проверки завершенного тура
    public function isCompleted(): bool
    {
        return $this->status === 'completed' && 
               $this->tour->end_date < now();
    }
}