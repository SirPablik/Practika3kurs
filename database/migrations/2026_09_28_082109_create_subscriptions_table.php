<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 4.1: Подписки на фильтры + email-уведомления
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            
            // Пользователь (соискатель)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // 🔥 ФИЛЬТРЫ В JSONB (гибкая структура)
            // Пример: {"tags": [1, 5, 10], "city": "Иркутск", "salary_min": 100000}
            $table->jsonb('filters');
            
            // Чтобы не спамить письмами каждый час
            $table->timestamp('last_notified_at')->nullable();
            
            // Активность подписки (пользователь может временно отключить)
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // 🔥 GIN-индекс для быстрого поиска по фильтрам (PostgreSQL)
            $table->index('filters', 'subscriptions_filters_gin', 'gin');
            
            // Индекс для поиска активных подписок пользователя
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};