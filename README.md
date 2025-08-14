# LionDevs Portfolio

Уникално портфолио за компанията **Lion Developments** с брутален дизайн и модерни функционалности.

## 🚀 Функционалности

- **Динамично управление на проекти** чрез config.php
- **Брутален dark дизайн** с неонови ефекти и анимации
- **Респонсивен дизайн** за всички устройства
- **Featured проект** на началната страница
- **Филтриране по категории** в страницата с проекти
- **Интерактивни модали** за детайли на проектите
- **Контактна форма** с валидация
- **Анимации и ефекти** за по-добро потребителско преживяване

## 📁 Файлова структура

```
/
├── index.php          # Главна страница
├── projects.php       # Страница с всички проекти
├── config.php         # Конфигурация на проектите
├── style.css          # CSS стилове
├── script.js          # JavaScript функционалности
├── images/            # Папка с изображения
│   ├── project1.jpg   
│   ├── project2.jpg   
│   ├── project3.jpg   
│   ├── project4.jpg   
│   └── team1.jpg      
└── README.md          # Тази документация
```

## ⚙️ Конфигурация

### Добавяне на нов проект

Редактирайте `config.php` и добавете нов проект в масива `$projects`:

```php
[
    'id' => 5, // Уникално ID
    'title' => 'Име на проекта',
    'description' => 'Кратко описание на проекта',
    'technologies' => ['PHP', 'JavaScript', 'MySQL'],
    'category' => 'web-development', // web-development, game-development, web-design, mobile-development, server-administration
    'image' => 'images/project5.jpg',
    'status' => 'completed', // completed или in-progress
    'featured' => false, // true ако искате да се показва на началната страница
    'date_completed' => '2024-01-15',
    'client' => 'Име на клиента',
    'github_url' => 'https://github.com/user/repo', // Оставете празно ако няма
    'demo_url' => 'https://demo-url.com', // Оставете празно ако няма
    'details' => 'Подробно описание на проекта...'
]
```

### Задаване на featured проект

За да зададете проект като featured (показва се на началната страница):

1. Отворете `config.php`
2. Намерете проекта който искате да бъде featured
3. Променете `'featured' => true`
4. Уверете се че другите проекти имат `'featured' => false`

### Промяна на основни настройки

В `config.php` можете да промените:

```php
$site_config = [
    'site_name' => 'LionDevs',
    'company_name' => 'Lion Developments', 
    'company_description' => 'Вашето описание...',
    'contact_email' => 'your-email@example.com',
    'phone' => '+359 XXX XXX XXX',
    'address' => 'Вашия адрес'
];
```

### Добавяне на нови категории

```php
$project_categories = [
    'your-category' => [
        'name' => 'Име на категорията',
        'icon' => 'fas fa-icon-name', // Font Awesome икона
        'color' => '#your-color'
    ]
];
```

### Добавяне на нови услуги

```php
$services[] = [
    'title' => 'Нова услуга',
    'description' => 'Описание на услугата',
    'icon' => 'fas fa-icon',
    'features' => ['Функция 1', 'Функция 2', 'Функция 3']
];
```

### Добавяне на екип

```php
$team_members[] = [
    'name' => 'Име Фамилия',
    'position' => 'Позиция',
    'description' => 'Описание',
    'image' => 'images/team-member.jpg',
    'skills' => ['Skill 1', 'Skill 2', 'Skill 3']
];
```

## 🎨 Персонализиране на дизайна

### Промяна на цветовете

В `style.css` можете да промените основните цветове:

```css
:root {
    --primary-color: #00ff88;    /* Основен цвят */
    --secondary-color: #ff6b6b;  /* Вторичен цвят */
    --accent-color: #4ecdc4;     /* Акцентен цвят */
    --bg-primary: #0a0a0a;       /* Основен фон */
    --bg-secondary: #111111;     /* Вторичен фон */
}
```

### Промяна на шрифтовете

Променете Google Fonts връзката в HTML файловете:

```html
<link href="https://fonts.googleapis.com/css2?family=YourFont:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
```

И обновете CSS:

```css
body {
    font-family: 'YourFont', sans-serif;
}
```

## 🖼️ Работа с изображения

### Размери на изображенията

- **Проекти**: 500x300px (16:9 съотношение)
- **Екип**: 300x300px (квадратни)
- **Формат**: JPG или PNG
- **Оптимизация**: Препоръчва се компресия за по-бързо зареждане

### Добавяне на нови изображения

1. Качете изображението в папката `images/`
2. Обновете пътя в `config.php`
3. Уверете се че файлът има подходящите права за четене

## 🚀 Deployment

### Изисквания

- PHP 7.4 или по-нова версия
- Уеб сървър (Apache/Nginx)
- Поддръжка на .htaccess (за Apache)

### Стъпки за публикуване

1. Качете всички файлове на вашия сървър
2. Уверете се че папката `images/` има права за писане
3. Настройте виртуален хост да сочи към главната папка
4. Тествайте функционалността

### .htaccess (за Apache)

Създайте `.htaccess` файл с:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^?]*)$ index.php [NC,L,QSA]

# Кеширане на статични файлове
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

## 🔧 Поддръжка

### Чести проблеми

**Изображенията не се зареждат:**
- Проверете пътищата в config.php
- Уверете се че файловете съществуват
- Проверете правата за четене

**Проектите не се показват:**
- Проверете синтаксиса в config.php
- Уверете се че няма грешки в масива
- Проверете за липсващи запетаи

**Стиловете не се прилагат:**
- Проверете пътя към style.css
- Изчистете кеша на браузъра
- Проверете за CSS грешки в конзолата

### Обновления

За да добавите нови функционалности:

1. Редактирайте съответните файлове
2. Тествайте локално
3. Backup на съществуващите файлове
4. Качете обновенията

## 📞 Поддръжка

Ако имате въпроси или проблеми:

- Проверете документацията
- Прегледайте кода за грешки
- Тествайте на локален сървър първо

## 🎯 Съвети за оптимизация

1. **Оптимизирайте изображенията** преди качване
2. **Използвайте CDN** за по-бързо зареждане
3. **Редовно обновявайте** съдържанието
4. **Тествайте** на различни устройства
5. **Следете** Google PageSpeed за производителност

## 📝 Лиценз

Този код е създаден специално за LionDevs и е предназначен за вътрешна употреба.

---

**LionDevs** - Развиваме бъдещето 🦁💻