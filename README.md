# Lion Developments Portfolio

🦁 Професионално портфолио за Lion Developments - компания специализирана в програмиране, дизайн, игрови сървъри и custom решения.

## 🚀 Характеристики

- **Брутален модерен дизайн** с тъмна тема и анимации
- **Responsive design** - работи перфектно на всички устройства
- **Project Management System** - лесно управление на проекти чрез config
- **Filtering & Search** - филтриране по категории и търсене
- **Project Modal** - детайлен преглед на всеки проект
- **Featured Project** - избран проект на началната страница
- **Contact Form** - готов за интеграция контактен формуляр
- **SEO Optimized** - готов за търсачки

## 📁 Структура на файловете

```
portfolio/
├── config.php              # Основна конфигурация и проекти
├── index.php              # Начална страница
├── projects.php           # Страница с проекти
├── images/                # Папка за снимки
│   ├── projects/         # Снимки на проекти
│   └── team/            # Снимки на екипа
└── README.md            # Документация
```

## ⚙️ Конфигурация

Всички настройки се намират в `config.php`:

### Основни настройки на компанията
```php
$site_config = [
    'company_name' => 'Lion Developments',
    'company_short' => 'LionDevs',
    'tagline' => 'Превръщаме идеите в реалност',
    // ...
];
```

### Управление на проекти
```php
// Избиране на featured проект за началната страница
$featured_project = 'project_vip_store';

// Добавяне на нов проект
$projects['my_new_project'] = [
    'title' => 'Заглавие на проекта',
    'category' => 'web', // web, mobile, gaming, software, design
    'description' => 'Описание...',
    'image' => 'images/projects/my_project.jpg',
    'technologies' => ['PHP', 'JavaScript', 'MySQL'],
    'status' => 'completed', // completed, in_progress
    'year' => '2024',
    'duration' => '2 месеца',
    'client' => 'Име на клиент',
    'features' => [
        'Функция 1',
        'Функция 2',
        // ...
    ],
    'gallery' => [
        'images/projects/my_project_1.jpg',
        'images/projects/my_project_2.jpg'
    ]
];
```

### Категории проекти
- `web` - Web Development
- `mobile` - Mobile Apps  
- `gaming` - Gaming Solutions
- `software` - Software Development
- `design` - UI/UX Design

## 🎨 Персонализиране на темата

В `config.php` можете да промените цветовете:

```php
$theme = [
    'primary_color' => '#ff6b35',    # Основен цвят
    'secondary_color' => '#1a1a1a',  # Вторичен цвят
    'accent_color' => '#ffd700',     # Accent цвят
    // ...
];
```

## 📱 Responsive Design

Портфолиото е напълно responsive и работи отлично на:
- **Desktop** - Пълен grid layout с анимации
- **Tablet** - Адаптиран layout
- **Mobile** - Single column с мобилно меню

## 🔧 Функционалности

### Начална страница (index.php)
- Hero секция с company info
- Featured project showcase
- Services секция
- Contact form
- Animated elements

### Проекти страница (projects.php)
- Filter по категории
- Live search
- Project grid с hover ефекти
- Detailed project modal
- Direct project links

### Project Management
- Лесно добавяне на проекти в config.php
- Automatic categorization
- Featured project selection
- Gallery support

## 🎯 SEO & Performance

- **Meta tags** - Title, description, keywords
- **Open Graph** - Social media sharing
- **Structured data** готов
- **Optimized images** поддръжка
- **Fast loading** с CSS/JS optimization

## 🚀 Deployment

1. **Upload файловете** на вашия сървър
2. **Редактирайте config.php** с вашите данни
3. **Добавете снимки** в images/projects/
4. **Тествайте** функционалността

### Препоръчвани настройки за сървър:
- PHP 7.4+
- Web server (Apache/Nginx)
- HTTPS enabled

## 📞 Контакт информация

За настройки в `$contact` array:

```php
$contact = [
    'email' => 'info@liondevs.com',
    'phone' => '+359 888 123 456',
    'discord' => 'LionDevs#1337',
    'github' => 'https://github.com/liondevs',
    'linkedin' => 'https://linkedin.com/company/liondevs'
];
```

## 🎨 Дизайн особености

- **Brutalist design** с sharp edges и bold typography
- **Dark theme** с orange/gold accents
- **Glassmorphism** ефекти
- **Particle animations** за hero section
- **Gradient overlays** и glow ефекти
- **Custom scrollbar** стилизиран
- **Hover animations** на всички интерактивни елементи

## 📋 TODO за бъдещи версии

- [ ] Admin panel за управление на проекти
- [ ] Contact form backend integration
- [ ] Blog система
- [ ] Multi-language support
- [ ] Dark/Light mode toggle
- [ ] Project filtering by technologies
- [ ] Client testimonials секция

## 💻 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 📄 License

Този код е създаден специално за Lion Developments. Може да бъде използван и модифициран според нуждите.

---

**Lion Developments** - Превръщаме идеите в реалност 🦁

*Ако имате въпроси или нужда от персонализации, моля свържете се с нас!*