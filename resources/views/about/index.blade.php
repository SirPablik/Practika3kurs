<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О проекте — СтажОК</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Шапка -->
    <nav class="bg-white shadow-sm border-b mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="/" class="text-2xl font-bold text-blue-600">СтажОК </a>
                <a href="/" class="text-gray-600 hover:text-blue-600 transition">← На главную</a>
            </div>
            <div class="flex gap-4 items-center">
                @auth
                    <a href="{{ route('profile.index') }}" class="text-gray-600 hover:text-blue-600">Кабинет</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login.show') }}" class="text-gray-600 hover:text-blue-600">Войти</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Контент -->
    <main class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white p-10 rounded-lg shadow-lg">
            
            <!-- Заголовок -->
            <h1 class="text-4xl font-bold text-blue-600 mb-6">О проекте СтажОК</h1>
            
            <div class="prose max-w-none text-gray-700 space-y-6">
                
                <!-- Описание -->
                <p class="text-lg leading-relaxed">
                    <strong>СтажОК</strong> — это агрегатор IT-вакансий для студентов и начинающих специалистов.
                    Мы собираем вакансии с разных источников, убираем дубли и предоставляем удобный поиск с фильтрами.
                </p>

                <!-- Цель -->
                <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-600">
                    <h2 class="text-2xl font-bold text-blue-800 mb-3">🎯 Цель проекта</h2>
                    <p class="text-blue-900">
                        Помочь студентам найти первую работу в IT, собрав все вакансии в одном месте 
                        с удобным поиском, фильтрами и уведомлениями о новых предложениях.
                    </p>
                </div>
                
                <!-- Стек технологий -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">🛠 Технологический стек</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="font-bold text-blue-600">Backend</div>
                            <div class="text-gray-700">Laravel 13 + PHP 8.4</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="font-bold text-green-600">База данных</div>
                            <div class="text-gray-700">PostgreSQL 15</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="font-bold text-purple-600">Кэш</div>
                            <div class="text-gray-700">Redis</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="font-bold text-red-600">Frontend</div>
                            <div class="text-gray-700">Blade + Tailwind CSS</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="font-bold text-yellow-600">API</div>
                            <div class="text-gray-700">Laravel Sanctum</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="font-bold text-cyan-600">Docker</div>
                            <div class="text-gray-700">4 контейнера</div>
                        </div>
                    </div>
                </div>
                
                <!-- Статистика -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">📊 Статистика проекта</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-blue-600">{{ $stats['vacancies'] }}</div>
                            <div class="text-sm text-gray-600 mt-2">Вакансий</div>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-green-600">{{ $stats['users'] }}</div>
                            <div class="text-sm text-gray-600 mt-2">Пользователей</div>
                        </div>
                        <div class="bg-purple-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-purple-600">{{ $stats['sources'] }}</div>
                            <div class="text-sm text-gray-600 mt-2">Источников</div>
                        </div>
                        <div class="bg-orange-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-orange-600">{{ $stats['tags'] }}</div>
                            <div class="text-sm text-gray-600 mt-2">Тегов</div>
                        </div>
                    </div>
                </div>
                
                <!-- Функционал -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">✅ Реализованный функционал</h2>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Поиск и фильтрация вакансий</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Авторизация и регистрация пользователей</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Личный кабинет с избранным</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Админ-панель для модерации</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>API с токенной авторизацией</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Парсинг вакансий (RSS + HH.ru)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Email уведомления</span>
                        </li>
                    </ul>
                </div>

                <!-- Контакты -->
                <div class="border-t pt-6 mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">📬 Контакты</h2>
                    <p class="text-gray-700">
                        Репозиторий проекта: 
                        <a href="https://github.com/SirPablik/Practika3kurs" class="text-blue-600 hover:underline" target="_blank">
                            GitHub
                        </a>
                    </p>
                    <p class="text-gray-500 text-sm mt-4">
                        Проект разработан в рамках технической практики 3 курса.
                    </p>
                </div>
            </div>
        </div>
    </main>
    <!-- Футер -->
<footer class="bg-white border-t mt-16">
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="text-center text-sm text-gray-500">
            <p>© 2024 СтажОК. Все права защищены.</p>
            <p class="mt-2">
                <a href="/" class="text-blue-600 hover:underline">На главную</a> • 
                <a href="/api/vacancies" target="_blank" class="text-blue-600 hover:underline">API</a>
            </p>
        </div>
    </div>
</footer>
</body>
</html>