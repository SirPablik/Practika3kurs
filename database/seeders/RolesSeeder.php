<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'seeker', 'display_name' => 'Соискатель', 'description' => 'Ищет работу']);
        Role::create(['name' => 'employer', 'display_name' => 'Работодатель', 'description' => 'Публикует вакансии']);
        Role::create(['name' => 'admin', 'display_name' => 'Администратор', 'description' => 'Управляет системой']);
    }
}