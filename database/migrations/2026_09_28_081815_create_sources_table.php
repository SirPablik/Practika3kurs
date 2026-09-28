<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 4.1: Управление источниками парсинга (вкл/выкл без деплоя)
     */
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'HH API', 'RSS Company X', 'Habr Career'
            $table->enum('type', ['api', 'rss', 'html']); // Тип источника
            
            // URL для RSS или API endpoint
            $table->string('url')->nullable(); 
            
            // Настройки в JSONB (токены, лимиты, заголовки)
            // Пример: {"token": "xxx", "requests_per_minute": 12}
            $table->jsonb('config')->nullable(); 
            
            // Флаг активности (ТЗ п. 4.1 "включение/выключение")
            $table->boolean('is_active')->default(true); 
            
            // Последняя успешная выгрузка (для мониторинга)
            $table->timestamp('last_parsed_at')->nullable();
            
            $table->timestamps();
            
            // Индекс для быстрого поиска активных источников
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};