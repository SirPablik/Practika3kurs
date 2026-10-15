<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $stats = [
            'vacancies' => \App\Models\Vacancy::where('is_moderated', true)->count(),
            'users' => \App\Models\User::count(),
            'sources' => \App\Models\Source::where('is_active', true)->count(),
            'tags' => \App\Models\Tag::count(),
        ];
        
        return view('about.index', compact('stats'));
    }
}