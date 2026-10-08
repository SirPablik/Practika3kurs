<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>СтажОК — Агрегатор вакансий</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Шапка с меню -->
<nav class="bg-white shadow-sm border-b mb-8">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-blue-600">СтажОК </h1>
            <p class="text-sm text-gray-500">Агрегатор IT-вакансий для студентов</p>
        </div>
        <div class="flex gap-4">
            <a href="/" class="text-gray-600 hover:text-blue-600">Вакансии</a>
            <a href="/api/vacancies" target="_blank" class="text-gray-600 hover:text-blue-600">API</a>
            <a href="http://localhost:8025" target="_blank" class="text-gray-600 hover:text-blue-600">Почта</a>
        </div>
    </div>
</nav>
    <!-- Поиск и фильтры -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <form action="/" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <!-- Поиск по названию -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="PHP, Python..." 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Город -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Город</label>
            <input type="text" name="city" value="{{ request('city') }}" 
                   placeholder="Иркутск, Москва..." 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Мин. зарплата -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ЗП от (₽)</label>
            <input type="number" name="salary_min" value="{{ request('salary_min') }}" 
                   placeholder="100000" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Кнопка -->
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                 Найти
            </button>
        </div>
    </form>

    <!-- Кнопка сброса -->
    @if(request('search') || request('city') || request('salary_min'))
        <div class="mt-4">
            <a href="/" class="text-sm text-gray-500 hover:text-blue-600">
                ✕ Сбросить фильтры
            </a>
        </div>
    @endif
</div>

    <!-- Контент -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Статистика -->
     <!-- Статистика (динамическая из API) -->
<div class="grid grid-cols-3 gap-4 mb-8" id="stats-container">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-3xl font-bold text-blue-600" id="total-vacancies">8</div>
        <div class="text-sm text-gray-500">Вакансий всего</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-3xl font-bold text-green-600" id="verified-percent">100%</div>
        <div class="text-sm text-gray-500">Проверено</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-3xl font-bold text-purple-600" id="active-sources">2</div>
        <div class="text-sm text-gray-500">Источников</div>
    </div>
</div>

<!-- Скрипт для загрузки статистики из API -->
<script>
    fetch('/api/statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-vacancies').textContent = data.data.total_vacancies;
                document.getElementById('active-sources').textContent = data.data.active_sources;
                // Можно добавить расчёт процента проверенных
            }
        })
        .catch(err => console.log('API статистики недоступно'));
</script>

        <!-- Список вакансий -->
        <div class="grid gap-4">
            @forelse($vacancies as $vacancy)
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $vacancy->title }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                🏢 {{ $vacancy->employer->company_name ?? 'Компания не указана' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600">
                                {{ number_format($vacancy->salary_min) }} - {{ number_format($vacancy->salary_max) }} ₽
                            </div>
                            <div class="text-sm text-gray-500"> {{ $vacancy->city }}</div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mt-4 line-clamp-2">{{ $vacancy->description }}</p>
                    
                    <div class="flex gap-2 mt-4">
                        @foreach($vacancy->tags->take(3) as $tag)
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 text-xs rounded-full">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                        <span class="text-xs text-gray-400">
                            {{ $vacancy->published_at->diffForHumans() }}
                        </span>
                        <a href="/vacancies/{{ $vacancy->id }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Подробнее →
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <p>Вакансий пока нет. Запустите сидеры!</p>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $vacancies->links() }}
        </div>
    </main>
</body>
</html>
