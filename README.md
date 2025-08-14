# LionDevs Portfolio 🦁

Модерно и брутално портфолио за Lion Developments - професионална компания за програмиране и дизайн.

## 🚀 Възможности

- **Брутален и модерен дизайн** с тъмна тема и ярки акценти
- **Responsive дизайн** - работи на всички устройства
- **Smooth анимации** и интерактивни ефекти
- **Портфолио система** с филтриране по категории
- **Admin панел** за управление на проекти
- **Featured проект** система
- **Contact форма** с валидация
- **SEO оптимизиран**

## 📁 Структура на файловете

```
/
├── index.php              # Начална страница
├── projects.php           # Портфолио страница
├── admin.php             # Admin панел
├── config.php            # Конфигурация
├── css/
│   └── style.css         # Главен CSS файл
├── js/
│   └── main.js           # JavaScript функционалност
└── images/
    ├── projects/         # Изображения на проекти
    └── backgrounds/      # Background изображения
```

## 🎨 Дизайн

Портфолиото използва **брутален** и **модерен** дизайн със следните характеристики:

### Цветова схема
- **Primary**: `#FF6B35` (Ярко оранжево)
- **Secondary**: `#FFE66D` (Жълто)
- **Accent**: `#FF006E` (Розово)
- **Background**: `#0A0A0A` (Тъмно черно)
- **Text**: `#FFFFFF` (Бяло)

### Типография
- **Primary Font**: Inter (Clean и модерен)
- **Accent Font**: Orbitron (Futuristic)

### Ефекти
- Glow ефекти при hover
- Smooth transitions
- Parallax анимации
- Gradient backgrounds
- Box shadows

## ⚙️ Конфигурация

Всички настройки се намират в `config.php`:

### Информация за компанията
```php
$company_config = [
    'name' => 'LionDevs',
    'full_name' => 'Lion Developments',
    'tagline' => 'We Build Digital Dreams',
    'contact_email' => 'contact@liondevs.com',
    // ...
];
```

### Проекти
```php
$portfolio_projects = [
    'featured_project' => 1, // ID на featured проект
    'projects' => [
        // Масив с проекти
    ]
];
```

### Услуги
```php
$services = [
    // Масив с услуги
];
```

## 📊 Admin Panel

### Достъп
- URL: `/admin.php`
- Парола: `liondevs2024`

### Функционалности
- ✅ Добавяне на нови проекти
- ✅ Изтриване на проекти (демо режим)
- ✅ Избор на featured проект
- ✅ Преглед на статистики
- ✅ Управление на настройки

### Tabs
1. **Управление на проекти** - Добавяне/изтриване
2. **Featured проект** - Избор на highlight проект
3. **Настройки** - Преглед на конфигурация

## 🛠️ Инсталация

1. **Изтеглете файловете** в web директория
2. **Настройте web сървър** (Apache/Nginx с PHP)
3. **Персонализирайте** `config.php` според нуждите
4. **Добавете изображения** в `images/projects/`
5. **Отворете** сайта в браузър

### Изисквания
- PHP 7.4+ 
- Web сървър (Apache/Nginx)
- Модерен браузър

## 📱 Responsive дизайн

Сайтът е напълно responsive и работи перфектно на:
- 📱 **Mobile** (320px+)
- 📱 **Tablet** (768px+)
- 💻 **Desktop** (1024px+)
- 🖥️ **Large screens** (1400px+)

## 🎯 SEO оптимизация

- ✅ Meta tags
- ✅ Semantic HTML
- ✅ Open Graph tags
- ✅ Fast loading
- ✅ Mobile-friendly
- ✅ Clean URLs

## 🔧 Персонализация

### Промяна на цветове
Редактирайте CSS variables в `style.css`:
```css
:root {
    --primary: #FF6B35;
    --accent: #FF006E;
    // ...
}
```

### Добавяне на проекти
Редактирайте `$portfolio_projects` в `config.php` или използвайте Admin панела.

### Промяна на информация
Редактирайте `$company_config` в `config.php`.

## 🚀 Features в детайли

### Портфолио система
- Филтриране по категории
- Modal windows за детайли
- Статус badges (Завършен/В процес)
- Tech stack tags
- Demo и GitHub линкове

### Admin панел
- Secured login система
- CRUD операции
- Real-time preview
- Form validation
- Responsive admin interface

### Анимации
- Scroll-triggered animations
- Hover ефекти
- Loading states
- Smooth transitions
- Parallax ефекти

## 🔒 Сигурност

**ВАЖНО**: Текущата admin система е само за демо!

За production използвайте:
- Hash-нати пароли
- Sessions с timeout
- CSRF protection
- SQL injection защита
- Input validation

## 📞 Поддръжка

За въпроси и поддръжка:
- Email: contact@liondevs.com
- Уебсайт: [liondevs.com](https://liondevs.com)

## 📄 Лиценз

Този проект е създаден специално за Lion Developments.

---

**Направено с ❤️ от LionDevs team**