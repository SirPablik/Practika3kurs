<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vacancy->title }} — СтажОК</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Шапка -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">СтажОК 🚀</a>
            <a href="/" class="text-gray-600 hover:text-blue-600">← Назад к списку</a>
        </div>
    </nav>

    <!-- Контент -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            
            <!-- Заголовок -->
            <h1 class="text-3xl font-bold text-gray-800">{{ $vacancy->title }}</h1>
            
            <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                <span> {{ $vacancy->employer->company_name ?? 'Компания не указана' }}</span>
                <span> {{ $vacancy->city }}</span>
                <span> {{ $vacancy->published_at->format('d.m.Y') }}</span>
            </div>

            <!-- Зарплата -->
            <div class="mt-6 p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">
                    {{ number_format($vacancy->salary_min) }} - {{ number_format($vacancy->salary_max) }} ₽
                </div>
                <div class="text-sm text-gray-500">до вычета налогов</div>
            </div>

            <!-- Теги -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Требования:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($vacancy->tags as $tag)
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 text-sm rounded-full">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Описание -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Описание вакансии:</h3>
                <div class="prose max-w-none text-gray-600">
                    {{ $vacancy->description }}
                </div>
            </div>

            <!-- Кнопка отклика -->
            <div class="mt-8 pt-6 border-t">
                <a href="{{ $vacancy->url }}" target="_blank" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Откликнуться на вакансию →
                </a>
            </div>

        </div>
    </main>

</body>
</html>
