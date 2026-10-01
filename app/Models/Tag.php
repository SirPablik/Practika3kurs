<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'aliases'];

    protected $casts = [
        'aliases' => 'array', // Автоматически превращает JSONB в массив
    ];

    // Связь: у одного тега много вакансий (Many-to-Many)
    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'vacancy_tag');
    }
}