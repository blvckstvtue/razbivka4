<?php
// config.php - Основна конфигурация

// Database настройки
$db_config = [
    'host' => 'localhost',
    'database' => 'vip_store',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];



// Настройки на сайта
$site_config = [
    'site_name' => 'Project ZANE',
    'server_name' => 'Project ZANE',
    'server_ip' => '213.16.57.33:27016',
    'discord_url' => 'https://discord.gg/x97F6v3aTn',
    'steam_group' => '#',
    'admin_contact' => 'liondevelopments1337@gmail.com'
];

// Payment настройки
$payment_config = [
    'testpayments' => false, // true = симулирани плащания, false = реални Stripe плащания
    'stripe' => [
        'publishable_key' => '', // Твоя Stripe test publishable key
        'secret_key' => '', // ВАЖНО: Замени с твоя реален Stripe test secret key от Dashboard
        'currency' => 'eur' // Валута за плащанията
    ]
];

// Widget настройки
$widget_config = [
    'top_donors' => [
        'enabled' => true,
        'title' => 'Top Donators',
        'show_count' => 5, // Брой донатори за показване
        'show_amounts' => true // Дали да показва сумите
    ],
    'chat' => [
        'enabled' => true,
        'title' => 'Community Chat',
        'max_messages' => 50, // Максимален брой съобщения за показване
        'refresh_interval' => 3000, // Интервал за обновяване в милисекунди
        'max_message_length' => 500 // Максимална дължина на съобщение
    ],
    'servers' => [
        'enabled' => true,
        'title' => 'Our Servers',
        'servers' => [
            [
                'name' => 'Competitive CSGO Mod',
                'ip' => '213.16.57.33:27016',
                'status' => 'online' // online, offline, maintenance
            ],
            [
                'name' => 'Zombie Escape',
                'ip' => '213.16.57.33:27015',
                'status' => 'online'
            ],
            [
                'name' => 'Test Server',
                'ip' => '127.0.0.1:27017',
                'status' => 'maintenance'
            ]
        ]
    ],
    'stats' => [
        'enabled' => true,
        'registered_users' => [
            'enabled' => true,
            'title' => 'Registred Users',
            'show_live_count' => true, // true = от базата, false = от конфига
            'count' => 300 // ръчно количество когато show_live_count = false
        ],
        'total_banned' => [
            'enabled' => true,
            'title' => 'Total Banned',
            'count' => 10 // ръчно количество от конфига
        ],
        'global_top_count' => [
            'enabled' => true,
            'title' => 'Global Player Count',
            'count' => 10 // ръчно количество от конфига
        ]
    ]
];

// Начална страница - секции с изображения
$homepage_sections = [
    'hero' => [
        'title' => 'Welcome to the official Project Zane website',
        'subtitle' => 'one of the best community in CS:S',
        'background_image' => 'images/background2.jpg',
        'cta_text' => 'Ingame Store',
        'cta_link' => 'shop.php'
    ],
    'features' => [
        'title' => 'Why to choose us?',
        'items' => [
            [
                'icon' => 'fas fa-crown',
                'title' => 'Optimized scripts',
                'description' => 'Our server has one of the best and custom made scripts with 100% optimization',
                'image' => 'images/sourcepawn.png'
            ],
            [
                'icon' => 'fas fa-bolt',
                'title' => '24/7 Uptime',
                'description' => 'Our servers are always online with near up to 5 ping maximum in Europe',
                'image' => 'images/247.png'
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Anti-Cheat',
                'description' => 'We have patched all of the vulnerabilities out there and even blocked all stup*d hackers using aimbot, wallhack and others',
                'image' => 'images/vac.png'
            ],
            [
                'icon' => 'fas fa-users',
                'title' => 'Active Community',
                'description' => 'We have active community and friendly staff which will help you almost immediately after openning ticket in our discord server or in our game servers',
                'image' => 'images/community.png'
            ]
        ]
    ],
    'server_info' => [
        'title' => 'Server Information',
        'background_image' => 'https://via.placeholder.com/1200x400/334155/ffffff?text=Server+Info+Background',
        'stats' => [
            ['label' => 'Competitive CSGO IP', 'value' => '213.16.57.33:27016', 'icon' => 'fas fa-server'],
            ['label' => 'Zombie Escape IP', 'value' => '213.16.57.33:27015', 'icon' => 'fas fa-server'],
            ['label' => 'Maximum slots', 'value' => '64', 'icon' => 'fas fa-users'],
            ['label' => 'Maps', 'value' => '50+ popular', 'icon' => 'fas fa-map'],
            ['label' => 'Mod', 'value' => 'Competitive + ZE', 'icon' => 'fas fa-gamepad']
        ]
    ],
    'news' => [
        'title' => 'News',
        'items' => [
            [
                'title' => 'CSGO Mod Server',
                'date' => '2025-08-09',
                'description' => 'We are happy to introduce you in our new csgo mod server for cs:s!',
                'image' => 'images/update1.png'
            ],
            [
                'title' => 'Patch 2.0',
                'date' => '2025-08-05',
                'description' => 'We patched around 50 scripts in our ZE server with new maps, futures, happy hour mode and more!',
                'image' => 'images/update2.png'
            ],
            [
                'title' => 'Discord Server',
                'date' => '2025-08-01',
                'description' => 'We released our discord server group for easy management, purchases, tickets and more!',
                'image' => 'images/update3.png'
            ]
        ]
    ]
];

// Мулти-сървър конфигурация
$servers_config = [
    'main_server' => [
        'name' => 'Competitive CSGO Mod',
        'ip' => '213.16.57.33:27016',
        'rcon' => [
            'host' => '192.168.1.19',
            'port' => 27016,
            'password' => 'kur',
            'timeout' => 5
        ],
        'shop_enabled' => true,
        'vip_items' => [
            'vip' => [
                'name' => 'VIP 1',
                'price' => 5.00,
                'description' => 'VIP Access for 30 days',
                'image' => 'images/package1.png',
                'features' => ['Jump Effects', 'Special Skins', 'Custom Chat Color & Tag', 'Tracers', 'Neon', 'VIP Weapon Skins', 'And More'],
                'rcon_command' => 'sm_addvip "{steamid}" vip1 2592000',
                'ingame_trialvipkey' => true
            ],
            'vip2' => [
                'name' => 'VIP 2',
                'price' => 12.00,
                'description' => 'VIP Access forever',
                'image' => 'images/package3.png',
                'features' => ['Jump Effects', 'Special Skins', 'Custom Chat Color & Tag', 'Tracers', 'Neon', 'VIP Weapon Skins', 'And More'],
                'rcon_command' => 'sm_addvip "{steamid}" vip1 0',
                'ingame_vipkey' => true
            ],
            'credits1' => [
                'name' => 'Credits 1',
                'price' => 10.00,
                'description' => 'Get 100000 credits in shop',
                'image' => 'images/package4.png',
                'features' => ['Ingame credits'],
                'rcon_command' => 'sm_givecredits "{steamid}" 100000',
                'ingame_shopkey2' => true
            ],
            'credits2' => [
                'name' => 'Credits 2',
                'price' => 13.00,
                'description' => 'Get 500000 credits in shop',
                'image' => 'images/package4.png',
                'features' => ['Ingame credits'],
                'rcon_command' => 'sm_givecredits "{steamid}" 500000',
                'ingame_shopkey1' => true
            ]
        ]
    ],
    'zombie_escape' => [
        'name' => 'Zombie Escape',
        'ip' => '213.16.57.33:27015',
        'rcon' => [
            'host' => '192.168.1.19',
            'port' => 27015,
            'password' => 'vip_password',
            'timeout' => 5
        ],
        'shop_enabled' => true,
        'vip_items' => [
            'vip' => [
                'name' => 'VIP 1',
                'price' => 5.00,
                'description' => 'VIP Access for 30 days',
                'image' => 'images/package1.png',
                'features' => ['Jump Effects', 'Special Skins', 'Custom Chat Color & Tag', 'Tracers', 'Neon', 'VIP Weapon Skins', 'And More'],
                'rcon_command' => 'sm_addvip "{steamid}" vip1 2592000',
                'ingame_trialvipkey' => true
            ],
            'vip2' => [
                'name' => 'VIP 2',
                'price' => 12.00,
                'description' => 'VIP Access forever',
                'image' => 'images/package3.png',
                'features' => ['Jump Effects', 'Special Skins', 'Custom Chat Color & Tag', 'Tracers', 'Neon', 'VIP Weapon Skins', 'And More'],
                'rcon_command' => 'sm_addvip "{steamid}" vip1 0',
                'ingame_vipkey' => true
            ],
            'credits1' => [
                'name' => 'Credits 1',
                'price' => 10.00,
                'description' => 'Get 100000 credits in shop',
                'image' => 'images/package4.png',
                'features' => ['Ingame credits'],
                'rcon_command' => 'sm_givecredits "{steamid}" 100000',
                'ingame_shopkey1' => true
            ],
            'credits2' => [
                'name' => 'Credits 2',
                'price' => 13.00,
                'description' => 'Get 500000 credits in shop',
                'image' => 'images/package4.png',
                'features' => ['Ingame credits'],
                'rcon_command' => 'sm_givecredits "{steamid}" 500000',
                'ingame_shopkey2' => true
            ]
        ]
    ],
    'test_server' => [
        'name' => 'Test Сървър',
        'ip' => '127.0.0.1:27017',
        'rcon' => [
            'host' => '192.168.1.19',
            'port' => 27020,
            'password' => 'test_password',
            'timeout' => 5
        ],
        'shop_enabled' => false, // Тестовия сървър няма магазин
        'vip_items' => []
    ]
];


?>