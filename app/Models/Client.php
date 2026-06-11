<?php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use SoftDeletes;
    
    protected $primaryKey = 'client_id';
    public $timestamps = true;

    // ЭТОТ МЕТОД КРИТИЧЕСКИ ВАЖЕН ДЛЯ АВТОРИЗАЦИИ
    public function getAuthIdentifierName()
    {
        return 'client_id';
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'registration_date',
        'password',
        'remember_token',
        'role',
        'is_blocked',
        'blocked_at',
        'block_reason',
        'blocked_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    // Методы для блокировки
    public function block($reason = null, $blockedBy = null)
    {
        $this->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'block_reason' => $reason,
            'blocked_by' => $blockedBy ?? auth()->id(),
        ]);
    }

    public function unblock()
    {
        $this->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'block_reason' => null,
            'blocked_by' => null,
        ]);
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    // Отношения
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id', 'client_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'client_id', 'client_id');
    }

    public function blockedByUser()
    {
        return $this->belongsTo(Client::class, 'blocked_by', 'client_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getRoleNameAttribute(): string
    {
        return [
            'user' => 'Пользователь',
            'moderator' => 'Модератор',
            'admin' => 'Администратор'
        ][$this->role] ?? 'Пользователь';
    }
}