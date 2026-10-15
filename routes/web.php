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
// Авторизация
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login.show');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

// Регистрация
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

// Админка (только для админов)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/moderation', [\App\Http\Controllers\Admin\VacancyModerationController::class, 'index'])->name('admin.moderation');
    Route::post('/admin/moderation/{vacancy}/approve', [\App\Http\Controllers\Admin\VacancyModerationController::class, 'approve'])->name('admin.moderation.approve');
    Route::post('/admin/moderation/{vacancy}/reject', [\App\Http\Controllers\Admin\VacancyModerationController::class, 'reject'])->name('admin.moderation.reject');
});
// Личный кабинет (только для авторизованных)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/saved/{vacancy}', [\App\Http\Controllers\ProfileController::class, 'removeSaved'])->name('profile.saved.remove');
});
// Добавление в избранное
Route::post('/profile/saved/{vacancy}/add', [\App\Http\Controllers\ProfileController::class, 'addSaved'])->name('profile.saved.add');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');