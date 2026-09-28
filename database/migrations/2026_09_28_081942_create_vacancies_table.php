<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 4.1: Дедупликация, аналитика, модерация
     */
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            
            // Источник парсинга (HH, RSS, etc.)
            $table->foreignId('source_id')
                  ->constrained('sources')
                  ->onDelete('cascade');
            
            // ID вакансии во внешней системе (HH API возвращает свой ID)
            $table->string('external_id')->nullable();
            
            //  ДЕДУПЛИКАЦИЯ (ТЗ п. 4.1)
            // Хэш = md5(title + company + city)
            // Если внешние ID разные, но вакансия та же — хэш совпадёт
            $table->string('dedup_hash', 32)->unique();
            
            // Основные данные
            $table->string('title'); // "PHP Developer"
            $table->text('description'); // Полное описание
            $table->integer('salary_min')->nullable(); // Для аналитики медианной ЗП
            $table->integer('salary_max')->nullable();
            $table->string('currency')->default('RUB'); // RUB, USD, EUR
            $table->string('city'); // "Иркутск", "Москва"
            $table->string('url'); // Ссылка на оригинал
            
            // Если работодатель опубликовал вручную
            $table->foreignId('employer_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            // Модерация (ТЗ п. 4.1 "Ручная модерация")
            $table->boolean('is_moderated')->default(false); // false = на проверке
            $table->text('moderation_comment')->nullable(); // Причина отклонения
            
            // Дата публикации (оригинальная, не дата парсинга)
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
            
            // 🔥 ИНДЕКСЫ ДЛЯ БЫСТРОГО ПОИСКА И АНАЛИТИКИ
            $table->index('city'); // Фильтр по городу
            $table->index('salary_min'); // Сортировка по ЗП
            $table->index('is_moderated'); // Показать только одобренные
            $table->index('dedup_hash'); // Быстрая проверка дублей
            $table->index('published_at'); // Сортировка по дате
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};