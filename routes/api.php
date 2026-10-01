<?php

use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

// Публичные endpoints (доступны всем)
Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show']);

// Защищённые endpoints (требуется API токен)
Route::middleware('auth:sanctum')->group(function () {
    // Создание вакансии (только работодатели)
    Route::post('/vacancies', [VacancyController::class, 'store']);
    
    // Сохранение вакансии в избранное (соискатели)
    // Route::post('/vacancies/{vacancy}/save', [VacancyController::class, 'save']);
    
    // Подписки на фильтры
    // Route::apiResource('subscriptions', SubscriptionController::class);
});