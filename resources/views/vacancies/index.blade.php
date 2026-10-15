<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>СтажОК — Агрегатор IT-вакансий</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    <!-- Шапка с меню и авторизацией -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700 mb-8 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">СтажОК 🚀</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Агрегатор IT-вакансий для студентов</p>
            </div>
            <div class="flex gap-4 items-center">
                @auth
                    <span class="text-gray-600 dark:text-gray-300">👤 {{ auth()->user()->name }}</span>
                    <a href="{{ route('profile.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">Кабинет</a>
                    @if(auth()->user()->role->name === 'admin')
                        <a href="/admin/moderation" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">Админка</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login.show') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">Войти</a>
                    <a href="{{ route('register.show') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Регистрация</a>
                @endauth
                
                <!-- Кнопка переключения темы -->
                <button id="theme-toggle" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Переключить тему">
                    🌙
                </button>
                
                <a href="/api/vacancies" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">API</a>
            </div>
        </div>
    </nav>

    <!-- Поиск и фильтры -->
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8 transition-colors duration-300">
            <form action="/" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Поиск по названию -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="PHP, Python..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                <!-- Город -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город</label>
                    <input type="text" name="city" value="{{ request('city') }}" 
                           placeholder="Иркутск, Москва..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                <!-- Фильтр по тегу -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Технология</label>
                    <select name="tag" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="">Все технологии</option>
                        @foreach(\App\Models\Tag::all() as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Мин. зарплата -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ЗП от (₽)</label>
                    <input type="number" name="salary_min" value="{{ request('salary_min') }}" 
                           placeholder="100000" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                <!-- Кнопка -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        🔍 Найти
                    </button>
                </div>
            </form>

            <!-- Кнопка сброса -->
            @if(request('search') || request('city') || request('salary_min') || request('tag'))
                <div class="mt-4">
                    <a href="/" class="text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        ✕ Сбросить фильтры
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Контент -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Статистика (динамическая из API) -->
        <div class="grid grid-cols-3 gap-4 mb-8" id="stats-container">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow transition-colors duration-300">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400" id="total-vacancies">...</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Вакансий всего</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow transition-colors duration-300">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">100%</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Проверено</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow transition-colors duration-300">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400" id="active-sources">...</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Источников</div>
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
                    }
                })
                .catch(err => console.log('API статистики недоступно'));
        </script>

        <!-- Список вакансий -->
        <div class="grid gap-4">
            @forelse($vacancies as $vacancy)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-colors duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $vacancy->title }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                🏢 {{ $vacancy->employer->company_name ?? 'Компания не указана' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($vacancy->salary_min) }} - {{ number_format($vacancy->salary_max) }} ₽
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">📍 {{ $vacancy->city }}</div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 dark:text-gray-300 mt-4 line-clamp-2">{{ $vacancy->description }}</p>
                    
                    <div class="flex gap-2 mt-4">
                        @foreach($vacancy->tags->take(3) as $tag)
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 text-xs rounded-full">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>

                    <!-- НИЖНЯЯ ПАНЕЛЬ КАРТОЧКИ С ИЗБРАННЫМ -->
                    <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-between items-center">
                        <!-- Дата публикации (слева) -->
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $vacancy->published_at->diffForHumans() }}
                        </span>

                        <!-- КНОПКА ИЗБРАННОГО (по центру) -->
                        @auth
                            @if(auth()->user()->savedVacancies->contains($vacancy->id))
                                <!-- Уже в избранном - показываем кнопку удаления -->
                                <form action="/profile/saved/{{ $vacancy->id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition flex items-center gap-1 text-sm font-medium">
                                        💔 Удалить из избранного
                                    </button>
                                </form>
                            @else
                                <!-- Не в избранном - показываем кнопку добавления -->
                                <form action="/profile/saved/{{ $vacancy->id }}/add" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition flex items-center gap-1 text-sm font-medium">
                                        ❤️ В избранное
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login.show') }}" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition flex items-center gap-1 text-sm font-medium" title="Войдите, чтобы сохранить">
                                ❤️ В избранное
                            </a>
                        @endauth

                        <!-- Ссылка на детали (справа) -->
                        <a href="/vacancies/{{ $vacancy->id }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            Подробнее →
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-300">
                    <p class="text-lg">Вакансий по вашему запросу не найдено.</p>
                    <a href="/" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">Сбросить фильтры</a>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        @if($vacancies->hasPages())
            <div class="mt-8">
                {{ $vacancies->links() }}
            </div>
        @endif
    </main>

    <!-- Футер -->
    <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 mt-16 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- О проекте -->
                <div>
                    <h3 class="text-lg font-bold text-blue-600 dark:text-blue-400 mb-3">СтажОК 🚀</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Агрегатор IT-вакансий для студентов и начинающих специалистов.
                    </p>
                </div>

                <!-- Навигация -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Навигация</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Вакансии</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">О проекте</a></li>
                        <li><a href="/api/vacancies" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">API</a></li>
                        @auth
                            <li><a href="{{ route('profile.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Личный кабинет</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Контакты -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Контакты</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li>📧 contact@stagok.local</li>
                        <li>📍 Иркутск, Россия</li>
                        <li>
                            <a href="https://github.com/SirPablik/Practika3kurs" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                GitHub репозиторий →
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Копирайт -->
            <div class="border-t dark:border-gray-700 mt-8 pt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                <p>© 2024 СтажОК. Все права защищены.</p>
                <p class="mt-2">Проект разработан в рамках технической практики 3 курса.</p>
            </div>
        </div>
    </footer>

    <!-- Скрипт переключения тёмной темы -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        // Проверяем сохраненную тему или системные настройки
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
            themeToggleBtn.textContent = '☀️';
        } else {
            htmlElement.classList.remove('dark');
            themeToggleBtn.textContent = '🌙';
        }

        themeToggleBtn.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            if (htmlElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                themeToggleBtn.textContent = '☀️';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggleBtn.textContent = '🌙';
            }
        });
    </script>
</body>
</html>