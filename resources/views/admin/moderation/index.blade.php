<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Модерация вакансий — СтажОК</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">Админ-панель 🔧</h1>
            <a href="/" class="text-gray-600 hover:text-blue-600">На сайт →</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-6">Вакансии на модерации ({{ $vacancies->total() }})</h2>

        <div class="grid gap-4">
            @forelse($vacancies as $vacancy)
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold">{{ $vacancy->title }}</h3>
                            <p class="text-sm text-gray-500">Источник: {{ $vacancy->source->name ?? 'Не указан' }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600">
                                {{ number_format($vacancy->salary_min) }} - {{ number_format($vacancy->salary_max) }} ₽
                            </div>
                            <div class="text-sm text-gray-500">📍 {{ $vacancy->city }}</div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mt-4 line-clamp-2">{{ $vacancy->description }}</p>
                    
                    <div class="mt-4 flex gap-2">
                        <form action="/admin/moderation/{{ $vacancy->id }}/approve" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                ✅ Одобрить
                            </button>
                        </form>
                        
                        <form action="/admin/moderation/{{ $vacancy->id }}/reject" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                ❌ Отклонить
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <p>Нет вакансий на модерации! 🎉</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $vacancies->links() }}
        </div>
    </main>
</body>
</html>