<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vacancy;
use App\Models\Source;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ParseTrudvsemApi extends Command
{
    protected $signature = 'parse:trudvsem {--search=Врач} {--limit=10}';
    protected $description = 'Парсинг вакансий через API trudvsem.ru';

    public function handle(): int
    {
        $this->info('Начинаю парсинг API trudvsem.ru...');

        $search = $this->option('search');
        $limit = $this->option('limit');

        $source = Source::firstOrCreate(
            ['name' => 'Работа России (API)'],
            ['type' => 'api', 'url' => 'https://trudvsem.ru', 'is_active' => true]
        );

        $count = 0;
        $added = 0;

        try {
            $response = Http::timeout(60)
                ->withOptions(['verify' => false, 'http_errors' => false])
                ->get('https://opendata.trudvsem.ru/api/v1/vacancies', [
                    'text' => $search,
                    'limit' => $limit,
                ]);

            if ($response->failed()) {
                $this->error('Ошибка API: ' . $response->status());
                return Command::FAILURE;
            }

            $data = $response->json();
            $vacancies = $data['results']['vacancies'] ?? [];

            if (empty($vacancies)) {
                $this->warn('Вакансии не найдены');
                return Command::SUCCESS;
            }

            $this->info("Получено записей: " . count($vacancies));

            foreach ($vacancies as $item) {
                if ($count >= $limit) break;

                $vacancyData = $item['vacancy'] ?? [];
                
                if (empty($vacancyData)) {
                    $count++;
                    continue;
                }

                $title = $this->sanitize($vacancyData['job-name'] ?? '');
                $company = $this->sanitize($vacancyData['company']['name'] ?? '');
                $city = $this->sanitize($vacancyData['region']['name'] ?? '');
                $url = $vacancyData['vac_url'] ?? 'https://trudvsem.ru';
                $description = $this->sanitize($vacancyData['duty'] ?? '');
                
                if (empty($title) || empty($city)) {
                    $count++;
                    continue;
                }

                $salaryMin = $vacancyData['salary_min'] ?? rand(70000, 100000);
                $salaryMax = $vacancyData['salary_max'] ?? ($salaryMin + rand(40000, 80000));

                $hash = md5($title . $company . $city . 'TRUDVSEM');
                if (Vacancy::where('dedup_hash', $hash)->exists()) {
                    $count++;
                    continue;
                }

                Vacancy::create([
                    'source_id' => $source->id,
                    'dedup_hash' => $hash,
                    'title' => substr($title, 0, 255),
                    'description' => substr($description, 0, 2000),
                    'salary_min' => (int)$salaryMin,
                    'salary_max' => (int)$salaryMax,
                    'currency' => 'RUB',
                    'city' => $city,
                    'url' => $url,
                    'employer_id' => null,
                    'is_moderated' => false,
                    'published_at' => now(),
                ]);

                $added++;
                $count++;
                $this->info("Добавлено: {$title} ({$city})");
            }

            $this->info("Готово. Добавлено: {$added}");

        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
            Log::error('Trudvsem Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function sanitize($text)
    {
        if (empty($text)) return '';
        
        $text = iconv('UTF-8', 'ASCII//IGNORE', $text) ?: '';
        
        if (empty($text)) {
          
            $text = preg_replace('/[^\p{L}\p{N}\s\-\,\.\:\(\)]/u', '', $text);
        }
        
        return trim($text);
    }
}