<?php
// config.php - Конфигурация за LionDevs Portfolio

// Основни настройки на сайта
$site_config = [
    'site_name' => 'LionDevs',
    'company_name' => 'Lion Developments',
    'company_description' => 'Професионална компания за разработка на софтуер, дизайн, сървъри за игри и персонализирани решения',
    'contact_email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    'address' => 'София, България'
];

// Проекти - тук можеш да добавяш, редактираш и махаш проекти
$projects = [
    [
        'id' => 1,
        'title' => 'Gaming Server Management System',
        'description' => 'Пълна система за управление на игрови сървъри с VIP система, донации, чат и статистики.',
        'technologies' => ['PHP', 'MySQL', 'JavaScript', 'HTML5', 'CSS3'],
        'category' => 'web-development',
        'image' => 'images/project1.jpg',
        'status' => 'completed',
        'featured' => true, // Този проект ще се показва на началната страница
        'date_completed' => '2024-01-15',
        'client' => 'Gaming Community',
        'github_url' => '', // оставяш празно ако не искаш да се показва
        'demo_url' => '',
        'details' => 'Разработихме цялостна система за управление на игрови сървъри включваща VIP система с различни пакети, система за донации чрез Stripe, реалтайм чат, статистики за играчи, RCON управление и много други функционалности.'
    ],
    [
        'id' => 2,
        'title' => 'E-Commerce Platform',
        'description' => 'Модерна платформа за електронна търговия с интегрирани плащания и управление на инвентар.',
        'technologies' => ['PHP', 'Laravel', 'Vue.js', 'MySQL', 'Stripe'],
        'category' => 'web-development',
        'image' => 'images/project2.jpg',
        'status' => 'completed',
        'featured' => false,
        'date_completed' => '2024-02-20',
        'client' => 'Retail Company',
        'github_url' => '',
        'demo_url' => '',
        'details' => 'Създадохме пълна платформа за онлайн търговия с корзина, плащания, управление на продукти, потребители и поръчки.'
    ],
    [
        'id' => 3,
        'title' => 'Mobile Game Development',
        'description' => 'Разработка на мобилна игра с Unity и интегриране с backend за multiplayer функционалност.',
        'technologies' => ['Unity', 'C#', 'Node.js', 'MongoDB', 'WebSocket'],
        'category' => 'game-development',
        'image' => 'images/project3.jpg',
        'status' => 'in-progress',
        'featured' => false,
        'date_completed' => '',
        'client' => 'Game Studio',
        'github_url' => '',
        'demo_url' => '',
        'details' => 'Разработваме интересна мобилна игра с multiplayer възможности и реалтайм комуникация между играчите.'
    ],
    [
        'id' => 4,
        'title' => 'Corporate Website Design',
        'description' => 'Модерен корпоративен уебсайт с респонсивен дизайн и CMS система.',
        'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'PHP', 'MySQL'],
        'category' => 'web-design',
        'image' => 'images/project4.jpg',
        'status' => 'completed',
        'featured' => false,
        'date_completed' => '2024-03-10',
        'client' => 'Business Corp',
        'github_url' => '',
        'demo_url' => '',
        'details' => 'Проектирахме и разработихме модерен корпоративен сайт с елегантен дизайн и лесна навигация.'
    ]
];

// Категории проекти
$project_categories = [
    'web-development' => [
        'name' => 'Уеб Разработка',
        'icon' => 'fas fa-code',
        'color' => '#6366f1'
    ],
    'game-development' => [
        'name' => 'Разработка на Игри',
        'icon' => 'fas fa-gamepad',
        'color' => '#10b981'
    ],
    'web-design' => [
        'name' => 'Уеб Дизайн',
        'icon' => 'fas fa-paint-brush',
        'color' => '#f59e0b'
    ],
    'mobile-development' => [
        'name' => 'Мобилни Приложения',
        'icon' => 'fas fa-mobile-alt',
        'color' => '#ef4444'
    ],
    'server-administration' => [
        'name' => 'Сървър Администрация',
        'icon' => 'fas fa-server',
        'color' => '#8b5cf6'
    ]
];

// Функция за получаване на featured проект
function getFeaturedProject() {
    global $projects;
    foreach ($projects as $project) {
        if ($project['featured'] === true) {
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

// Услуги които предлагаме
$services = [
    [
        'title' => 'Уеб Разработка',
        'description' => 'Създаваме модерни и функционални уебсайтове',
        'icon' => 'fas fa-code',
        'features' => ['PHP/Laravel', 'JavaScript/Vue.js', 'Responsive Design', 'E-commerce']
    ],
    [
        'title' => 'Дизайн',
        'description' => 'Креативни решения за всички ваши нужди',
        'icon' => 'fas fa-paint-brush',
        'features' => ['UI/UX Design', 'Logo Design', 'Brand Identity', 'Print Design']
    ],
    [
        'title' => 'Игрови Сървъри',
        'description' => 'Професионално настройване и поддръжка',
        'icon' => 'fas fa-server',
        'features' => ['Server Setup', 'Plugin Development', 'Custom Scripts', '24/7 Support']
    ],
    [
        'title' => 'Мобилни Приложения',
        'description' => 'Разработка на приложения за iOS и Android',
        'icon' => 'fas fa-mobile-alt',
        'features' => ['Native Development', 'Cross-platform', 'UI/UX Design', 'App Store Publishing']
    ]
];

// Екип
$team_members = [
    [
        'name' => 'Александър',
        'position' => 'Lead Developer & CEO',
        'description' => 'Експерт в уеб разработка и игрови технологии',
        'image' => 'images/team1.jpg',
        'skills' => ['PHP', 'JavaScript', 'Unity', 'Server Management']
    ]
];

?>