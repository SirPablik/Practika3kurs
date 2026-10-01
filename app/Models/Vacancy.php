<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends Model
{
    protected $fillable = [
        'source_id',
        'external_id',
        'dedup_hash',
        'title',
        'description',
        'salary_min',
        'salary_max',
        'currency',
        'city',
        'url',
        'employer_id',
        'is_moderated',
        'moderation_comment',
        'published_at',
    ];

    protected $casts = [
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'is_moderated' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Связь: вакансия принадлежит источнику
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    // Связь: вакансия принадлежит работодателю (пользователю)
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    // Связь: у вакансии много тегов (Many-to-Many)
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'vacancy_tag');
    }

    // Связь: вакансию сохранили много пользователей
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_vacancies');
    }
}