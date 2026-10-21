<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DebugTrudvsemApi extends Command
{
    protected $signature = 'debug:trudvsem {--search=Врач}';
    protected $description = 'Просмотр сырого ответа от API trudvsem.ru';

    public function handle(): int
    {
        $search = $this->option('search');

        $response = Http::timeout(60)
            ->withOptions(['verify' => false])
            ->get('https://opendata.trudvsem.ru/api/v1/vacancies', [
                'text' => $search,
                'limit' => 3,
            ]);

        if ($response->failed()) {
            $this->error('Ошибка API: ' . $response->status());
            return Command::FAILURE;
        }

        $data = $response->json();
        
        $this->info('=== СТРУКТУРА ОТВЕТА ===');
        $this->info('Ключи верхнего уровня: ' . json_encode(array_keys($data)));
        $this->info('');
        
        if (isset($data['data']) && is_array($data['data'])) {
            $this->info('=== ПЕРВАЯ ВАКАНСИЯ (data[0]) ===');
            $this->info(json_encode($data['data'][0], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        } else {
            $this->info('=== ПЕРВАЯ ВАКАНСИЯ (корень) ===');
            $this->info(json_encode($data[0] ?? $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        return Command::SUCCESS;
    }
}