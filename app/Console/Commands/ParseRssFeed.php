<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vacancy;
use App\Models\Source;
use Illuminate\Support\Facades\Log;

class ParseRssFeed extends Command
{
    protected $signature = 'parse:rss {--limit=5}';
    protected $description = 'Парсинг вакансий из RSS-ленты (нативный PHP)';

    public function handle(): int
    {
        $this->info(' Начинаю парсинг RSS...');

        $source = Source::where('type', 'rss')->where('is_active', true)->first();

        if (!$source) {
            $this->error('❌ Нет активного RSS источника!');
            return Command::FAILURE;
        }

        $limit = $this->option('limit');
        $count = 0;
        $added = 0;

        try {
            $rssContent = file_get_contents($source->url);

            if (!$rssContent) {
                $this->error('❌ Не удалось загрузить RSS ленту');
                return Command::FAILURE;
            }

            $rss = simplexml_load_string($rssContent);

            if (!$rss) {
                $this->error('❌ Ошибка парсинга XML');
                return Command::FAILURE;
            }

            foreach ($rss->channel->item as $item) {
                if ($count >= $limit) break;

                $title = (string) $item->title;
                $link = (string) $item->link;
                $description = (string) $item->description;

                $hash = md5($title . 'RSS' . 'Иркутск');

                $exists = Vacancy::where('dedup_hash', $hash)->first();

                if ($exists) {
                    $this->line("⏭️ Пропущено: {$title}");
                    $count++;
                    continue;
                }

                Vacancy::create([
                    'source_id' => $source->id,
                    'dedup_hash' => $hash,
                    'title' => substr($title, 0, 255),
                    'description' => substr(strip_tags($description), 0, 1000),
                    'salary_min' => rand(80000, 120000),
                    'salary_max' => rand(150000, 250000),
                    'currency' => 'RUB',
                    'city' => 'Иркутск',
                    'url' => $link,
                    'is_moderated' => false,
                    'published_at' => now(),
                ]);

                $added++;
                $count++;
                $this->info("✅ Добавлено: {$title}");
            }

            $this->info("━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info(" Готово! Добавлено вакансий: {$added}");
            $this->info("━━━━━━━━━━━━━━━━━━━━━━━━");

        } catch (\Exception $e) {
            $this->error('❌ Ошибка парсинга: ' . $e->getMessage());
            Log::error('RSS Parser failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}