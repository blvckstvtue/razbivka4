<?php
// config.php - Конфигурация на Lion Developments Portfolio

// Основни настройки на сайта
$site_config = [
    'company_name' => 'Lion Developments',
    'company_short' => 'LionDevs',
    'tagline' => 'Unleashing Digital Excellence',
    'description' => 'Ние сме Lion Developments - компания, която се занимава с програмиране, дизайн, игрови сървъри, скриптове и всякакви проекти по изискване на клиенти.',
    'email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    'address' => 'София, България'
];

// Социални мрежи
$social_links = [
    'discord' => 'https://discord.gg/liondevs',
    'github' => 'https://github.com/liondevs',
    'linkedin' => 'https://linkedin.com/company/liondevs',
    'facebook' => 'https://facebook.com/liondevs',
    'instagram' => 'https://instagram.com/liondevs'
];

// Проекти - тук можеш да добавяш, редактираш и премахваш проекти
$projects = [
    [
        'id' => 1,
        'title' => 'Gaming Server Management System',
        'description' => 'Пълна система за управление на игрови сървъри с VIP магазин, RCON контрол, статистики и автоматизирани плащания.',
        'category' => 'Web Development',
        'technologies' => ['PHP', 'MySQL', 'JavaScript', 'CSS3', 'Stripe API'],
        'image' => 'images/project1.jpg',
        'status' => 'completed',
        'client' => 'Gaming Community',
        'date' => '2024-01-15',
        'featured' => true, // Този проект ще се показва на началната страница
        'github' => 'https://github.com/liondevs/gaming-server-management',
        'demo' => 'https://demo.liondevs.com/gaming-server'
    ],
    [
        'id' => 2,
        'title' => 'E-Commerce Platform',
        'description' => 'Модерна e-commerce платформа с административен панел, управление на продукти, поръчки и интеграция с множество платежни системи.',
        'category' => 'Web Development',
        'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'PayPal API'],
        'image' => 'images/project2.jpg',
        'status' => 'completed',
        'client' => 'Online Retailer',
        'date' => '2024-02-20',
        'featured' => false,
        'github' => '',
        'demo' => 'https://demo.liondevs.com/ecommerce'
    ],
    [
        'id' => 3,
        'title' => 'CS2 Server Plugin Pack',
        'description' => 'Колекция от custom плугини за Counter-Strike 2 сървъри включващи VIP система, магазин за оръжия, статистики и anti-cheat.',
        'category' => 'Game Development',
        'technologies' => ['SourcePawn', 'C++', 'MySQL', 'SourceMod'],
        'image' => 'images/project3.jpg',
        'status' => 'completed',
        'client' => 'CS2 Gaming Server',
        'date' => '2024-03-10',
        'featured' => false,
        'github' => 'https://github.com/liondevs/cs2-plugins',
        'demo' => ''
    ],
    [
        'id' => 4,
        'title' => 'Corporate Website Design',
        'description' => 'Елегантен корпоративен уебсайт с модерен дизайн, responsive layout и CMS за лесно управление на съдържанието.',
        'category' => 'Web Design',
        'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'WordPress', 'Figma'],
        'image' => 'images/project4.jpg',
        'status' => 'in_progress',
        'client' => 'Tech Startup',
        'date' => '2024-04-01',
        'featured' => false,
        'github' => '',
        'demo' => 'https://demo.liondevs.com/corporate'
    ],
    [
        'id' => 5,
        'title' => 'Discord Bot Ecosystem',
        'description' => 'Мощна екосистема от Discord ботове за управление на сървъри, модерация, музика, игри и интеграция с външни услуги.',
        'category' => 'Bot Development',
        'technologies' => ['Python', 'Discord.py', 'PostgreSQL', 'Redis', 'Docker'],
        'image' => 'images/project5.jpg',
        'status' => 'completed',
        'client' => 'Discord Communities',
        'date' => '2024-01-30',
        'featured' => false,
        'github' => 'https://github.com/liondevs/discord-bots',
        'demo' => ''
    ]
];

// Услуги
$services = [
    [
        'icon' => 'fas fa-code',
        'title' => 'Web Development',
        'description' => 'Създаваме модерни, бързи и сигурни уебсайтове и уеб приложения с най-новите технологии.'
    ],
    [
        'icon' => 'fas fa-gamepad',
        'title' => 'Game Development',
        'description' => 'Разработваме игрови сървъри, плугини, модове и цялостни игрови решения.'
    ],
    [
        'icon' => 'fas fa-paint-brush',
        'title' => 'UI/UX Design',
        'description' => 'Проектираме красиви и функционални интерфейси с фокус върху потребителското изживяване.'
    ],
    [
        'icon' => 'fas fa-robot',
        'title' => 'Bot Development',
        'description' => 'Създаваме интелигентни ботове за Discord, Telegram и други платформи.'
    ],
    [
        'icon' => 'fas fa-server',
        'title' => 'Server Management',
        'description' => 'Настройваме и поддържаме сървъри за игри, уебсайтове и приложения.'
    ],
    [
        'icon' => 'fas fa-cogs',
        'title' => 'Custom Solutions',
        'description' => 'Разработваме персонализирани решения според специфичните нужди на клиентите.'
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
    return $projects[0]; // Fallback към първия проект
}

// Функция за получаване на всички проекти
function getAllProjects() {
    global $projects;
    return $projects;
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

// Функция за получаване на проекти по категория
function getProjectsByCategory($category) {
    global $projects;
    return array_filter($projects, function($project) use ($category) {
        return $project['category'] === $category;
    });
}

// Функция за получаване на всички категории
function getProjectCategories() {
    global $projects;
    $categories = array_unique(array_column($projects, 'category'));
    return $categories;
}
?>