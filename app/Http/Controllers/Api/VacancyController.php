<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class VacancyController extends Controller
{
    /**
     * GET /api/vacancies
     * Список вакансий с фильтрацией (ТЗ п. 4.1)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vacancy::query()
            ->with(['source', 'tags', 'employer'])
            ->where('is_moderated', true); // Только одобренные вакансии

        // Фильтр по городу
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Фильтр по минимальной ЗП
        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        // Фильтр по тегам (ID тегов)
        if ($request->filled('tags')) {
            $tagIds = explode(',', $request->tags);
            $query->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds));
        }

        // Сортировка по дате (новые сначала)
        $vacancies = $query->orderBy('published_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => VacancyResource::collection($vacancies),
            'meta' => [
                'total' => $vacancies->total(),
                'per_page' => $vacancies->perPage(),
                'current_page' => $vacancies->currentPage(),
            ],
        ]);
    }

    /**
     * GET /api/vacancies/{id}
     * Детальная информация о вакансии
     */
    public function show(Vacancy $vacancy): JsonResponse
    {
        $vacancy->load(['source', 'tags', 'employer']);

        return response()->json([
            'success' => true,
            'data' => new VacancyResource($vacancy),
        ]);
    }

    /**
     * POST /api/vacancies
     * Создание вакансии (только для работодателей с токеном)
     * ТЗ п. 4.1: ЛК работодателя
     */
    public function store(Request $request): JsonResponse
    {
        // Проверка авторизации по токену
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Provide valid API token.',
            ], 401);
        }

        // Проверка роли (только работодатель)
        if ($user->role->name !== 'employer') {
            return response()->json([
                'success' => false,
                'message' => 'Only employers can create vacancies.',
            ], 403);
        }

        // Валидация данных
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_min' => 'nullable|integer',
            'salary_max' => 'nullable|integer',
            'city' => 'required|string|max:100',
            'url' => 'nullable|url',
            'tags' => 'nullable|array',
        ]);

        // Создаём вакансию
        $vacancy = Vacancy::create([
            'source_id' => 1, // По умолчанию (можно добавить выбор)
            'external_id' => null,
            'dedup_hash' => md5($validated['title'] . $user->company_name . $validated['city']),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'city' => $validated['city'],
            'url' => $validated['url'] ?? null,
            'employer_id' => $user->id,
            'is_moderated' => false, // Требует модерации
            'published_at' => now(),
        ]);

        // Привязываем теги
        if (!empty($validated['tags'])) {
            $vacancy->tags()->sync($validated['tags']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vacancy created successfully. Awaiting moderation.',
            'data' => new VacancyResource($vacancy->load(['source', 'tags', 'employer'])),
        ], 201);
    }
}