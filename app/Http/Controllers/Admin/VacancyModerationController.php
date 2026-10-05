<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VacancyModerationController extends Controller
{
    /**
     * Список вакансий на модерации
     */
    public function index(): View
    {
        $vacancies = Vacancy::with('tags', 'source')
            ->where('is_moderated', false)
            ->latest()
            ->paginate(20);

        return view('admin.moderation.index', compact('vacancies'));
    }

    /**
     * Одобрить вакансию
     */
    public function approve(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->update(['is_moderated' => true]);
        return redirect()->back()->with('success', 'Вакансия одобрена!');
    }

    /**
     * Отклонить вакансию
     */
    public function reject(Vacancy $vacancy, Request $request): RedirectResponse
    {
        $vacancy->update([
            'is_moderated' => false,
            'moderation_comment' => $request->comment ?? 'Отклонено'
        ]);
        return redirect()->back()->with('success', 'Вакансия отклонена!');
    }
}