<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Сначала справочники (чтобы были ID для связей)
        $this->call([
            RolesSeeder::class,
            TagsSeeder::class,
            SourcesSeeder::class,
            VacanciesSeeder::class,
        ]);

        // 2. Создаем тестового пользователя (только если нет)
        if (!User::where('email', 'employer@test.com')->exists()) {
            User::create([
                'name' => 'Test Employer',
                'email' => 'employer@test.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // 2 = Работодатель (должен быть создан в RolesSeeder)
                'company_name' => 'Test IT Company',
                'is_active' => true,
            ]);
        }

        if (!User::where('email', 'admin@test.com')->exists()) {
            User::create([
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // 3 = Администратор
                'company_name' => null,
                'is_active' => true,
            ]);
        }
    }
}