<?php

use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

// Главная страница со списком вакансий
Route::get('/', [VacancyController::class, 'index'])->name('vacancies.index');

// Детальная страница вакансии
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');
// Админка - модерация вакансий
Route::prefix('admin/moderation')->name('admin.moderation.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\VacancyModerationController::class, 'index'])->name('index');
    Route::post('/{vacancy}/approve', [\App\Http\Controllers\Admin\VacancyModerationController::class, 'approve'])->name('approve');
    Route::post('/{vacancy}/reject', [\App\Http\Controllers\Admin\VacancyModerationController::class, 'reject'])->name('reject');
});