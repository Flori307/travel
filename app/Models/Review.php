<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'review_id';
    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'tour_id',
        'rating',
        'comment',
        'moderation_status',
        'moderation_comment',
        'moderated_by',
        'moderated_at',
        'has_profanity',
        'original_comment',
    ];

    protected $casts = [
        'rating' => 'integer',
        'moderated_at' => 'datetime',
        'has_profanity' => 'boolean',
    ];

    // Существующие отношения
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    // ДОБАВЬТЕ ЭТО ОТНОШЕНИЕ
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'moderated_by');
    }

    // Accessor для форматированной даты
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d.m.Y');
    }

    // Accessor для статуса модерации в виде бейджа
    public function getModerationStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">На модерации</span>',
            'approved' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Одобрен</span>',
            'rejected' => '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Отклонен</span>'
        ];
        
        return $badges[$this->moderation_status] ?? $badges['pending'];
    }

    // Scope для фильтрации
    public function scopePending($query)
    {
        return $query->where('moderation_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('moderation_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('moderation_status', 'rejected');
    }
}