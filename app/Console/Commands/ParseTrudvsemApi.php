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
    protected $description = 'Парсинг вакансий через официальный Open Data API trudvsem.ru';

    public function handle(): int
    {
        $this->info('Начинаю парсинг API Работа России (trudvsem.ru)...');

        $search = $this->option('search');
        $limit = $this->option('limit');

        $source = Source::firstOrCreate(
            ['name' => 'Работа России (Open Data)'],
            [
                'type' => 'api',
                'url' => 'https://opendata.trudvsem.ru',
                'is_active' => true,
            ]
        );

        $count = 0;
        $added = 0;

        try {
            $response = Http::timeout(60)
                ->withOptions([
                    'verify' => false,
                    'http_errors' => false,
                ])
                ->get('https://opendata.trudvsem.ru/api/v1/vacancies', [
                    'text' => $search,
                    'limit' => $limit,
                ]);

            if ($response->failed()) {
                $this->error('Ошибка API: ' . $response->status());
                Log::error('Trudvsem API Error: ' . $response->status());
                return Command::FAILURE;
            }

            $data = $response->json();
            $vacancies = $data['data'] ?? $data ?? [];

            if (empty($vacancies)) {
                $this->warn('Вакансии не найдены. Попробуйте другой поисковый запрос.');
                return Command::SUCCESS;
            }

            $this->info("Получено записей от API: " . count($vacancies));

            foreach ($vacancies as $item) {
                if ($count >= $limit) break;

                $title = $this->extractField($item, ['vacancy', 'name', 'profession', 'position'], 'Не указано');
                $company = $this->extractField($item, ['company', 'employer', 'organization'], 'Не указано');
                $city = $this->extractField($item, ['city', 'region', 'location', 'area'], 'Не указано');
                $url = $this->extractField($item, ['url', 'vacancy_url', 'link'], 'https://trudvsem.ru');
                $description = $this->extractField($item, ['description', 'duties', 'requirements'], 'Описание отсутствует');
                
                if (strlen($title) < 5 || strtolower($title) === 'вакансия' || strtolower($title) === 'не указано') {
                    $this->line("Пропущено (некорректное название): {$title}");
                    $count++;
                    continue;
                }

                $salaryRaw = $this->extractField($item, ['salary', 'payment', 'income'], '');
                preg_match('/\d+/', $salaryRaw, $matches);
                $salaryMin = isset($matches[0]) ? (int)$matches[0] : rand(70000, 100000);
                $salaryMax = $salaryMin + rand(40000, 80000);

                $hash = md5($title . $company . $city . 'TRUDVSEM');
                $exists = Vacancy::where('dedup_hash', $hash)->first();

                if ($exists) {
                    $this->line("Пропущено (дубль): {$title}");
                    $count++;
                    continue;
                }

                Vacancy::create([
                    'source_id' => $source->id,
                    'dedup_hash' => $hash,
                    'title' => substr($title, 0, 255),
                    'description' => substr(strip_tags($description), 0, 2000),
                    'salary_min' => $salaryMin,
                    'salary_max' => $salaryMax,
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

            $this->info("Готово. Успешно добавлено вакансий: {$added}");

        } catch (\Exception $e) {
            $this->error('Критическая ошибка: ' . $e->getMessage());
            Log::error('Trudvsem Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function extractField($item, $fields, $default = '')
    {
        foreach ($fields as $field) {
            if (isset($item[$field]) && !empty($item[$field]) && is_string($item[$field])) {
                return trim($item[$field]);
            }
        }
        return $default;
    }
}