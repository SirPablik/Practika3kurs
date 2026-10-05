<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vacancy;
use App\Models\Tag;
use App\Models\Source;

class VacanciesSeeder extends Seeder
{
    public function run(): void
    {
        $source = Source::first();
        $tags = Tag::take(5)->pluck('id')->toArray();

        $jobs = [
            ['title' => 'PHP Developer (Laravel)', 'min' => 100000, 'max' => 200000],
            ['title' => 'Frontend Developer (Vue.js)', 'min' => 80000, 'max' => 150000],
            ['title' => 'Python Backend Developer', 'min' => 120000, 'max' => 250000],
            ['title' => 'DevOps Engineer', 'min' => 150000, 'max' => 300000],
            ['title' => 'Fullstack Developer', 'min' => 90000, 'max' => 180000],
            ['title' => 'Junior PHP Developer', 'min' => 40000, 'max' => 70000],
            ['title' => 'Middle Laravel Developer', 'min' => 120000, 'max' => 220000],
            ['title' => 'Senior Backend Developer', 'min' => 200000, 'max' => 400000],
        ];

        foreach ($jobs as $index => $job) {
            $vacancy = Vacancy::create([
                'source_id' => $source->id,
                'dedup_hash' => md5($job['title'] . 'Company' . 'Иркутск' . $index),
                'title' => $job['title'],
                'description' => 'Мы ищем талантливого разработчика в нашу команду. Опыт работы с Laravel, PostgreSQL, Docker. Удаленная работа, гибкий график.',
                'salary_min' => $job['min'],
                'salary_max' => $job['max'],
                'currency' => 'RUB',
                'city' => 'Иркутск',
                'url' => 'https://example.com/vacancy/' . $index,
                'employer_id' => null,
                'is_moderated' => true, // ВАЖНО: чтобы отображались
                'published_at' => now()->subDays(rand(0, 10)),
            ]);
            $vacancy->tags()->sync($tags);
        }
    }
}