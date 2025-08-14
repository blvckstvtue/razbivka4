<?php
// config.php - Конфигурация за Lion Developments портфолио

// Основна информация за сайта
$site_config = [
    'site_name' => 'Lion Developments',
    'company_name' => 'LionDevs',
    'tagline' => 'Crafting Digital Excellence',
    'description' => 'Ние сме компания която се занимава с програмиране, дизайн, игрови сървъри, скриптове и всякакви проекти по изискване на клиенти.',
    'logo' => '🦁',
    'primary_color' => '#ff6b35',
    'secondary_color' => '#1a1a2e',
    'accent_color' => '#16213e'
];

// Проекти в портфолиото
$projects = [
    [
        'id' => 1,
        'title' => 'Gaming Server Network',
        'description' => 'Мащабна мрежа от игрови сървъри с custom модификации и системи за управление на играчи.',
        'technologies' => ['SourcePawn', 'MySQL', 'PHP', 'Linux'],
        'category' => 'Gaming',
        'image' => 'images/project1.jpg',
        'featured' => false,
        'status' => 'completed',
        'year' => 2024,
        'link' => '#',
        'github' => '',
        'client' => 'Gaming Community XYZ'
    ],
    [
        'id' => 2,
        'title' => 'E-Commerce Platform',
        'description' => 'Пълноценна е-търговска платформа с payment gateway интеграция и административен панел.',
        'technologies' => ['PHP', 'MySQL', 'JavaScript', 'Stripe API'],
        'category' => 'Web Development',
        'image' => 'images/project2.jpg',
        'featured' => true, // Този проект ще се показва на началната страница
        'status' => 'completed',
        'year' => 2024,
        'link' => '#',
        'github' => '',
        'client' => 'TechStore BG'
    ],
    [
        'id' => 3,
        'title' => 'Custom Game Plugins',
        'description' => 'Серия от custom плугини за Counter-Strike сървъри включващи VIP системи и специални игрови режими.',
        'technologies' => ['SourcePawn', 'SourceMod', 'SQL'],
        'category' => 'Gaming',
        'image' => 'images/project3.jpg',
        'featured' => false,
        'status' => 'completed',
        'year' => 2023,
        'link' => '#',
        'github' => '',
        'client' => 'Bulgarian Gaming Network'
    ],
    [
        'id' => 4,
        'title' => 'Corporate Website & Branding',
        'description' => 'Цялостен ребрандинг и уеб присъствие за технологична компания включващо лого дизайн и маркетингови материали.',
        'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'Adobe Creative Suite'],
        'category' => 'Design',
        'image' => 'images/project4.jpg',
        'featured' => false,
        'status' => 'completed',
        'year' => 2023,
        'link' => '#',
        'github' => '',
        'client' => 'InnovateTech Ltd'
    ]
];

// Функция за получаване на featured проект
function getFeaturedProject() {
    global $projects;
    foreach ($projects as $project) {
        if ($project['featured']) {
            return $project;
        }
    }
    return null;
}

// Функция за получаване на проекти по категория
function getProjectsByCategory($category = null) {
    global $projects;
    if ($category === null) {
        return $projects;
    }
    return array_filter($projects, function($project) use ($category) {
        return $project['category'] === $category;
    });
}

// Функция за получаване на уникални категории
function getCategories() {
    global $projects;
    $categories = array_unique(array_column($projects, 'category'));
    return array_values($categories);
}

// Функция за получаване на проект по ID
function getProjectById($id) {
    global $projects;
    foreach ($projects as $project) {
        if ($project['id'] == $id) {
            return $project;
        }
    }
    return null;
}

// Навигационно меню
$navigation = [
    'home' => ['title' => 'Начало', 'url' => 'index.php'],
    'projects' => ['title' => 'Проекти', 'url' => 'projects.php'],
    'about' => ['title' => 'За нас', 'url' => '#about'],
    'contact' => ['title' => 'Контакти', 'url' => '#contact']
];

// Социални мрежи
$social_links = [
    'github' => '#',
    'linkedin' => '#',
    'discord' => '#',
    'email' => 'contact@liondevs.com'
];

// SEO настройки
$seo = [
    'meta_description' => 'Lion Developments - Професионални програмни решения, игрови сървъри, дизайн и разработка по ваши изисквания.',
    'meta_keywords' => 'програмиране, игрови сървъри, дизайн, скриптове, плугини, PHP, JavaScript, SourcePawn',
    'og_image' => 'images/og-image.jpg'
];
?>