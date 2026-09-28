<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 4.1: Нормализация стека технологий + поиск по алиасам
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            // Нормализованное имя (единый формат)
            $table->string('name')->unique(); // "PHP", "Laravel", "PostgreSQL"
            
            // Алиасы для поиска (JSONB - преимущество PostgreSQL)
            // Пример: ["php", "php7", "php8", "пхп", "hypertext preprocessor"]
            $table->jsonb('aliases')->default('[]');
            
            $table->timestamps();
            
            // GIN-индекс для быстрого поиска по JSONB (PostgreSQL фишка)
            // Позволяет искать вакансию по алиасу "пхп" → найдёт тег "PHP"
            $table->index('aliases', 'tags_aliases_gin', 'gin');
            
            // Индекс для поиска по имени
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};