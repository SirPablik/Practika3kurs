<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index(Request $request)
{
    $query = Vacancy::with('tags')
        ->where('is_moderated', true);

    // Поиск по названию
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Фильтр по городу
    if ($request->filled('city')) {
        $query->where('city', $request->city);
    }

    // Фильтр по минимальной зарплате
    if ($request->filled('salary_min')) {
        $query->where('salary_min', '>=', $request->salary_min);
    }

    $vacancies = $query->latest('published_at')->paginate(10);

    // Сохраняем параметры поиска для пагинации
    $vacancies->appends($request->only('search', 'city', 'salary_min'));

    return view('vacancies.index', compact('vacancies'));
}

    public function show(Vacancy $vacancy)
    {
        $vacancy->load('tags','employer');
        return view('vacancies.show',compact('vacancy'));
    }
}
