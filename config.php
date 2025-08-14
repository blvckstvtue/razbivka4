<?php
// config.php - Lion Developments Portfolio Configuration

// Основни настройки на сайта
$site_config = [
    'site_name' => 'Lion Developments',
    'company_name' => 'Lion Developments',
    'tagline' => 'Превръщаме идеи в реалност',
    'description' => 'Професионални решения за програмиране, дизайн, игрални сървъри и всичко друго свързано с технологиите.',
    'email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    'address' => 'София, България',
    'social' => [
        'github' => 'https://github.com/liondevs',
        'linkedin' => 'https://linkedin.com/company/liondevs',
        'facebook' => 'https://facebook.com/liondevs',
        'instagram' => 'https://instagram.com/liondevs'
    ]
];

// Проекти конфигурация
$projects_config = [
    'featured_project_id' => 1, // ID на проекта който да се показва на началната страница
    'projects' => [
        [
            'id' => 1,
            'title' => 'Gaming Server Management System',
            'category' => 'Game Development',
            'description' => 'Комплексна система за управление на игрални сървъри с VIP система, магазин и администрация.',
            'technologies' => ['PHP', 'MySQL', 'JavaScript', 'CSS3', 'HTML5'],
            'image' => 'images/projects/gaming-system.jpg',
            'status' => 'completed', // completed, in-progress, planned
            'client' => 'Project ZANE',
            'completion_date' => '2024-01-15',
            'project_url' => 'https://example.com',
            'github_url' => '',
            'featured' => true
        ],
        [
            'id' => 2,
            'title' => 'E-Commerce Platform',
            'category' => 'Web Development',
            'description' => 'Модерна e-commerce платформа с интегрирани плащания, управление на инвентар и аналитика.',
            'technologies' => ['React', 'Node.js', 'MongoDB', 'Stripe API'],
            'image' => 'images/projects/ecommerce.jpg',
            'status' => 'completed',
            'client' => 'TechStore BG',
            'completion_date' => '2023-12-20',
            'project_url' => 'https://techstore.bg',
            'github_url' => '',
            'featured' => false
        ],
        [
            'id' => 3,
            'title' => 'Custom Discord Bot',
            'category' => 'Bot Development',
            'description' => 'Многофункционален Discord бот с модерация, музика, игри и custom команди.',
            'technologies' => ['Python', 'Discord.py', 'SQLite', 'Docker'],
            'image' => 'images/projects/discord-bot.jpg',
            'status' => 'completed',
            'client' => 'Gaming Community',
            'completion_date' => '2023-11-10',
            'project_url' => '',
            'github_url' => 'https://github.com/liondevs/discord-bot',
            'featured' => false
        ],
        [
            'id' => 4,
            'title' => 'Corporate Website Redesign',
            'category' => 'Design & Development',
            'description' => 'Пълен редизайн и разработка на корпоративен уебсайт с модерен дизайн и SEO оптимизация.',
            'technologies' => ['WordPress', 'PHP', 'SCSS', 'jQuery'],
            'image' => 'images/projects/corporate-site.jpg',
            'status' => 'in-progress',
            'client' => 'Bulgarian Corp',
            'completion_date' => '2024-03-01',
            'project_url' => '',
            'github_url' => '',
            'featured' => false
        ]
    ]
];

// Услуги
$services_config = [
    [
        'icon' => 'fas fa-code',
        'title' => 'Програмиране',
        'description' => 'Custom софтуер, уеб приложения, desktop приложения и мобилни решения.'
    ],
    [
        'icon' => 'fas fa-palette',
        'title' => 'Дизайн',
        'description' => 'UI/UX дизайн, брандинг, графичен дизайн и креативни решения.'
    ],
    [
        'icon' => 'fas fa-server',
        'title' => 'Игрални Сървъри',
        'description' => 'Настройка, конфигурация и поддръжка на игрални сървъри и комунити.'
    ],
    [
        'icon' => 'fas fa-robot',
        'title' => 'Ботове & Скриптове',
        'description' => 'Discord ботове, автоматизация, скриптове и плъгини за игри.'
    ],
    [
        'icon' => 'fas fa-cog',
        'title' => 'Custom Решения',
        'description' => 'Персонализирани проекти по изискване на клиента - всичко е възможно.'
    ],
    [
        'icon' => 'fas fa-headset',
        'title' => 'Поддръжка',
        'description' => '24/7 техническа поддръжка и поддръжка на всички наши проекти.'
    ]
];

// Функции за работа с проектите
function getFeaturedProject() {
    global $projects_config;
    foreach ($projects_config['projects'] as $project) {
        if ($project['id'] == $projects_config['featured_project_id']) {
            return $project;
        }
    }
    return $projects_config['projects'][0] ?? null;
}

function getAllProjects() {
    global $projects_config;
    return $projects_config['projects'];
}

function getProjectById($id) {
    global $projects_config;
    foreach ($projects_config['projects'] as $project) {
        if ($project['id'] == $id) {
            return $project;
        }
    }
    return null;
}

function addProject($project_data) {
    global $projects_config;
    
    // Намираме най-високото ID и добавяме 1
    $max_id = 0;
    foreach ($projects_config['projects'] as $project) {
        if ($project['id'] > $max_id) {
            $max_id = $project['id'];
        }
    }
    
    $project_data['id'] = $max_id + 1;
    $projects_config['projects'][] = $project_data;
    
    return saveConfig();
}

function updateProject($id, $project_data) {
    global $projects_config;
    
    foreach ($projects_config['projects'] as $key => $project) {
        if ($project['id'] == $id) {
            $project_data['id'] = $id;
            $projects_config['projects'][$key] = $project_data;
            return saveConfig();
        }
    }
    return false;
}

function deleteProject($id) {
    global $projects_config;
    
    foreach ($projects_config['projects'] as $key => $project) {
        if ($project['id'] == $id) {
            unset($projects_config['projects'][$key]);
            $projects_config['projects'] = array_values($projects_config['projects']); // Reindex array
            return saveConfig();
        }
    }
    return false;
}

function setFeaturedProject($id) {
    global $projects_config;
    $projects_config['featured_project_id'] = $id;
    return saveConfig();
}

function saveConfig() {
    global $projects_config, $site_config, $services_config;
    
    $config_content = "<?php\n// config.php - Lion Developments Portfolio Configuration\n\n";
    $config_content .= "// Основни настройки на сайта\n";
    $config_content .= '$site_config = ' . var_export($site_config, true) . ";\n\n";
    $config_content .= "// Проекти конфигурация\n";
    $config_content .= '$projects_config = ' . var_export($projects_config, true) . ";\n\n";
    $config_content .= "// Услуги\n";
    $config_content .= '$services_config = ' . var_export($services_config, true) . ";\n\n";
    
    // Добавяме функциите отново
    $config_content .= file_get_contents(__FILE__);
    $config_content = preg_replace('/^<\?php.*?(?=\/\/ Функции за работа с проектите)/s', '', $config_content);
    
    return file_put_contents(__FILE__, $config_content);
}

// Създаване на необходимите директории
if (!file_exists('images')) {
    mkdir('images', 0777, true);
}
if (!file_exists('images/projects')) {
    mkdir('images/projects', 0777, true);
}
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

?>