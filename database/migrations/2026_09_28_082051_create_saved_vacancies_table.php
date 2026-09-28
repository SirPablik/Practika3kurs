<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 4.1: ЛК соискателя - сохранённые вакансии (избранное)
     */
    public function up(): void
    {
        Schema::create('saved_vacancies', function (Blueprint $table) {
            $table->id();
            
            // Пользователь (соискатель)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Если пользователь удалён → удаляем сохранения
            
            // Вакансия
            $table->foreignId('vacancy_id')
                  ->constrained('vacancies')
                  ->onDelete('cascade'); // Если вакансия удалена → удаляем из избранного
            
            // Дата сохранения (для сортировки "недавно добавленные")
            $table->timestamp('saved_at')->useCurrent();
            
            // 🔥 УНИКАЛЬНОСТЬ: пользователь не может сохранить одну вакансию дважды
            $table->unique(['user_id', 'vacancy_id']);
            
            // Индексы для быстрого поиска
            $table->index('user_id');
            $table->index('vacancy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_vacancies');
    }
};