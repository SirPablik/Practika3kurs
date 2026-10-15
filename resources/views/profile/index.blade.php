<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет — СтажОК</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Шапка -->
<nav class="bg-white shadow-sm border-b mb-8">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="/" class="text-2xl font-bold text-blue-600">СтажОК 🚀</a>
            <a href="/" class="text-gray-600 hover:text-blue-600 transition">← На главную</a>
        </div>
        <div class="flex gap-4 items-center">
            <span class="text-gray-600">👤 {{ auth()->user()->name }}</span>
            <a href="{{ route('profile.index') }}" class="text-blue-600 hover:underline">Кабинет</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800">Выйти</button>
            </form>
        </div>
    </div>
</nav>
    <!-- Контент -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Левая колонка: Информация о пользователе -->
            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">👤 Профиль</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">Имя:</span>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Email:</span>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Роль:</span>
                            <p class="font-medium">{{ $user->role->display_name ?? 'Не указана' }}</p>
                        </div>
                        @if($user->company_name)
                        <div>
                            <span class="text-gray-500">Компания:</span>
                            <p class="font-medium">{{ $user->company_name }}</p>
                        </div>
                        @endif
                        <div>
                            <span class="text-gray-500">На сайте:</span>
                            <p class="font-medium">{{ $user->created_at->format('d.m.Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block mt-4 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        ✏️ Редактировать
                    </a>
                </div>

                <!-- Статистика -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"> Статистика</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">В избранном:</span>
                            <span class="font-bold text-blue-600">{{ $savedVacancies->total() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Подписок:</span>
                            <span class="font-bold text-green-600">{{ $subscriptions->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка: Избранное -->
            <div class="md:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">❤️ Избранное</h2>
                    
                    @forelse($savedVacancies as $vacancy)
                        <div class="border-b pb-4 mb-4 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-blue-600">
                                        <a href="/vacancies/{{ $vacancy->id }}" class="hover:underline">{{ $vacancy->title }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-500"> {{ $vacancy->employer->company_name ?? 'Компания не указана' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-green-600 font-bold">
                                        {{ number_format($vacancy->salary_min) }} - {{ number_format($vacancy->salary_max) }} ₽
                                    </div>
                                    <div class="text-sm text-gray-500">📍 {{ $vacancy->city }}</div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-2">
                                @foreach($vacancy->tags->take(3) as $tag)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="text-xs text-gray-400">{{ $vacancy->published_at->diffForHumans() }}</span>
                                <form action="{{ route('profile.saved.remove', $vacancy) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        🗑️ Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Нет сохранённых вакансий</p>
                            <a href="/" class="text-blue-600 hover:underline mt-2 inline-block">Перейти к поиску →</a>
                        </div>
                    @endforelse

                    @if($savedVacancies->hasPages())
                        <div class="mt-6">
                            {{ $savedVacancies->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <!-- Футер -->
<footer class="bg-white border-t mt-16">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="text-center text-sm text-gray-500">
            <p>© 2024 СтажОК. Все права защищены.</p>
            <p class="mt-2">
                <a href="/" class="text-blue-600 hover:underline">На главную</a> • 
                <a href="{{ route('about') }}" class="text-blue-600 hover:underline">О проекте</a>
            </p>
        </div>
    </div>
</footer>
</body>
</html>