<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiToken extends Command
{
    protected $signature = 'api:generate-token {email}';
    protected $description = 'Generate API token for a user';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return Command::FAILURE;
        }

        // Создаём токен
        $token = $user->createToken('api-token')->plainTextToken;

        $this->info("API Token for {$email}:");
        $this->line($token);
        $this->warn("Save this token! It won't be shown again.");

        return Command::SUCCESS;
    }
}