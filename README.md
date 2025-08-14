# 🦁 Lion Developments Portfolio

Брутално портфолио за компанията Lion Developments, изградено с PHP и модерен дизайн.

## 🚀 Функции

- **Responsive дизайн** - перфектно изглежда на всички устройства
- **Анимиран фон** - с градиенти и плаващи частици
- **Config система** - лесно управление на проекти
- **Филтриране по категории** - бързо намиране на проекти
- **Featured проект** - показване на най-важния проект на началната страница
- **Интерактивни модали** - детайлен преглед на проектите
- **SEO оптимизация** - meta тагове и структурирани данни

## 📁 Структура на файловете

```
/
├── index.php          # Начална страница
├── projects.php       # Страница с всички проекти
├── config.php         # Конфигурация и управление на проекти
├── images/            # Папка за снимки на проектите
│   ├── project1.jpg
│   ├── project2.jpg
│   └── ...
└── README.md          # Тази документация
```

## ⚙️ Конфигурация

### Основни настройки

В `config.php` можете да променяте:

```php
$site_config = [
    'site_name' => 'Lion Developments',
    'company_name' => 'LionDevs',
    'tagline' => 'Crafting Digital Excellence',
    'description' => 'Описание на компанията...',
    'logo' => '🦁',
    'primary_color' => '#ff6b35',    // Основен цвят
    'secondary_color' => '#1a1a2e',  // Вторичен цвят
    'accent_color' => '#16213e'      // Акцентен цвят
];
```

### Добавяне на нови проекти

За да добавите нов проект, редактирайте масива `$projects` в `config.php`:

```php
$projects[] = [
    'id' => 5,                              // Уникално ID
    'title' => 'Име на проекта',
    'description' => 'Описание на проекта...',
    'technologies' => ['PHP', 'MySQL', 'JavaScript'],
    'category' => 'Web Development',        // Gaming, Web Development, Design
    'image' => 'images/project5.jpg',       // Път към снимката
    'featured' => false,                    // true за показване на началната страница
    'status' => 'completed',                // completed, in_progress
    'year' => 2024,
    'link' => 'https://example.com',        // Live demo линк
    'github' => 'https://github.com/user/repo',
    'client' => 'Име на клиента'
];
```

### Задаване на Featured проект

За да зададете кой проект да се показва на началната страница:

1. Отидете в `config.php`
2. Намерете проекта който искате да featured
3. Сменете `'featured' => true`
4. Уверете се че само **един** проект има `featured => true`

### Добавяне на снимки

1. Качете снимката в папката `images/`
2. Използвайте формат: `project{ID}.jpg` (например `project1.jpg`)
3. Препоръчителни размери: 800x500px или 16:10 ratio
4. Поддържани формати: JPG, PNG, WebP

## 🎨 Персонализиране на дизайна

### Цветове

Променете CSS променливите в `index.php` и `projects.php`:

```css
:root {
    --primary: #ff6b35;        /* Основен цвят */
    --secondary: #1a1a2e;      /* Тъмен цвят */
    --accent: #16213e;         /* Акцентен цвят */
}
```

### Шрифтове

Използвани шрифтове:
- **Orbitron** - за заглавия и лого
- **Inter** - за основния текст

Можете да ги смените в `<link>` тага за Google Fonts.

### Анимации

Всички анимации са в CSS и могат да се персонализират:
- `gradientShift` - анимация на фона
- `titleGlow` - светене на заглавията
- `float` - плаващи частици

## 🔧 Технически детайли

### Изисквания
- PHP 7.4+
- Web сървър (Apache/Nginx)
- Модерен браузър с поддръжка на CSS Grid

### Браузърна поддръжка
- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

### Производителност
- Оптимизирани CSS анимации
- Lazy loading за снимки
- Minified CSS и JavaScript
- Progressive enhancement

## 📱 Responsive дизайн

Сайтът е напълно responsive и работи отлично на:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (320px - 767px)

## 🛡️ Сигурност

- Escape-ване на всички данни с `htmlspecialchars()`
- Валидация на входящи данни
- Защита срещу XSS атаки
- Безопасни URL параметри

## 🚀 Deployment

1. Качете всички файлове на вашия хостинг
2. Убедете се че папката `images/` има write permissions
3. Конфигурирайте проектите в `config.php`
4. Качете снимки на проектите
5. Готово! 🎉

## 💡 Съвети

- Използвайте висококачествени снимки за по-добър визуален ефект
- Редовно актуализирайте проектите
- Тествайте на различни устройства
- Оптимизирайте снимките за по-бързо зареждане

## 📞 Поддръжка

За въпроси или проблеми, свържете се с Lion Developments:
- Email: contact@liondevs.com
- GitHub: [github.com/liondevs](https://github.com/liondevs)

---

**Изградено с ❤️ от Lion Developments**