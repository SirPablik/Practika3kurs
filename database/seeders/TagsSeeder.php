<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'PHP', 'aliases' => ['php', 'php7', 'php8', 'пхп']],
            ['name' => 'Laravel', 'aliases' => ['laravel', 'laravel framework']],
            ['name' => 'Python', 'aliases' => ['python', 'python3', 'пайтон']],
            ['name' => 'JavaScript', 'aliases' => ['js', 'javascript', 'ecmascript']],
            ['name' => 'PostgreSQL', 'aliases' => ['postgres', 'pgsql', 'postgre']],
            ['name' => 'Docker', 'aliases' => ['docker', 'container']],
            ['name' => 'Vue.js', 'aliases' => ['vue', 'vuejs', 'vue3']],
            ['name' => 'React', 'aliases' => ['react', 'reactjs']],
            ['name' => 'Git', 'aliases' => ['git', 'version control']],
            ['name' => 'Linux', 'aliases' => ['linux', 'ubuntu', 'debian']],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}