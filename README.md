# 🦁 Lion Developments Portfolio

Уникално портфолио за Lion Developments с брутален дизайн и пълна функционалност за управление на проекти.

## 🌟 Характеристики

- **Брутален дизайн** с неонови цветове и анимации
- **Responsive дизайн** за всички устройства
- **Динамично управление на проекти** чрез PHP
- **Избран проект** на началната страница
- **Филтриране по категории** в страницата с проекти
- **Админ панел** за пълно управление
- **Smooth анимации** и ефекти

## 🚀 Инсталация

1. **Копирайте файловете** в директорията на вашия уеб сървър
2. **Настройте permissions** за config.php файла:
   ```bash
   chmod 664 config.php
   ```
3. **Създайте директориите** за изображения (ако не са създадени автоматично):
   ```bash
   mkdir -p images/projects
   mkdir -p uploads
   ```

## 📁 Структура на файловете

```
portfolio/
├── index.php          # Начална страница
├── projects.php       # Страница с всички проекти
├── admin.php          # Админ панел
├── config.php         # Конфигурация и управление на данни
├── images/            # Директория за изображения
│   └── projects/      # Изображения на проектите
├── uploads/           # Качени файлове
└── README.md          # Тази документация
```

## ⚙️ Конфигурация

### Основни настройки

Редактирайте `config.php` за промяна на основните настройки:

```php
$site_config = [
    'site_name' => 'Lion Developments',
    'company_name' => 'Lion Developments',
    'tagline' => 'Превръщаме идеи в реалност',
    'email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    // ...
];
```

### Социални мрежи

```php
'social' => [
    'github' => 'https://github.com/liondevs',
    'linkedin' => 'https://linkedin.com/company/liondevs',
    'facebook' => 'https://facebook.com/liondevs',
    'instagram' => 'https://instagram.com/liondevs'
]
```

## 🎯 Управление на проекти

### Чрез Админ панела

1. Отидете на `/admin.php`
2. Използвайте таба **"Добави Проект"** за нови проекти
3. Използвайте таба **"Управление"** за редактиране/изтриване

### Полета за проект

- **Заглавие*** - Име на проекта
- **Категория*** - Тип проект (Web Development, Game Development, etc.)
- **Клиент*** - За кого е направен проекта
- **Статус** - planned/in-progress/completed
- **Дата на завършване** - Кога е завършен проекта
- **URL на изображение** - Линк към изображение на проекта
- **URL на проекта** - Линк към живия проект
- **GitHub URL** - Линк към GitHub репо
- **Описание*** - Подробно описание
- **Технологии*** - Използвани технологии (разделени със запетая)
- **Избран проект** - Дали да се показва на началната страница

### Програмно управление

Можете да използвате функциите в `config.php`:

```php
// Добавяне на проект
addProject($project_data);

// Редактиране на проект
updateProject($id, $project_data);

// Изтриване на проект
deleteProject($id);

// Задаване на избран проект
setFeaturedProject($id);

// Получаване на всички проекти
$projects = getAllProjects();

// Получаване на избрания проект
$featured = getFeaturedProject();
```

## 🎨 Персонализиране на дизайна

### CSS променливи

Променете цветовете в CSS:

```css
:root {
    --primary: #ff6b35;        /* Основен цвят */
    --accent: #00d4ff;         /* Акцентен цвят */
    --background: #0a0a0a;     /* Фон */
    --surface: #1e1e1e;        /* Повърхности */
    --text-primary: #ffffff;    /* Основен текст */
    /* ... */
}
```

### Шрифтове

Проектът използва:
- **Orbitron** - за заглавия и лого
- **Rajdhani** - за основния текст

## 📱 Responsive дизайн

Сайтът е напълно responsive и работи отлично на:
- 📱 Мобилни устройства
- 📱 Таблети
- 💻 Desktop компютри
- 🖥️ Големи монитори

## 🔧 Технически детайли

### Изисквания

- PHP 7.4+
- Уеб сървър (Apache/Nginx)
- Модерен браузър с поддръжка за CSS Grid и Flexbox

### Използвани технологии

- **PHP** - Backend логика
- **HTML5** - Структура
- **CSS3** - Стилизиране и анимации
- **JavaScript** - Интерактивност
- **Font Awesome** - Икони
- **Google Fonts** - Типография

## 🎭 Анимации и ефекти

- **Fade-in анимации** при скролиране
- **Hover ефекти** на карти и бутони
- **Gradient анимации** за фонове
- **Text glow ефекти** за заглавия
- **Smooth transitions** за всички интерактивни елементи

## 🔒 Сигурност

- **HTML escaping** за предотвратяване на XSS
- **Input validation** за формите
- **CSRF protection** препоръчително за production

## 📝 Добавяне на съдържание

### Изображения

1. Качете изображенията в `images/projects/`
2. Използвайте пълния URL в админ панела
3. Препоръчителен размер: 600x400px
4. Формати: JPG, PNG, WebP

### Категории

Категориите се създават автоматично при добавяне на проекти. Популярни категории:
- Web Development
- Game Development
- Mobile Development
- Design & Branding
- Bot Development
- Custom Solutions

## 🚀 Deployment

### На shared hosting

1. Качете всички файлове чрез FTP
2. Настройте permissions
3. Тествайте функционалността

### На VPS/Dedicated

1. Клонирайте репото
2. Настройте уеб сървъра
3. Конфигурирайте PHP
4. Настройте SSL сертификат

## 🐛 Troubleshooting

### Проблеми с permissions

```bash
chmod 664 config.php
chmod 755 images/
chmod 755 uploads/
```

### Проблеми с изображенията

- Проверете дали директорията `images/projects/` съществува
- Проверете permissions на директорията
- Използвайте пълни URL адреси за външни изображения

### PHP грешки

- Включете error reporting за debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📞 Поддръжка

За въпроси и поддръжка:
- 📧 Email: contact@liondevs.com
- 🐙 GitHub: [github.com/liondevs](https://github.com/liondevs)

## 📄 Лиценз

Този проект е създаден за Lion Developments. Всички права запазени.

---

**Направено с ❤️ от Lion Developments**