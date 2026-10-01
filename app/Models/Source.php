<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = ['name', 'type', 'url', 'config', 'is_active', 'last_parsed_at'];

    protected $casts = [
        'config' => 'array', // Автоматически превращает JSONB в массив
        'is_active' => 'boolean',
        'last_parsed_at' => 'datetime',
    ];

    // Связь: у одного источника много вакансий
    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }
}