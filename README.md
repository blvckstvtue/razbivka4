# Lion Developments Portfolio

Брутално портфолио за Lion Developments - компания за програмиране, дизайн, игрови сървъри и custom решения.

## 🦁 Характеристики

- **Брутален дизайн** с fire gradient цветове (златно, оранжево, червено)
- **Responsive design** - работи на всички устройства
- **Анимирани ефекти** - smooth animations и hover effects
- **Featured проект** на началната страница
- **Филтриране по категории** на страницата с проекти
- **Лесно управление** чрез config.php файл
- **SEO оптимизирано** с meta tags

## 🚀 Инсталация

1. **Клонирай репото:**
```bash
git clone [your-repo-url]
cd liondevs-portfolio
```

2. **Настрой уеб сървър:**
   - Apache/Nginx с PHP 7.4+ поддръжка
   - Постави файловете в web root директорията

3. **Отвори в браузър:**
```
http://localhost/your-project-folder
```

## ⚙️ Конфигурация

Всички настройки се правят в `config.php` файла:

### Основни настройки на компанията
```php
$site_config = [
    'company_name' => 'Lion Developments',
    'company_short' => 'LionDevs',
    'tagline' => 'Unleashing Digital Excellence',
    'description' => 'Описание на компанията...',
    'email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    'address' => 'София, България'
];
```

### Социални мрежи
```php
$social_links = [
    'discord' => 'https://discord.gg/liondevs',
    'github' => 'https://github.com/liondevs',
    'linkedin' => 'https://linkedin.com/company/liondevs',
    // ... още платформи
];
```

## 📁 Управление на проекти

### Добавяне на нов проект

В `config.php`, добави нов елемент в `$projects` масива:

```php
[
    'id' => 6, // Уникален ID
    'title' => 'Име на проекта',
    'description' => 'Описание на проекта...',
    'category' => 'Web Development', // Категория
    'technologies' => ['PHP', 'MySQL', 'JavaScript'], // Технологии
    'image' => 'images/project6.jpg', // Път към снимка
    'status' => 'completed', // 'completed' или 'in_progress'
    'client' => 'Име на клиент',
    'date' => '2024-04-15', // Дата във формат YYYY-MM-DD
    'featured' => false, // true ако искаш да се показва на началната страница
    'github' => 'https://github.com/user/repo', // GitHub линк (опционален)
    'demo' => 'https://demo.example.com' // Demo линк (опционален)
]
```

### Задаване на featured проект

За да зададеш кой проект да се показва на началната страница:

1. Отвори `config.php`
2. Намери проекта, който искаш да бъде featured
3. Промени `'featured' => true`
4. Убедете се, че другите проекти имат `'featured' => false`

**Важно:** Само един проект трябва да има `featured => true`!

### Категории на проекти

Категориите се генерират автоматично от проектите. Поддържани категории:
- Web Development
- Game Development
- UI/UX Design
- Bot Development
- Server Management
- Custom Solutions

### Снимки на проекти

1. Качи снимките в папката `images/`
2. Препоръчителни размери: 800x600px или по-големи
3. Форматите PNG, JPG, WEBP са поддържани
4. Използвай описателни имена: `project-name.jpg`

## 🎨 Персонализиране на дизайна

### Цветова схема

В CSS файловете можеш да промениш цветовете в `:root` секцията:

```css
:root {
    --primary-gold: #FFD700;
    --primary-orange: #FF6B35;
    --dark-bg: #0a0a0a;
    --darker-bg: #000000;
    /* ... още цветове */
}
```

### Шрифтове

Използвани шрифтове:
- **Orbitron** - за заглавия и лого (sci-fi стил)
- **Rajdhani** - за основен текст (модерен, четлив)

### Анимации

Можеш да настроиш скоростта на анимациите в CSS:
- `transition: all 0.3s ease` - hover ефекти
- `animation: bgShift 20s ease-in-out infinite` - background анимация

## 📱 Responsive дизайн

Портфолиото е напълно responsive и работи на:
- Desktop (1400px+)
- Tablet (768px - 1399px)
- Mobile (до 767px)

## 🔧 Функционалности

### Начална страница (index.php)
- Hero секция с анимирано заглавие
- Featured проект с детайли
- Секция с услуги
- Контакт информация

### Страница с проекти (projects.php)
- Grid layout с всички проекти
- Филтриране по категории
- Hover ефекти и анимации
- Responsive дизайн

### Навигация
- Fixed navbar с blur ефект
- Smooth scrolling
- Active state индикатори

## 🌟 SEO оптимизация

- Semantic HTML5 структура
- Meta description и keywords
- Open Graph tags (готови за добавяне)
- Structured data (готови за добавяне)
- Fast loading times

## 🛠️ Техническа информация

### Изисквания
- PHP 7.4+
- Уеб сървър (Apache/Nginx)
- Модерен браузър с CSS Grid поддръжка

### Файлова структура
```
/
├── index.php              # Начална страница
├── projects.php           # Страница с проекти
├── config.php             # Конфигурационен файл
├── images/                # Папка със снимки
├── README.md              # Този файл
└── example/               # Example файлове (може да се изтрие)
```

### Browser поддръжка
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## 📞 Поддръжка

За въпроси и проблеми:
- Email: contact@liondevs.com
- GitHub Issues: [Създай issue](https://github.com/your-repo/issues)

## 🚀 Deployment

### На shared hosting:
1. Качи всички файлове във public_html папката
2. Настрой config.php с правилните данни
3. Качи снимките в images/ папката

### На VPS/Dedicated:
1. Настрой Apache/Nginx virtual host
2. Копирай файловете в web директорията
3. Настрой SSL сертификат (препоръчително)

## 📝 Changelog

### v1.0.0 (2024-12-28)
- Първоначална версия
- Брутален fire дизайн
- Responsive layout
- Project management система
- Featured project функционалност
- Category filtering

---

**Направено с ❤️ от Lion Developments**

*Unleashing Digital Excellence*