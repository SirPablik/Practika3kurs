<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'company_name',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── СВЯЗИ (добавлено для ТЗ) ───

    /**
     * Связь: пользователь имеет одну роль
     * ТЗ п. 3.2: Категории пользователей
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Связь: у пользователя много вакансий (если он работодатель)
     * ТЗ п. 4.1: ЛК работодателя
     */
    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class, 'employer_id');
    }

    /**
     * Связь: пользователь сохранил много вакансий
     * ТЗ п. 4.1: ЛК соискателя (избранное)
     */
    public function savedVacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'saved_vacancies');
    }

    /**
     * Связь: у пользователя много подписок
     * ТЗ п. 4.1: Email-уведомления
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}