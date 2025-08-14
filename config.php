<?php
// config.php - Lion Developments Portfolio Configuration

// Site Configuration
$site_config = [
    'company_name' => 'Lion Developments',
    'company_short' => 'LionDevs',
    'tagline' => 'Превръщаме идеите в реалност',
    'description' => 'Ние сме Lion Developments - компания специализирана в програмиране, дизайн, игрови сървъри и custom решения. Всеки проект е уникален за нас.',
    'email' => 'info@liondevs.com',
    'phone' => '+359 888 123 456',
    'address' => 'София, България'
];

// Featured Project (показва се на началната страница)
$featured_project = 'project_vip_store'; // ID на проекта който искаш да се показва

// Projects Configuration
$projects = [
    'project_vip_store' => [
        'title' => 'VIP Store System',
        'category' => 'gaming',
        'description' => 'Напълно функционална VIP store система за игрови сървъри с Stripe интеграция, real-time chat, RCON управление и администраторски панел.',
        'image' => 'images/projects/vip_store.jpg',
        'technologies' => ['PHP', 'MySQL', 'JavaScript', 'Stripe API', 'RCON'],
        'status' => 'completed',
        'year' => '2024',
        'duration' => '3 месеца',
        'client' => 'Project ZANE',
        'features' => [
            'Stripe payment integration',
            'Real-time chat system', 
            'RCON command execution',
            'User management system',
            'Admin dashboard',
            'Responsive design'
        ],
        'gallery' => [
            'images/projects/vip_store_1.jpg',
            'images/projects/vip_store_2.jpg',
            'images/projects/vip_store_3.jpg'
        ]
    ],
    
    'project_discord_bot' => [
        'title' => 'Advanced Discord Bot',
        'category' => 'software',
        'description' => 'Multi-functional Discord bot с moderation, music, games и custom commands. Поддържа множество сървъри едновременно.',
        'image' => 'images/projects/discord_bot.jpg',
        'technologies' => ['Python', 'Discord.py', 'SQLite', 'API Integration'],
        'status' => 'completed',
        'year' => '2024',
        'duration' => '2 месеца',
        'client' => 'Gaming Communities',
        'features' => [
            'Auto moderation',
            'Music streaming',
            'Custom commands',
            'Economy system',
            'Server statistics',
            'Ticket system'
        ],
        'gallery' => [
            'images/projects/discord_bot_1.jpg',
            'images/projects/discord_bot_2.jpg'
        ]
    ],

    'project_ecommerce' => [
        'title' => 'E-Commerce Platform',
        'category' => 'web',
        'description' => 'Модерна e-commerce платформа с payment gateway, inventory management и admin dashboard. Напълно responsive и SEO optimized.',
        'image' => 'images/projects/ecommerce.jpg',
        'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Stripe', 'Redis'],
        'status' => 'completed',
        'year' => '2024',
        'duration' => '4 месеца',
        'client' => 'Retail Business',
        'features' => [
            'Product catalog',
            'Shopping cart',
            'Payment processing',
            'Order management',
            'Customer accounts',
            'Analytics dashboard'
        ],
        'gallery' => [
            'images/projects/ecommerce_1.jpg',
            'images/projects/ecommerce_2.jpg',
            'images/projects/ecommerce_3.jpg'
        ]
    ],

    'project_mobile_app' => [
        'title' => 'Cross-Platform Mobile App',
        'category' => 'mobile',
        'description' => 'React Native приложение за iOS и Android с real-time функционалности, push notifications и cloud sync.',
        'image' => 'images/projects/mobile_app.jpg',
        'technologies' => ['React Native', 'Firebase', 'Redux', 'TypeScript'],
        'status' => 'in_progress',
        'year' => '2024',
        'duration' => '5 месеца',
        'client' => 'Tech Startup',
        'features' => [
            'Cross-platform compatibility',
            'Real-time messaging',
            'Push notifications',
            'Offline capabilities',
            'Cloud synchronization',
            'Biometric authentication'
        ],
        'gallery' => [
            'images/projects/mobile_app_1.jpg',
            'images/projects/mobile_app_2.jpg'
        ]
    ],

    'project_game_plugin' => [
        'title' => 'Minecraft Server Plugins',
        'category' => 'gaming',
        'description' => 'Collection от custom Minecraft plugins включващи economy system, mini-games, custom crafting и player management.',
        'image' => 'images/projects/minecraft_plugins.jpg',
        'technologies' => ['Java', 'Bukkit API', 'MySQL', 'YAML'],
        'status' => 'completed',
        'year' => '2023',
        'duration' => '6 месеца',
        'client' => 'Gaming Network',
        'features' => [
            'Economy system',
            'Custom mini-games',
            'Player statistics',
            'Custom crafting recipes',
            'Permission management',
            'Multi-world support'
        ],
        'gallery' => [
            'images/projects/minecraft_1.jpg',
            'images/projects/minecraft_2.jpg',
            'images/projects/minecraft_3.jpg'
        ]
    ]
];

// Project Categories
$categories = [
    'all' => 'Всички',
    'web' => 'Web Development',
    'mobile' => 'Mobile Apps',
    'gaming' => 'Gaming Solutions',
    'software' => 'Software Development',
    'design' => 'UI/UX Design'
];

// Company Services
$services = [
    [
        'title' => 'Web Development',
        'description' => 'Модерни уеб приложения и сайтове с най-новите технологии',
        'icon' => 'fas fa-code',
        'technologies' => ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React']
    ],
    [
        'title' => 'Mobile Development',
        'description' => 'Cross-platform мобилни приложения за iOS и Android',
        'icon' => 'fas fa-mobile-alt',
        'technologies' => ['React Native', 'Flutter', 'Swift', 'Kotlin']
    ],
    [
        'title' => 'Gaming Solutions',
        'description' => 'Игрови сървъри, plugins, scripts и custom модификации',
        'icon' => 'fas fa-gamepad',
        'technologies' => ['Java', 'C#', 'Lua', 'SourcePawn', 'Python']
    ],
    [
        'title' => 'UI/UX Design',
        'description' => 'Съвременен дизайн който впечатлява и конвертира',
        'icon' => 'fas fa-paint-brush',
        'technologies' => ['Figma', 'Adobe XD', 'Photoshop', 'Illustrator']
    ],
    [
        'title' => 'Custom Solutions',
        'description' => 'Специализирани решения по изискване на клиента',
        'icon' => 'fas fa-cogs',
        'technologies' => ['Custom Development', 'API Integration', 'Automation']
    ],
    [
        'title' => 'Server Management',
        'description' => 'Настройка и поддръжка на сървъри и хостинг решения',
        'icon' => 'fas fa-server',
        'technologies' => ['Linux', 'Docker', 'AWS', 'VPS Management']
    ]
];

// Team Members (опционално)
$team = [
    [
        'name' => 'Главен Developer',
        'role' => 'Full Stack Developer & CEO',
        'image' => 'images/team/ceo.jpg',
        'skills' => ['PHP', 'JavaScript', 'Python', 'Game Development']
    ]
];

// Contact Information
$contact = [
    'email' => 'info@liondevs.com',
    'phone' => '+359 888 123 456',
    'discord' => 'LionDevs#1337',
    'github' => 'https://github.com/liondevs',
    'linkedin' => 'https://linkedin.com/company/liondevs'
];

// SEO Configuration
$seo = [
    'title' => 'Lion Developments - Професионални IT Решения',
    'description' => 'Lion Developments предлага професионални услуги в програмирането, дизайна, игровите сървъри и custom разработки. Превръщаме идеите ви в реалност.',
    'keywords' => 'програмиране, web development, mobile apps, игрови сървъри, дизайн, България',
    'og_image' => 'images/og-image.jpg'
];

// Theme Configuration
$theme = [
    'primary_color' => '#ff6b35',
    'secondary_color' => '#1a1a1a',
    'accent_color' => '#ffd700',
    'success_color' => '#28a745',
    'danger_color' => '#dc3545',
    'warning_color' => '#ffc107'
];

?>