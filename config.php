<?php
// config.php - Lion Developments Portfolio Configuration

// Company Information
$company_config = [
    'name' => 'Lion Developments',
    'tagline' => 'Creating Digital Excellence',
    'description' => 'Професионални решения в програмирането, дизайна и игровите технологии',
    'logo' => 'assets/logo.png',
    'email' => 'contact@liondevs.com',
    'founded' => '2024'
];

// Portfolio Projects Configuration
$portfolio_config = [
    'featured_project_id' => 'game-server-management', // ID на проекта за главната страница
    'projects_per_page' => 9, // Проекти на страница
    'image_path' => 'assets/projects/', // Папка за снимки на проекти
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp', 'gif']
];

// Projects Database - Тук добавяш/махаш/редактираш проекти
$projects = [
    'game-server-management' => [
        'id' => 'game-server-management',
        'title' => 'Advanced Game Server Management System',
        'category' => 'Game Development',
        'description' => 'Модерна система за управление на игрови сървъри с RCON интеграция, real-time мониторинг и автоматизирани функции.',
        'long_description' => 'Комплексна система разработена специално за управление на игрови сървъри. Включва RCON контрол, real-time чат система, автоматизирани бекъпи, player management и детайлни статистики.',
        'technologies' => ['PHP', 'JavaScript', 'MySQL', 'Socket.IO', 'RCON Protocol'],
        'image' => 'game-server-system.jpg',
        'featured' => true,
        'completed' => true,
        'year' => '2024',
        'client' => 'Gaming Community',
        'demo_url' => null,
        'github_url' => null,
        'status' => 'live'
    ],
    'e-commerce-platform' => [
        'id' => 'e-commerce-platform',
        'title' => 'Custom E-Commerce Platform',
        'category' => 'Web Development',
        'description' => 'Пълноценна e-commerce платформа с интегрирани плащания, inventory management и admin панел.',
        'long_description' => 'Разработихме уникална e-commerce платформа от нулата с фокус върху user experience и performance. Платформата включва интеграция с Stripe, автоматизиран inventory control и advanced analytics.',
        'technologies' => ['PHP', 'MySQL', 'Stripe API', 'JavaScript', 'CSS3'],
        'image' => 'ecommerce-platform.jpg',
        'featured' => false,
        'completed' => true,
        'year' => '2024',
        'client' => 'Retail Business',
        'demo_url' => null,
        'github_url' => null,
        'status' => 'live'
    ],
    'minecraft-plugin-suite' => [
        'id' => 'minecraft-plugin-suite',
        'title' => 'Minecraft Server Plugin Suite',
        'category' => 'Game Development',
        'description' => 'Колекция от custom Minecraft плъгини за подобряване на gameplay и server management.',
        'long_description' => 'Разработихме серия от innovative Minecraft плъгини включващи custom economy система, advanced protection механизми, mini-games и interactive UI elements. Всички плъгини са оптимизирани за performance.',
        'technologies' => ['Java', 'Spigot API', 'MySQL', 'YAML'],
        'image' => 'minecraft-plugins.jpg',
        'featured' => false,
        'completed' => true,
        'year' => '2024',
        'client' => 'Minecraft Server Network',
        'demo_url' => null,
        'github_url' => null,
        'status' => 'live'
    ],
    'ai-automation-tool' => [
        'id' => 'ai-automation-tool',
        'title' => 'AI-Powered Business Automation',
        'category' => 'Software Development',
        'description' => 'Интелигентна система за автоматизация на бизнес процеси използваща machine learning.',
        'long_description' => 'Иновативна платформа която използва AI за автоматизация на рутинни бизнес задачи. Системата анализира patterns в данните и предлага оптимизации за workflow processes.',
        'technologies' => ['Python', 'TensorFlow', 'FastAPI', 'PostgreSQL', 'Docker'],
        'image' => 'ai-automation.jpg',
        'featured' => false,
        'completed' => false,
        'year' => '2024',
        'client' => 'Tech Startup',
        'demo_url' => null,
        'github_url' => null,
        'status' => 'development'
    ],
    'mobile-app-backend' => [
        'id' => 'mobile-app-backend',
        'title' => 'Scalable Mobile App Backend',
        'category' => 'Backend Development',
        'description' => 'Високопроизводителен backend за mobile приложение с REST API и real-time функции.',
        'long_description' => 'Разработихме scalable backend архитектура за mobile приложение с millions of users. Включва REST API, WebSocket connections за real-time updates, caching layers и microservices архитектура.',
        'technologies' => ['Node.js', 'Express', 'MongoDB', 'Redis', 'WebSocket', 'AWS'],
        'image' => 'mobile-backend.jpg',
        'featured' => false,
        'completed' => true,
        'year' => '2024',
        'client' => 'Mobile App Company',
        'demo_url' => null,
        'github_url' => null,
        'status' => 'live'
    ],
    'brand-identity-package' => [
        'id' => 'brand-identity-package',
        'title' => 'Complete Brand Identity Package',
        'category' => 'Design',
        'description' => 'Цялостна brand identity включваща logo, color schemes, typography и marketing materials.',
        'long_description' => 'Създадохме complete brand identity от нулата включваща modern logo design, comprehensive style guide, business cards, letterheads, social media templates и promotional materials.',
        'technologies' => ['Adobe Illustrator', 'Photoshop', 'Figma', 'After Effects'],
        'image' => 'brand-identity.jpg',
        'featured' => false,
        'completed' => true,
        'year' => '2024',
        'client' => 'Startup Company',
        'demo_url' => null,
        'github_url' => null,
        'status' => 'delivered'
    ]
];

// Navigation Configuration
$navigation = [
    'Home' => 'index.php',
    'Projects' => 'projects.php',
    'About' => '#about',
    'Contact' => '#contact'
];

// Social Media Links
$social_media = [
    'github' => '#',
    'linkedin' => '#',
    'discord' => '#',
    'email' => 'contact@liondevs.com'
];

// Helper Functions
function getFeaturedProject() {
    global $projects, $portfolio_config;
    $featured_id = $portfolio_config['featured_project_id'];
    return isset($projects[$featured_id]) ? $projects[$featured_id] : null;
}

function getAllProjects() {
    global $projects;
    return $projects;
}

function getProjectsByCategory($category = null) {
    global $projects;
    if (!$category) return $projects;
    
    return array_filter($projects, function($project) use ($category) {
        return $project['category'] === $category;
    });
}

function getProjectById($id) {
    global $projects;
    return isset($projects[$id]) ? $projects[$id] : null;
}

function getProjectCategories() {
    global $projects;
    $categories = [];
    foreach ($projects as $project) {
        if (!in_array($project['category'], $categories)) {
            $categories[] = $project['category'];
        }
    }
    return $categories;
}

// Security functions
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validateImageUpload($file) {
    global $portfolio_config;
    
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($ext, $portfolio_config['allowed_image_types']);
}
?>