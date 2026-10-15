<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование профиля — СтажОК</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <<nav class="bg-white shadow-sm border-b mb-8">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="/" class="text-2xl font-bold text-blue-600">СтажОК </a>
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
    <main class="max-w-2xl mx-auto px-4 py-8">
        
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Редактирование данных -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4"> Редактировать профиль</h2>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Имя</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Компания</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                     Сохранить
                </button>
            </form>
        </div>

        <!-- Смена пароля -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold text-gray-800 mb-4">🔑 Сменить пароль</h2>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Текущий пароль</label>
                    <input type="password" name="current_password" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Новый пароль</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    🔐 Обновить пароль
                </button>
            </form>
        </div>
    </main>
</body>
</html>