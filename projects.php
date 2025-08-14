<?php
require_once 'config.php';
$all_projects = getAllProjects();
$categories = getProjectCategories();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекти - <?= $site_config['company_name'] ?></title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Meta Tags -->
    <meta name="description" content="Разгледайте нашите проекти - уеб разработка, игрови сървъри, дизайн и много други.">
    <meta name="keywords" content="портфолио, проекти, програмиране, дизайн, игрови сървъри, България">
    <meta name="author" content="<?= $site_config['company_name'] ?>">
    
    <style>
        :root {
            --primary-gold: #FFD700;
            --primary-orange: #FF6B35;
            --dark-bg: #0a0a0a;
            --darker-bg: #000000;
            --surface-dark: #1a1a1a;
            --surface-darker: #0f0f0f;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --text-muted: #888888;
            --accent-red: #ff0000;
            --accent-green: #00ff41;
            --border-glow: rgba(255, 215, 0, 0.3);
            --shadow-brutal: 0 0 50px rgba(255, 107, 53, 0.3);
            --gradient-fire: linear-gradient(135deg, #ff6b35 0%, #ffd700 50%, #ff0000 100%);
            --gradient-dark: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: 80px;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 20% 50%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(255, 0, 0, 0.1) 0%, transparent 50%);
            animation: bgShift 20s ease-in-out infinite;
        }

        @keyframes bgShift {
            0%, 100% { transform: translateX(0) translateY(0); }
            33% { transform: translateX(-20px) translateY(-20px); }
            66% { transform: translateX(20px) translateY(20px); }
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            z-index: 1000;
            border-bottom: 2px solid var(--primary-gold);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--gradient-fire);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
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
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            padding: 10px 0;
        }

        .nav-links a.active,
        .nav-links a:hover {
            color: var(--primary-gold);
            text-shadow: 0 0 10px var(--primary-gold);
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--gradient-fire);
            transition: width 0.3s ease;
        }

        .nav-links a.active::before,
        .nav-links a:hover::before {
            width: 100%;
        }

        /* Page Header */
        .page-header {
            padding: 120px 0 80px;
            text-align: center;
            background: var(--gradient-dark);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23333" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.1;
            animation: gridMove 30s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(10px, 10px); }
        }

        .page-title {
            font-family: 'Orbitron', monospace;
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-fire);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
            z-index: 10;
        }

        .page-subtitle {
            font-size: 1.3rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Filters */
        .filters {
            padding: 60px 0;
            background: var(--surface-dark);
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-btn {
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--surface-darker);
            padding: 12px 30px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-fire);
            transition: left 0.3s ease;
            z-index: 1;
        }

        .filter-btn span {
            position: relative;
            z-index: 2;
        }

        .filter-btn.active,
        .filter-btn:hover {
            border-color: var(--primary-gold);
            color: var(--darker-bg);
        }

        .filter-btn.active::before,
        .filter-btn:hover::before {
            left: 0;
        }

        /* Projects Grid */
        .projects-section {
            padding: 80px 0 120px;
            background: var(--dark-bg);
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 40px;
        }

        .project-card {
            background: var(--surface-darker);
            border: 2px solid var(--surface-dark);
            overflow: hidden;
            transition: all 0.4s ease;
            position: relative;
            opacity: 1;
            transform: scale(1);
        }

        .project-card.hidden {
            opacity: 0;
            transform: scale(0.8);
            pointer-events: none;
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-fire);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .project-card:hover::before {
            opacity: 0.05;
        }

        .project-card:hover {
            border-color: var(--primary-gold);
            transform: translateY(-10px);
            box-shadow: var(--shadow-brutal);
        }

        .project-image {
            position: relative;
            height: 250px;
            overflow: hidden;
            z-index: 2;
        }

        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            filter: brightness(0.8) contrast(1.2);
        }

        .project-card:hover .project-image img {
            transform: scale(1.1);
            filter: brightness(1) contrast(1.3);
        }

        .project-status {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 6px 15px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 3;
        }

        .status-completed {
            background: var(--accent-green);
            color: var(--darker-bg);
        }

        .status-in_progress {
            background: var(--primary-orange);
            color: var(--darker-bg);
        }

        .project-content {
            padding: 40px 30px;
            position: relative;
            z-index: 2;
        }

        .project-category {
            display: inline-block;
            background: var(--gradient-fire);
            color: var(--darker-bg);
            padding: 6px 15px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .project-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-gold);
            text-transform: uppercase;
            line-height: 1.3;
        }

        .project-description {
            color: var(--text-secondary);
            margin-bottom: 20px;
            line-height: 1.7;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 25px;
        }

        .tech-tag {
            background: rgba(255, 107, 53, 0.2);
            color: var(--primary-gold);
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid var(--primary-gold);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .project-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .project-date {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .project-client {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .project-links {
            display: flex;
            gap: 15px;
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: transparent;
            color: var(--primary-gold);
            text-decoration: none;
            border: 2px solid var(--primary-gold);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
        }

        .project-link:hover {
            background: var(--primary-gold);
            color: var(--darker-bg);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
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
            color: var(--primary-gold);
        }

        .empty-state h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--text-secondary);
        }

        /* Footer */
        .footer {
            background: var(--darker-bg);
            border-top: 2px solid var(--primary-gold);
            padding: 80px 0 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-section h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--primary-gold);
            text-transform: uppercase;
        }

        .footer-section p,
        .footer-section a {
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 15px;
            display: block;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--primary-gold);
        }

        .social-links {
            display: flex;
            gap: 20px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: var(--surface-dark);
            color: var(--primary-gold);
            border: 2px solid var(--primary-gold);
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary-gold);
            color: var(--darker-bg);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid var(--surface-dark);
            color: var(--text-muted);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .projects-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                gap: 15px;
            }
            
            .filter-btn {
                padding: 10px 20px;
                font-size: 1rem;
            }
            
            .project-links {
                flex-direction: column;
            }
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo"><?= $site_config['company_short'] ?></a>
            <ul class="nav-links">
                <li><a href="index.php">Начало</a></li>
                <li><a href="projects.php" class="active">Проекти</a></li>
                <li><a href="index.php#services">Услуги</a></li>
                <li><a href="index.php#contact">Контакт</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">Нашите Проекти</h1>
            <p class="page-subtitle">Разгледайте портфолиото ни с реализирани проекти в различни области на програмирането и дизайна</p>
        </div>
    </section>

    <!-- Filters -->
    <section class="filters">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <span>Всички Проекти</span>
                </button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn" data-filter="<?= strtolower(str_replace(' ', '-', $category)) ?>">
                        <span><?= $category ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="projects-section">
        <div class="container">
            <div class="projects-grid" id="projectsGrid">
                <?php foreach ($all_projects as $project): ?>
                    <div class="project-card fade-in" data-category="<?= strtolower(str_replace(' ', '-', $project['category'])) ?>">
                        <div class="project-image">
                            <img src="<?= $project['image'] ?>" alt="<?= $project['title'] ?>" 
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMzMzIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIE5vdCBGb3VuZDwvdGV4dD48L3N2Zz4='">
                            <div class="project-status status-<?= $project['status'] ?>">
                                <?= $project['status'] === 'completed' ? 'Завършен' : 'В процес' ?>
                            </div>
                        </div>
                        <div class="project-content">
                            <span class="project-category"><?= $project['category'] ?></span>
                            <h3 class="project-title"><?= $project['title'] ?></h3>
                            <p class="project-description"><?= $project['description'] ?></p>
                            
                            <div class="project-tech">
                                <?php foreach ($project['technologies'] as $tech): ?>
                                    <span class="tech-tag"><?= $tech ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="project-meta">
                                <div class="project-date">
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d.m.Y', strtotime($project['date'])) ?>
                                </div>
                                <div class="project-client">
                                    <i class="fas fa-user"></i>
                                    <?= $project['client'] ?>
                                </div>
                            </div>
                            
                            <div class="project-links">
                                <?php if (!empty($project['github'])): ?>
                                    <a href="<?= $project['github'] ?>" class="project-link" target="_blank">
                                        <i class="fab fa-github"></i> GitHub
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($project['demo'])): ?>
                                    <a href="<?= $project['demo'] ?>" class="project-link" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Demo
                                    </a>
                                <?php endif; ?>
                                <?php if (empty($project['github']) && empty($project['demo'])): ?>
                                    <div class="project-link" style="opacity: 0.5; cursor: not-allowed;">
                                        <i class="fas fa-lock"></i> Частен проект
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>Няма намерени проекти</h3>
                <p>Опитайте с друг филтър или се върнете към всички проекти</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?= $site_config['company_name'] ?></h3>
                    <p><?= $site_config['description'] ?></p>
                </div>
                <div class="footer-section">
                    <h3>Контакт</h3>
                    <a href="mailto:<?= $site_config['email'] ?>"><?= $site_config['email'] ?></a>
                    <a href="tel:<?= $site_config['phone'] ?>"><?= $site_config['phone'] ?></a>
                    <p><?= $site_config['address'] ?></p>
                </div>
                <div class="footer-section">
                    <h3>Последвайте ни</h3>
                    <div class="social-links">
                        <?php foreach ($social_links as $platform => $url): ?>
                            <a href="<?= $url ?>" class="social-link" target="_blank">
                                <i class="fab fa-<?= $platform ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 <?= $site_config['company_name'] ?>. Всички права запазени.</p>
            </div>
        </div>
    </footer>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');
        const emptyState = document.getElementById('emptyState');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');

                const filterValue = button.getAttribute('data-filter');
                let visibleCount = 0;

                projectCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (filterValue === 'all' || cardCategory === filterValue) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                // Show/hide empty state
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            });
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
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