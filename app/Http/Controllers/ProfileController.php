<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\Vacancy;


class ProfileController extends Controller
{
    /**
     * Главная страница личного кабинета
     */
    public function index(): View
    {
        $user = auth()->user();
        $savedVacancies = $user->savedVacancies()->with('tags')->latest()->paginate(10);
        $subscriptions = $user->subscriptions()->latest()->get();

        return view('profile.index', compact('user', 'savedVacancies', 'subscriptions'));
    }

    /**
     * Страница редактирования профиля
     */
    public function edit(): View
    {
        $user = auth()->user();
        $roles = \App\Models\Role::all();

        return view('profile.edit', compact('user', 'roles'));
    }

    /**
     * Обновление профиля
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company_name' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Профиль обновлён!');
    }

    /**
     * Смена пароля
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.index')->with('success', 'Пароль изменён!');
    }

    /**
 * Добавить в избранное
 */
public function addSaved(Vacancy $vacancy): RedirectResponse
{
    $user = auth()->user();
    
    // Проверяем, не сохранена ли уже
    if (!$user->savedVacancies()->where('vacancy_id', $vacancy->id)->exists()) {
        $user->savedVacancies()->attach($vacancy->id);
        return back()->with('success', 'Вакансия добавлена в избранное! ❤️');
    }
    
    return back()->with('info', 'Уже в избранном');
}

    /**
     * Удалить из избранного
     */
    public function removeSaved(Vacancy $vacancy): RedirectResponse
    {
        auth()->user()->savedVacancies()->detach($vacancy->id);
        return back()->with('success', 'Удалено из избранного');
    }
}