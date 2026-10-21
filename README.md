# Проверка и агрегация IT-вакансий

Веб-приложение на Laravel, которое собирает IT-вакансии из внешних источников (API и RSS), проводит их через модерацию и отдаёт пользователям через сайт и публичный REST API.

> Учебный проект (практика, 3 курс, ИРКПО).

## Возможности

- **Каталог вакансий** с фильтрами по городу, минимальной зарплате и тегам (технологиям).
- **Парсеры источников:** Open Data API «Работа России» (trudvsem.ru) и RSS-ленты (например, Habr Career).
- **Дедупликация** вакансий по хешу (`dedup_hash`).
- **Модерация:** вакансии из парсеров и от работодателей попадают в очередь и публикуются только после одобрения.
- **Роли:** соискатель, работодатель, администратор.
- **Личный кабинет:** профиль, смена пароля, избранные вакансии.
- **REST API** с токенами Laravel Sanctum (создание вакансий работодателем).
- **Статистика:** количество вакансий, тегов, активных источников, средняя зарплата.
- **Email-уведомления** (шаблон `NewVacancyMail`, тестовая почта через Mailpit).

## Стек

| Компонент | Технология |
|---|---|
| Backend | PHP 8.3+ (в Docker — 8.4), Laravel 13 |
| База данных | PostgreSQL 15 (jsonb, GIN-индексы) |
| Кэш / очереди | Redis, database-драйверы |
| Авторизация API | Laravel Sanctum |
| Frontend | Blade, Vite, Tailwind CSS 4 |
| Почта (dev) | Mailpit |
| Окружение | Docker Compose |

## Быстрый старт (Docker)

### Требования

- Docker и Docker Compose
- Git

### 1. Клонирование

```bash
git clone https://github.com/SirPablik/Practika3kurs.git
cd Practika3kurs
```

### 2. Настройка `.env`

```bash
cp .env.example .env
```

В `.env.example` по умолчанию указан SQLite. Для работы в Docker замените блоки БД, Redis и почты:

```env
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=stagok
DB_USERNAME=sail
DB_PASSWORD=password

REDIS_HOST=redis

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

Переменные `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` используются и контейнером PostgreSQL при первом создании базы.

### 3. Запуск контейнеров

```bash
docker-compose up -d --build
```

Поднимутся контейнеры `laravel.test` (приложение, порт 80), `pgsql` (5432), `redis` (6379) и `mailpit` (SMTP 1025, веб-интерфейс 8025).

### 4. Зависимости и ключ приложения

Если при открытии сайта появляется ошибка `vendor/autoload.php not found`, установите зависимости (занимает 2–5 минут):

```bash
docker-compose run --rm laravel.test composer install
docker-compose exec laravel.test php artisan key:generate
```

### 5. Миграции и тестовые данные

```bash
docker-compose exec laravel.test php artisan migrate
docker-compose exec laravel.test php artisan db:seed
```

Сидеры создают роли, 10 тегов, 3 источника, 8 тестовых вакансий и двух пользователей:

| Роль | Email | Пароль |
|---|---|---|
| Работодатель | `employer@test.com` | `password` |
| Администратор | `admin@test.com` | `password` |

### 6. (Опционально) Сборка фронтенда

```bash
npm install
npm run build
```

### 7. Проверка

| URL | Что должно открыться |
|---|---|
| http://localhost | Главная, список вакансий |
| http://localhost/api/vacancies | JSON со списком вакансий |
| http://localhost/api/statistics | JSON со статистикой |
| http://localhost/admin/moderation | Очередь модерации (нужна авторизация) |
| http://localhost:8025 | Mailpit — перехваченные письма |

## Локальный запуск без Docker

Требуется PHP 8.3+, Composer, Node.js и PostgreSQL (либо оставить SQLite из `.env.example`).

```bash
composer setup        # install, .env, key:generate, migrate, npm install, build
php artisan db:seed
composer dev          # dev-сервер
```

## Консольные команды

| Команда | Описание |
|---|---|
| `php artisan parse:trudvsem --search=Врач --limit=10` | Импорт вакансий из Open Data API trudvsem.ru |
| `php artisan parse:rss --limit=5` | Импорт вакансий из активного RSS-источника |
| `php artisan api:generate-token {email}` | Выпуск API-токена пользователю (показывается один раз) |

Импортированные вакансии получают `is_moderated = false` и появляются на сайте только после одобрения в админке.

## REST API

Базовый префикс: `/api`. Ошибки для `/api/*` всегда возвращаются в JSON.

### Публичные эндпоинты

| Метод | URL | Описание |
|---|---|---|
| GET | `/api/vacancies` | Список одобренных вакансий, 20 на страницу |
| GET | `/api/vacancies/{id}` | Детали вакансии |
| GET | `/api/statistics` | Сводная статистика |

Параметры фильтрации `/api/vacancies`:

| Параметр | Пример | Смысл |
|---|---|---|
| `city` | `Иркутск` | Точное совпадение города |
| `salary_min` | `100000` | Минимальная нижняя граница зарплаты |
| `tags` | `1,2,5` | ID тегов через запятую |
| `page` | `2` | Номер страницы |

### Защищённые эндпоинты

Требуют заголовок `Authorization: Bearer <token>`. Токен выдаёт команда `api:generate-token`.

| Метод | URL | Описание |
|---|---|---|
| POST | `/api/vacancies` | Создание вакансии (только роль `employer`), уходит на модерацию |

Пример:

```bash
curl -X POST http://localhost/api/vacancies \
  -H "Authorization: Bearer <token>" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Laravel Developer",
    "description": "Разработка backend на Laravel",
    "city": "Иркутск",
    "salary_min": 120000,
    "salary_max": 200000,
    "tags": [1, 2]
  }'
```

Ответ `201` содержит созданную вакансию и сообщение о том, что она ожидает модерации.

## Страницы сайта

| URL | Назначение |
|---|---|
| `/` | Каталог вакансий |
| `/vacancies/{id}` | Страница вакансии |
| `/about` | О проекте |
| `/login`, `/register` | Вход и регистрация |
| `/profile`, `/profile/edit` | Личный кабинет (после входа) |
| `/admin/moderation` | Модерация вакансий (одобрить / отклонить с комментарием) |

## База данных

Основные таблицы (миграции в `database/migrations`, дамп в `database/schema.sql`):

| Таблица | Назначение |
|---|---|
| `roles` | Роли: `seeker`, `employer`, `admin` |
| `users` | Пользователи (`role_id`, `company_name`, `is_active`) |
| `sources` | Источники вакансий (`type`: api / rss / html, `config` jsonb) |
| `tags` | Технологии и их синонимы (`aliases` jsonb, GIN-индекс) |
| `vacancies` | Вакансии: зарплата, город, `dedup_hash`, `is_moderated`, `moderation_comment` |
| `vacancy_tag` | Связь вакансий и тегов (многие ко многим) |
| `saved_vacancies` | Избранное пользователей |
| `subscriptions` | Подписки на фильтры (`filters` jsonb, `last_notified_at`) |

Служебные таблицы Laravel: `sessions`, `cache`, `jobs`, `personal_access_tokens`.

## Структура проекта

```
app/
  Console/Commands/     парсеры (ParseRssFeed, ParseTrudvsemApi), GenerateApiToken
  Http/Controllers/     web-контроллеры; Api/, Admin/, Auth/
  Http/Resources/       VacancyResource
  Mail/                 NewVacancyMail
  Models/               User, Role, Vacancy, Source, Tag, Subscription
database/
  migrations/ seeders/ schema.sql
resources/views/        Blade-шаблоны (vacancies, auth, profile, admin, emails, about)
routes/                 web.php, api.php, console.php
docker-compose.yml      laravel.test, pgsql, redis, mailpit
Dockerfile              PHP 8.4 + расширения PostgreSQL
```

## Известные ограничения

- Маршруты `/admin/moderation` защищены только проверкой входа (`auth`), без проверки роли администратора.
- Для источников без данных о зарплате парсеры подставляют случайные значения (`rand`), поэтому статистика зарплат для импортированных вакансий приблизительная.
- Создание вакансии через API всегда привязывает её к источнику с `id = 1`.
- Маршруты сохранения вакансий и подписок в API закомментированы и пока не реализованы.

## Лицензия

Проект построен на Laravel, распространяемом по лицензии [MIT](https://opensource.org/licenses/MIT).
