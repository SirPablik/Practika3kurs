<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 4.1: Связь вакансий и тегов (Many-to-Many) для поиска по стеку
     */
    public function up(): void
    {
        Schema::create('vacancy_tag', function (Blueprint $table) {
            // Внешний ключ на вакансии
            $table->foreignId('vacancy_id')
                  ->constrained('vacancies')
                  ->onDelete('cascade'); // Если вакансия удалена → удаляем связи
            
            // Внешний ключ на теги
            $table->foreignId('tag_id')
                  ->constrained('tags')
                  ->onDelete('cascade'); // Если тег удалён → удаляем связи
            
            // 🔥 КОМПОЗИТНЫЙ ПЕРВИЧНЫЙ КЛЮЧ
            // Заменяет обычное $table->id()
            // Гарантирует, что одна и та же связь не повторится
            $table->primary(['vacancy_id', 'tag_id']);
            
            // Индекс для быстрого поиска вакансий по тегу
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_tag');
    }
};