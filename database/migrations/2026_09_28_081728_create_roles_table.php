<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ТЗ п. 3.2: Категории пользователей (Соискатель, Работодатель, Администратор)
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 'seeker', 'employer', 'admin' (для кода)
            $table->string('display_name'); // 'Соискатель', 'Работодатель', 'Администратор' (для UI)
            $table->text('description')->nullable(); // Описание прав роли
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};