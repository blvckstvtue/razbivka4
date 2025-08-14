<?php
// config.php - Lion Developments Portfolio Configuration

// Site Configuration
$site_config = [
    'company_name' => 'Lion Developments',
    'company_slogan' => 'Crafting Digital Excellence',
    'company_description' => 'We specialize in programming, design, game servers, scripts, plugins and custom client projects. Everything you need, we deliver.',
    'contact_email' => 'contact@liondevs.com',
    'phone' => '+359 XXX XXX XXX',
    'address' => 'Bulgaria',
    'social_links' => [
        'github' => 'https://github.com/liondevs',
        'linkedin' => 'https://linkedin.com/company/liondevs',
        'discord' => 'https://discord.gg/liondevs',
        'instagram' => 'https://instagram.com/liondevs'
    ]
];

// Projects Configuration
// Add, edit, or remove projects here
$projects_config = [
    'featured_project' => 'vip-store', // ID of the project to feature on homepage
    'projects' => [
        [
            'id' => 'vip-store',
            'title' => 'VIP Store System',
            'category' => 'Game Development',
            'description' => 'Advanced VIP store system for Counter-Strike servers with payment integration, user management, and real-time statistics.',
            'image' => 'images/projects/vip-store.svg',
            'technologies' => ['PHP', 'MySQL', 'JavaScript', 'Stripe API', 'SourcePawn'],
            'features' => [
                'Secure payment processing',
                'Real-time server integration',
                'Advanced user management',
                'Statistics dashboard',
                'Multi-language support'
            ],
            'status' => 'completed',
            'date' => '2024-01-15',
            'client' => 'Gaming Community',
            'duration' => '3 months',
            'github_link' => '',
            'live_demo' => '',
            'price_range' => '€500-1000'
        ],
        [
            'id' => 'custom-cms',
            'title' => 'Custom CMS Platform',
            'category' => 'Web Development',
            'description' => 'Tailored content management system built from scratch for enterprise clients with advanced workflow management.',
            'image' => 'images/projects/cms-platform.svg',
            'technologies' => ['PHP', 'Laravel', 'Vue.js', 'MySQL', 'Redis'],
            'features' => [
                'Multi-tenant architecture',
                'Advanced role management',
                'Workflow automation',
                'API integrations',
                'Real-time collaboration'
            ],
            'status' => 'completed',
            'date' => '2023-11-20',
            'client' => 'Enterprise Corp',
            'duration' => '6 months',
            'github_link' => '',
            'live_demo' => '',
            'price_range' => '€2000-5000'
        ],
        [
            'id' => 'game-server-manager',
            'title' => 'Game Server Manager',
            'category' => 'Game Development',
            'description' => 'Comprehensive server management tool for multiple game types with automated deployment and monitoring.',
            'image' => 'images/projects/server-manager.jpg',
            'technologies' => ['Python', 'Docker', 'React', 'PostgreSQL', 'WebSocket'],
            'features' => [
                'Multi-game support',
                'Automated deployments',
                'Real-time monitoring',
                'Resource optimization',
                'Plugin management'
            ],
            'status' => 'in_progress',
            'date' => '2024-02-01',
            'client' => 'Gaming Network',
            'duration' => '4 months',
            'github_link' => '',
            'live_demo' => '',
            'price_range' => '€1500-3000'
        ],
        [
            'id' => 'brand-identity',
            'title' => 'Complete Brand Identity',
            'category' => 'Design',
            'description' => 'Full brand identity design including logo, website, marketing materials and brand guidelines.',
            'image' => 'images/projects/brand-identity.jpg',
            'technologies' => ['Adobe Creative Suite', 'Figma', 'HTML/CSS', 'JavaScript'],
            'features' => [
                'Logo design',
                'Brand guidelines',
                'Website design',
                'Marketing materials',
                'Social media assets'
            ],
            'status' => 'completed',
            'date' => '2023-09-10',
            'client' => 'Startup Company',
            'duration' => '2 months',
            'github_link' => '',
            'live_demo' => '',
            'price_range' => '€800-1500'
        ],
        [
            'id' => 'mobile-app',
            'title' => 'Mobile Gaming App',
            'category' => 'Mobile Development',
            'description' => 'Cross-platform mobile gaming application with real-time multiplayer functionality and in-app purchases.',
            'image' => 'images/projects/mobile-app.svg',
            'technologies' => ['React Native', 'Node.js', 'MongoDB', 'Socket.io', 'Firebase'],
            'features' => [
                'Real-time multiplayer',
                'In-app purchases',
                'Social features',
                'Leaderboards',
                'Push notifications'
            ],
            'status' => 'completed',
            'date' => '2023-12-05',
            'client' => 'Gaming Studio',
            'duration' => '5 months',
            'github_link' => '',
            'live_demo' => '',
            'price_range' => '€3000-6000'
        ],
        [
            'id' => 'ecommerce-platform',
            'title' => 'E-commerce Platform',
            'category' => 'Web Development',
            'description' => 'Modern e-commerce solution with advanced inventory management, payment processing and analytics.',
            'image' => 'images/projects/ecommerce.jpg',
            'technologies' => ['Next.js', 'Stripe', 'PostgreSQL', 'Tailwind CSS', 'Vercel'],
            'features' => [
                'Advanced inventory system',
                'Multiple payment gateways',
                'Analytics dashboard',
                'SEO optimization',
                'Mobile-first design'
            ],
            'status' => 'completed',
            'date' => '2024-03-12',
            'client' => 'Retail Business',
            'duration' => '4 months',
            'github_link' => '',
            'live_demo' => '',
            'price_range' => '€2500-5000'
        ]
    ]
];

// Services Configuration
$services_config = [
    'services' => [
        [
            'icon' => 'fas fa-code',
            'title' => 'Custom Development',
            'description' => 'Tailored software solutions built from scratch to meet your specific needs.',
            'features' => ['Web Applications', 'Desktop Software', 'APIs & Integrations', 'Database Design']
        ],
        [
            'icon' => 'fas fa-gamepad',
            'title' => 'Game Development',
            'description' => 'Game servers, plugins, scripts and complete gaming solutions.',
            'features' => ['Game Servers', 'Plugins & Scripts', 'Server Management', 'Community Tools']
        ],
        [
            'icon' => 'fas fa-palette',
            'title' => 'Design Services',
            'description' => 'Creative design solutions for digital and print media.',
            'features' => ['UI/UX Design', 'Brand Identity', 'Web Design', 'Marketing Materials']
        ],
        [
            'icon' => 'fas fa-mobile-alt',
            'title' => 'Mobile Development',
            'description' => 'Cross-platform mobile applications for iOS and Android.',
            'features' => ['Native Apps', 'Cross-Platform', 'App Store Optimization', 'Maintenance']
        ]
    ]
];

// Team Configuration
$team_config = [
    'members' => [
        [
            'name' => 'Lead Developer',
            'role' => 'Full-Stack Developer',
            'image' => 'images/team/developer.jpg',
            'skills' => ['PHP', 'JavaScript', 'Python', 'MySQL', 'React'],
            'description' => 'Experienced developer with expertise in web and game development.'
        ]
    ]
];

// Theme Configuration
$theme_config = [
    'primary_color' => '#ff6b35',
    'secondary_color' => '#1a1a1a',
    'accent_color' => '#ffd23f',
    'background_color' => '#0a0a0a',
    'text_color' => '#ffffff',
    'gradient_primary' => 'linear-gradient(135deg, #ff6b35 0%, #f7931e 100%)',
    'gradient_secondary' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
];

// Helper Functions
function getProjectById($id) {
    global $projects_config;
    foreach ($projects_config['projects'] as $project) {
        if ($project['id'] === $id) {
            return $project;
        }
    }
    return null;
}

function getFeaturedProject() {
    global $projects_config;
    return getProjectById($projects_config['featured_project']);
}

function getProjectsByCategory($category = null) {
    global $projects_config;
    if (!$category) {
        return $projects_config['projects'];
    }
    
    return array_filter($projects_config['projects'], function($project) use ($category) {
        return $project['category'] === $category;
    });
}

function getProjectCategories() {
    global $projects_config;
    $categories = [];
    foreach ($projects_config['projects'] as $project) {
        if (!in_array($project['category'], $categories)) {
            $categories[] = $project['category'];
        }
    }
    return $categories;
}
?>