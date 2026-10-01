<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Source;

class SourcesSeeder extends Seeder
{
    public function run(): void
    {
        Source::create([
            'name' => 'HH API',
            'type' => 'api',
            'url' => 'https://api.hh.ru/',
            'config' => ['requests_per_minute' => 12],
            'is_active' => true,
        ]);

        Source::create([
            'name' => 'Habr Career RSS',
            'type' => 'rss',
            'url' => 'https://habr.com/ru/rss/jobs/',
            'config' => ['encoding' => 'UTF-8'],
            'is_active' => true,
        ]);

        Source::create([
            'name' => 'Local IT Companies RSS',
            'type' => 'rss',
            'url' => null,
            'config' => [],
            'is_active' => false, // Выключено по умолчанию
        ]);
    }
}