<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #2563eb, #1e40af); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .vacancy-title { font-size: 22px; font-weight: bold; color: #1e40af; margin-bottom: 15px; }
        .info { background: #f9fafb; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .info-row { display: flex; justify-content: space-between; margin: 8px 0; }
        .salary { font-size: 24px; color: #059669; font-weight: bold; margin: 20px 0; text-align: center; }
        .button { display: inline-block; background: #2563eb; color: white; padding: 14px 40px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .button:hover { background: #1d4ed8; }
        .footer { background: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 СтажОК</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Новая вакансия по вашей подписке!</p>
        </div>
        
        <div class="content">
            <div class="vacancy-title">{{ $vacancy->title }}</div>
            
            <div class="info">
                <div class="info-row">
                    <span> Компания:</span>
                    <strong>{{ $vacancy->employer->company_name ?? 'Не указана' }}</strong>
                </div>
                <div class="info-row">
                    <span> Город:</span>
                    <strong>{{ $vacancy->city }}</strong>
                </div>
                <div class="info-row">
                    <span>📅 Опубликовано:</span>
                    <strong>{{ $vacancy->published_at->format('d.m.Y') }}</strong>
                </div>
            </div>
            
            <div class="salary">
                💰 {{ number_format($vacancy->salary_min) }} - {{ number_format($vacancy->salary_max) }} ₽
            </div>
            
            <p><strong> Описание:</strong></p>
            <p style="color: #4b5563; line-height: 1.8;">{{ Str::limit($vacancy->description, 400) }}</p>
            
            <div style="text-align: center;">
                <a href="{{ $vacancy->url }}" class="button" target="_blank">Откликнуться на вакансию →</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Вы получили это письмо, потому что подписаны на уведомления СтажОК.</p>
            <p>© 2024 СтажОК. Все права защищены.</p>
            <p style="margin-top: 10px;">
                <a href="{{ url('/profile') }}" style="color: #2563eb;">Управление подписками</a>
            </p>
        </div>
    </div>
</body>
</html>