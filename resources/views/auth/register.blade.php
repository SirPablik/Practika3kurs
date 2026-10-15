<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация — СтажОК</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-8">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Регистрация</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Имя</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Роль</label>
                <select name="role_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="1">Соискатель</option>
                    <option value="2">Работодатель</option>
                </select>
            </div>
            <div class="mb-4" id="company-field" style="display:none;">
                <label class="block text-gray-700 mb-2">Название компании</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Пароль</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Подтверждение пароля</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Зарегистрироваться
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Уже есть аккаунт? <a href="{{ route('login.show') }}" class="text-blue-600 hover:underline">Войти</a>
        </p>
    </div>

    <script>
        document.querySelector('select[name="role_id"]').addEventListener('change', function() {
            document.getElementById('company-field').style.display = this.value == '2' ? 'block' : 'none';
        });
    </script>
</body>
</html>