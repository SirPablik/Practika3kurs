<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\Tag;
use App\Models\Source;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_vacancies' => Vacancy::where('is_moderated', true)->count(),
                'total_tags' => Tag::count(),
                'active_sources' => Source::where('is_active', true)->count(),
                'vacancies_today' => Vacancy::where('is_moderated', true)
                    ->whereDate('created_at', today())
                    ->count(),
                'avg_salary_min' => Vacancy::where('is_moderated', true)
                    ->avg('salary_min') ?? 0,
                'avg_salary_max' => Vacancy::where('is_moderated', true)
                    ->avg('salary_max') ?? 0,
            ],
        ]);
    }
}