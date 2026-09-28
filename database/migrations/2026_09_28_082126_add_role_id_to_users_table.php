<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 3.2: Добавляем связь пользователя с ролью
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Внешний ключ на таблицу roles
            $table->foreignId('role_id')
                  ->default(1) // По умолчанию — Соискатель
                  ->constrained('roles')
                  ->onDelete('restrict'); // Нельзя удалить роль, если есть пользователи
            
            // Для работодателей — название компании (ТЗ п. 3.2)
            $table->string('company_name')->nullable();
            
            // Активность пользователя (модерация/бан)
            $table->boolean('is_active')->default(true);
            
            // Индекс для быстрого поиска по роли
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropIndex(['role_id']);
            $table->dropColumn(['role_id', 'company_name', 'is_active']);
        });
    }
};