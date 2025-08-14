<?php
require_once 'config.php';

$all_projects = getAllProjects();
$categories = array_unique(array_column($all_projects, 'category'));
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекти - <?= $site_config['site_name'] ?></title>
    <meta name="description" content="Разгледайте всички проекти на <?= $site_config['company_name'] ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #ff6b35;
            --primary-dark: #e55a2b;
            --secondary: #1a1a1a;
            --accent: #00d4ff;
            --accent-dark: #00b8e6;
            --background: #0a0a0a;
            --surface: #1e1e1e;
            --surface-light: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #808080;
            --border: #333333;
            --gradient-main: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 100%);
            --shadow-glow: 0 0 30px rgba(255, 107, 53, 0.3);
            --shadow-accent: 0 0 30px rgba(0, 212, 255, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: 80px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-main);
            border-radius: 4px;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            z-index: 1000;
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            position: relative;
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            position: relative;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-links a:hover {
            color: var(--primary);
            text-shadow: 0 0 10px var(--primary);
        }

        .nav-links a.active {
            color: var(--accent);
            text-shadow: 0 0 10px var(--accent);
        }

        /* Page Header */
        .page-header {
            padding: 80px 0 60px;
            background: var(--gradient-dark);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 70%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(0, 212, 255, 0.1) 0%, transparent 50%);
            animation: headerGlow 6s ease-in-out infinite alternate;
        }

        @keyframes headerGlow {
            0% { opacity: 0.3; }
            100% { opacity: 0.6; }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            position: relative;
            z-index: 2;
        }

        .page-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.3)); }
            100% { filter: drop-shadow(0 0 40px rgba(255, 107, 53, 0.6)); }
        }

        .page-header p {
            font-size: 1.3rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Filter Section */
        .filter-section {
            padding: 60px 0;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 12px 30px;
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--border);
            border-radius: 50px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--gradient-main);
            color: white;
            border-color: transparent;
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
        }

        /* Projects Grid */
        .projects-section {
            padding: 80px 0;
            background: var(--background);
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .project-card {
            background: var(--surface);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.5s ease;
            border: 1px solid var(--border);
            position: relative;
            opacity: 1;
            transform: scale(1);
        }

        .project-card.hidden {
            opacity: 0;
            transform: scale(0.8);
            pointer-events: none;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border-color: var(--primary);
        }

        .project-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-card:hover .project-image img {
            transform: scale(1.1);
        }

        .project-status {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-completed {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-in-progress {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .status-planned {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }

        .project-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
            display: flex;
            align-items: flex-end;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .project-card:hover .project-overlay {
            opacity: 1;
        }

        .project-quick-info {
            color: white;
        }

        .project-quick-info h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .project-content {
            padding: 30px;
        }

        .project-category {
            display: inline-block;
            background: var(--gradient-main);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .project-content h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-primary);
        }

        .project-content p {
            color: var(--text-secondary);
            margin-bottom: 20px;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 25px;
        }

        .tech-tag {
            background: var(--surface-light);
            color: var(--accent);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid var(--accent);
        }

        .project-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .project-links {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--gradient-main);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--accent);
            border-color: var(--accent);
        }

        .btn-outline:hover {
            background: var(--accent);
            color: var(--background);
            transform: translateY(-2px);
        }

        /* Featured Badge */
        .featured-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--gradient-main);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: var(--shadow-glow);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--border);
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .projects-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .filter-buttons {
                gap: 10px;
            }

            .filter-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .project-links {
                flex-direction: column;
            }
        }

        /* Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">LION DEVS</a>
            <ul class="nav-links">
                <li><a href="index.php">Начало</a></li>
                <li><a href="projects.php" class="active">Проекти</a></li>
                <li><a href="index.php#services">Услуги</a></li>
                <li><a href="index.php#contact">Контакт</a></li>
                <li><a href="admin.php">Админ</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Нашите Проекти</h1>
            <p>Разгледайте портфолиото ни и видете какво можем да постигнем заедно</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">Всички</button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn" data-category="<?= htmlspecialchars($category) ?>"><?= $category ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="projects-section">
        <div class="container">
            <div class="projects-grid" id="projects-grid">
                <?php foreach ($all_projects as $project): ?>
                <div class="project-card fade-in" data-category="<?= htmlspecialchars($project['category']) ?>">
                    <?php if ($project['featured']): ?>
                        <div class="featured-badge">
                            <i class="fas fa-star"></i> Избран
                        </div>
                    <?php endif; ?>
                    
                    <div class="project-image">
                        <img src="<?= $project['image'] ?>" alt="<?= $project['title'] ?>" 
                             onerror="this.src='https://via.placeholder.com/500x250/1e1e1e/ff6b35?text=<?= urlencode($project['title']) ?>'">
                        
                        <div class="project-status status-<?= $project['status'] ?>">
                            <?php
                            switch($project['status']) {
                                case 'completed': echo 'Завършен'; break;
                                case 'in-progress': echo 'В процес'; break;
                                case 'planned': echo 'Планиран'; break;
                                default: echo $project['status'];
                            }
                            ?>
                        </div>
                        
                        <div class="project-overlay">
                            <div class="project-quick-info">
                                <h4><?= $project['title'] ?></h4>
                                <p><?= $project['category'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="project-content">
                        <span class="project-category"><?= $project['category'] ?></span>
                        <h3><?= $project['title'] ?></h3>
                        <p><?= $project['description'] ?></p>
                        
                        <div class="tech-stack">
                            <?php foreach ($project['technologies'] as $tech): ?>
                                <span class="tech-tag"><?= $tech ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="project-meta">
                            <span><i class="fas fa-user"></i> <?= $project['client'] ?></span>
                            <span><i class="fas fa-calendar"></i> <?= date('M Y', strtotime($project['completion_date'])) ?></span>
                        </div>
                        
                        <div class="project-links">
                            <?php if (!empty($project['project_url'])): ?>
                                <a href="<?= $project['project_url'] ?>" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt"></i> Виж
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($project['github_url'])): ?>
                                <a href="<?= $project['github_url'] ?>" target="_blank" class="btn btn-outline">
                                    <i class="fab fa-github"></i> GitHub
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($all_projects)): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>Няма проекти</h3>
                <p>Все още няма добавени проекти в портфолиото.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');

                const category = button.getAttribute('data-category');

                projectCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (category === 'all' || cardCategory === category) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>