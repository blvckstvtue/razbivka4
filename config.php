<?php
// config.php - Конфигурация за LionDevs Portfolio

// Настройки на компанията
$company_config = [
    'name' => 'LionDevs',
    'full_name' => 'Lion Developments',
    'tagline' => 'We Build Digital Dreams',
    'description' => 'Създаваме иновативни решения - програми, дизайни, сървъри за игри, скриптове, плугини и проекти по поръчка.',
    'contact_email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    'social_media' => [
        'github' => 'https://github.com/liondevs',
        'discord' => 'https://discord.gg/liondevs',
        'instagram' => 'https://instagram.com/liondevs',
        'linkedin' => 'https://linkedin.com/company/liondevs'
    ]
];

// Портфолио проекти
$portfolio_projects = [
    'featured_project' => 1, // ID на проекта който да се показва на началната страница
    'projects' => [
        [
            'id' => 1,
            'title' => 'CS:GO VIP Store',
            'category' => 'Gaming',
            'description' => 'Модерен VIP магазин за Counter-Strike сървъри с интеграция с Stripe плащания, real-time статистики и админ панел.',
            'technologies' => ['PHP', 'MySQL', 'JavaScript', 'Stripe API', 'Bootstrap'],
            'image' => 'images/projects/csgo-vip-store.jpg',
            'demo_url' => '#',
            'github_url' => '#',
            'status' => 'completed',
            'completion_date' => '2024-01-15',
            'client' => 'Gaming Community',
            'price_range' => '500-1000',
            'featured' => true
        ],
        [
            'id' => 2,
            'title' => 'Discord Bot Framework',
            'category' => 'Software',
            'description' => 'Мощен framework за създаване на Discord ботове с модулна архитектура, database интеграция и автоматични updates.',
            'technologies' => ['Python', 'Discord.py', 'SQLite', 'AsyncIO'],
            'image' => 'images/projects/discord-bot.jpg',
            'demo_url' => '#',
            'github_url' => '#',
            'status' => 'completed',
            'completion_date' => '2024-02-20',
            'client' => 'Multiple Clients',
            'price_range' => '300-800',
            'featured' => true
        ],
        [
            'id' => 3,
            'title' => 'E-Commerce Platform',
            'category' => 'Web Development',
            'description' => 'Пълна e-commerce платформа с админ панел, inventory management, payment gateway интеграция и responsive design.',
            'technologies' => ['PHP', 'Laravel', 'Vue.js', 'MySQL', 'PayPal API'],
            'image' => 'images/projects/ecommerce.jpg',
            'demo_url' => '#',
            'github_url' => '#',
            'status' => 'in_progress',
            'completion_date' => '2024-03-30',
            'client' => 'Retail Business',
            'price_range' => '1500-3000',
            'featured' => true
        ],
        [
            'id' => 4,
            'title' => 'Brand Identity Design',
            'category' => 'Design',
            'description' => 'Пълен брандинг пакет включващ лого дизайн, визитки, фирмени материали и web дизайн концепция.',
            'technologies' => ['Adobe Illustrator', 'Photoshop', 'Figma', 'InDesign'],
            'image' => 'images/projects/brand-identity.jpg',
            'demo_url' => '#',
            'github_url' => null,
            'status' => 'completed',
            'completion_date' => '2024-01-10',
            'client' => 'Startup Company',
            'price_range' => '800-1200',
            'featured' => false
        ],
        [
            'id' => 5,
            'title' => 'Minecraft Server Plugin',
            'category' => 'Gaming',
            'description' => 'Custom plugin за Minecraft сървър с economy система, PvP arenas, rankings и special events.',
            'technologies' => ['Java', 'Spigot API', 'MySQL', 'Maven'],
            'image' => 'images/projects/minecraft-plugin.jpg',
            'demo_url' => '#',
            'github_url' => '#',
            'status' => 'completed',
            'completion_date' => '2024-02-05',
            'client' => 'Minecraft Server Owner',
            'price_range' => '400-700',
            'featured' => false
        ]
    ]
];

// Услуги на компанията
$services = [
    [
        'icon' => 'fas fa-code',
        'title' => 'Custom Software Development',
        'description' => 'Разработваме custom софтуер по ваши изисквания - от прости скриптове до сложни enterprise решения.'
    ],
    [
        'icon' => 'fas fa-gamepad',
        'title' => 'Gaming Solutions',
        'description' => 'Сървъри за игри, плугини, модификации и custom игрови решения за всички популярни платформи.'
    ],
    [
        'icon' => 'fas fa-paint-brush',
        'title' => 'Design & Branding',
        'description' => 'Лого дизайн, брандинг, UI/UX дизайн, графичен дизайн и визуални решения за вашия бизнес.'
    ],
    [
        'icon' => 'fas fa-globe',
        'title' => 'Web Development',
        'description' => 'Modern и responsive уебсайтове, web приложения, e-commerce решения и SEO оптимизация.'
    ],
    [
        'icon' => 'fas fa-robot',
        'title' => 'Automation & Bots',
        'description' => 'Discord ботове, автоматизация на процеси, API интеграции и intelligent chatbots.'
    ],
    [
        'icon' => 'fas fa-tools',
        'title' => 'Technical Support',
        'description' => 'Поддръжка на проекти, troubleshooting, optimization и technical consulting услуги.'
    ]
];

// Статистики на компанията
$company_stats = [
    [
        'number' => '50+',
        'label' => 'Успешни Проекта',
        'icon' => 'fas fa-project-diagram'
    ],
    [
        'number' => '30+',
        'label' => 'Доволни Клиенти',
        'icon' => 'fas fa-users'
    ],
    [
        'number' => '3+',
        'label' => 'Години Опит',
        'icon' => 'fas fa-calendar-alt'
    ],
    [
        'number' => '24/7',
        'label' => 'Поддръжка',
        'icon' => 'fas fa-headset'
    ]
];

// Настройки на сайта
$site_config = [
    'site_title' => 'LionDevs - Professional Development Services',
    'meta_description' => 'LionDevs - Професионални услуги по програмиране, дизайн, gaming solutions и web development. Създаваме иновативни решения за вашия бизнес.',
    'meta_keywords' => 'programming, web development, game development, design, liondevs, bulgaria',
    'enable_dark_mode' => true,
    'enable_animations' => true,
    'contact_form_email' => 'contact@liondevs.com'
];

// Функция за получаване на featured проект
function getFeaturedProject() {
    global $portfolio_projects;
    $featured_id = $portfolio_projects['featured_project'];
    
    foreach ($portfolio_projects['projects'] as $project) {
        if ($project['id'] == $featured_id) {
            return $project;
        }
    }
    
    return null;
}

// Функция за получаване на всички проекти
function getAllProjects() {
    global $portfolio_projects;
    return $portfolio_projects['projects'];
}

// Функция за получаване на проект по ID
function getProjectById($id) {
    global $portfolio_projects;
    
    foreach ($portfolio_projects['projects'] as $project) {
        if ($project['id'] == $id) {
            return $project;
        }
    }
    
    return null;
}

// Функция за филтриране на проекти по категория
function getProjectsByCategory($category) {
    global $portfolio_projects;
    
    return array_filter($portfolio_projects['projects'], function($project) use ($category) {
        return $project['category'] === $category;
    });
}

// Функция за получаване на всички категории
function getProjectCategories() {
    global $portfolio_projects;
    
    $categories = array_unique(array_column($portfolio_projects['projects'], 'category'));
    return $categories;
}
?>