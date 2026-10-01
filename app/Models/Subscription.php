<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'filters', 'last_notified_at', 'is_active'];

    protected $casts = [
        'filters' => 'array', // Автоматически превращает JSONB в массив
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime',
    ];

    // Связь: подписка принадлежит пользователю
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}