# Lion Developments - Portfolio Website

Уникално портфолио за **Lion Developments** с брутален дизайн, PHP backend и лесно управление на проекти.

## 🚀 Особености

- **Брутален дизайн** с neon цветове и анимации
- **PHP конфигурация** за лесно управление на проекти
- **Featured проект** на главната страница
- **Филтриране и търсене** на проекти
- **Admin панел** за управление
- **Responsive дизайн** за всички устройства
- **Animated елементи** и particles система

## 📁 Структура на проекта

```
/
├── index.php          # Главна страница
├── projects.php       # Страница с всички проекти
├── admin.php          # Admin панел (парола: liondevs2024)
├── config.php         # Конфигурация на проектите
├── assets/
│   └── projects/      # Снимки на проекти
└── README.md          # Този файл
```

## ⚙️ Инсталация

1. **Копирай файловете** в твоя web server (Apache/Nginx с PHP)
2. **Създай папката** `assets/projects/` ако не съществува
3. **Качи снимки** на проектите в `assets/projects/`
4. **Отвори** `index.php` в браузъра

## 🎯 Как да добавиш проекти

### Метод 1: Чрез Admin панел (препоръчително)
1. Отиди на `admin.php`
2. Въведи парола: `liondevs2024`
3. Попълни формата за нов проект
4. Качи снимка
5. Натисни "Добави Проект"

### Метод 2: Директно в config.php
Отвори `config.php` и добави нов проект в `$projects` масива:

```php
'project-id' => [
    'id' => 'project-id',
    'title' => 'Заглавие на проекта',
    'category' => 'Категория',
    'description' => 'Кратко описание',
    'long_description' => 'Подробно описание',
    'technologies' => ['PHP', 'JavaScript', 'MySQL'],
    'image' => 'project-image.jpg',
    'featured' => false,
    'completed' => true,
    'year' => '2024',
    'client' => 'Име на клиента',
    'demo_url' => null,
    'github_url' => null,
    'status' => 'live' // live, development, delivered
],
```

## ⭐ Задаване на Featured проект

За да зададеш кой проект да се показва на главната страница:

1. **В Admin панела:** Използвай "Featured Проект" секцията
2. **В config.php:** Промени `featured_project_id` в `$portfolio_config`

```php
$portfolio_config = [
    'featured_project_id' => 'project-id-here',
    // ...
];
```

## 🖼️ Управление на снимки

### Качване на снимки:
1. Снимките се записват в `assets/projects/`
2. Поддържани формати: JPG, PNG, WebP, GIF
3. Препоръчителен размер: 800x600px или по-голям
4. Автоматично се генерират placeholder снимки ако файлът липсва

### Именуване:
- Admin панелът автоматично именува файловете
- За ръчно качване: използвай descriptive имена как `project-name.jpg`

## 🎨 Персонализация

### Промяна на цветове:
Редактирай CSS переменните в `index.php` и `projects.php`:

```css
:root {
    --neon-cyan: #00ffff;      /* Основен цвят */
    --neon-purple: #8a2be2;    /* Вторичен цвят */
    --neon-orange: #ff6600;    /* Accent цвят */
    /* ... */
}
```

### Промяна на информация за компанията:
В `config.php` редактирай `$company_config`:

```php
$company_config = [
    'name' => 'Lion Developments',
    'tagline' => 'Creating Digital Excellence',
    'description' => 'Твоето описание тук',
    'email' => 'твоя@email.com',
    'founded' => '2024'
];
```

## 🔒 Сигурност

### Admin парола:
**ВАЖНО!** Промени admin паролата в `admin.php`:

```php
$admin_password = 'твоята-сигурна-парола';
```

### За продукция:
- Използвай HTTPS
- Добави database storage вместо config файлове
- Имплементирай session security
- Добави rate limiting

## 📱 Responsive дизайн

Сайтът автоматично се адаптира за:
- **Desktop** (1200px+)
- **Tablet** (768px - 1199px)  
- **Mobile** (до 767px)

## 🎭 Анимации и ефекти

- **Particles система** - floating частици в background
- **Neon glow ефекти** на hover
- **Scroll animations** - елементи се появяват при скрол
- **Gradient borders** с rotating анимация
- **Ripple ефекти** при клик на проекти

## 🔧 Troubleshooting

### Снимките не се показват:
1. Провери дали папката `assets/projects/` съществува
2. Провери file permissions (755 за папки, 644 за файлове)
3. Провери пътя в config.php

### Admin панелът не работи:
1. Увери се че sessions са enabled в PHP
2. Провери дали паролата е правилна
3. Провери PHP error log

### Проектите не се показват:
1. Провери синтаксиса в config.php
2. Увери се че project ID е уникален
3. Провери PHP error log

## 🎯 Production готовност

За да направиш сайта production-ready:

1. **Database:** Премести проектите от config.php в MySQL/PostgreSQL
2. **CMS:** Направи proper CRUD функционалност в admin панела
3. **Security:** Добави proper authentication система
4. **SEO:** Добави meta tags, sitemap, structured data
5. **Performance:** Добави caching, image optimization
6. **Analytics:** Интегрирай Google Analytics

## 📞 Контакти

За въпроси относно кода или персонализации:
- Email: liondevelopments1337@gmail.com

---

**Направено с ❤️ от Lion Developments**