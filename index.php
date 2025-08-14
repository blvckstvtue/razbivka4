<?php
require_once 'config.php';

$featured_project = getFeaturedProject();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_config['site_name'] ?> - <?= $site_config['tagline'] ?></title>
    <meta name="description" content="<?= $site_config['description'] ?>">
    
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

        .navbar.scrolled {
            background: rgba(10, 10, 10, 0.98);
            padding: 10px 0;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
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

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--gradient-main);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .logo:hover::after {
            transform: scaleX(1);
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

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: var(--gradient-dark);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 212, 255, 0.1) 0%, transparent 50%);
            animation: heroGlow 8s ease-in-out infinite alternate;
        }

        @keyframes heroGlow {
            0% { opacity: 0.3; }
            100% { opacity: 0.7; }
        }

        .hero-content {
            text-align: center;
            z-index: 2;
            max-width: 900px;
            padding: 0 30px;
            animation: heroSlideUp 1.2s ease-out;
        }

        @keyframes heroSlideUp {
            from {
                opacity: 0;
                transform: translateY(60px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 50px rgba(255, 107, 53, 0.5);
            animation: textGlow 3s ease-in-out infinite alternate;
        }

        @keyframes textGlow {
            0% { filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.3)); }
            100% { filter: drop-shadow(0 0 40px rgba(255, 107, 53, 0.6)); }
        }

        .hero .tagline {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            color: var(--text-secondary);
            margin-bottom: 40px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero .description {
            font-size: 1.3rem;
            color: var(--text-muted);
            margin-bottom: 50px;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--gradient-main);
            color: white;
            box-shadow: var(--shadow-glow);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(255, 107, 53, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--accent);
            border-color: var(--accent);
        }

        .btn-outline:hover {
            background: var(--accent);
            color: var(--background);
            box-shadow: var(--shadow-accent);
            transform: translateY(-3px);
        }

        /* Featured Project Section */
        .featured-project {
            padding: 120px 0;
            background: var(--surface);
            position: relative;
        }

        .featured-project::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 70% 30%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            position: relative;
            z-index: 2;
        }

        .section-title {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-title h2 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title p {
            font-size: 1.3rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .project-showcase {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .project-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: all 0.5s ease;
        }

        .project-image:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        }

        .project-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-image:hover img {
            transform: scale(1.05);
        }

        .project-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-main);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .project-image:hover::after {
            opacity: 0.1;
        }

        .project-info h3 {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .project-category {
            display: inline-block;
            background: var(--gradient-main);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .project-info p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 40px;
        }

        .tech-tag {
            background: var(--surface-light);
            color: var(--accent);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid var(--accent);
        }

        .project-links {
            display: flex;
            gap: 20px;
        }

        .project-links .btn {
            padding: 12px 30px;
            font-size: 1rem;
        }

        /* Services Section */
        .services {
            padding: 120px 0;
            background: var(--background);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 80px;
        }

        .service-card {
            background: var(--surface);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-main);
            opacity: 0.05;
            transition: left 0.5s ease;
        }

        .service-card:hover::before {
            left: 0;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(255, 107, 53, 0.2);
            border-color: var(--primary);
        }

        .service-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            color: var(--accent);
            text-shadow: 0 0 20px var(--accent);
        }

        .service-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-primary);
        }

        .service-card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: var(--surface);
            padding: 60px 0 30px;
            border-top: 1px solid var(--border);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .footer-section p,
        .footer-section a {
            color: var(--text-secondary);
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: var(--accent);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: var(--surface-light);
            color: var(--text-secondary);
            text-align: center;
            line-height: 50px;
            border-radius: 50%;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-links a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .project-showcase {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
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
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">LION DEVS</a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Начало</a></li>
                <li><a href="projects.php">Проекти</a></li>
                <li><a href="#services">Услуги</a></li>
                <li><a href="#contact">Контакт</a></li>
                <li><a href="admin.php">Админ</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1><?= $site_config['company_name'] ?></h1>
            <p class="tagline"><?= $site_config['tagline'] ?></p>
            <p class="description"><?= $site_config['description'] ?></p>
            <div class="cta-buttons">
                <a href="projects.php" class="btn btn-primary">Разгледай Проекти</a>
                <a href="#contact" class="btn btn-outline">Свържи се с нас</a>
            </div>
        </div>
    </section>

    <?php if ($featured_project): ?>
    <!-- Featured Project Section -->
    <section class="featured-project">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Избран Проект</h2>
                <p>Ето един от най-успешните ни проекти, който демонстрира нашите възможности</p>
            </div>
            
            <div class="project-showcase fade-in">
                <div class="project-image">
                    <img src="<?= $featured_project['image'] ?>" alt="<?= $featured_project['title'] ?>" 
                         onerror="this.src='https://via.placeholder.com/600x400/1e1e1e/ff6b35?text=<?= urlencode($featured_project['title']) ?>'">
                </div>
                
                <div class="project-info">
                    <span class="project-category"><?= $featured_project['category'] ?></span>
                    <h3><?= $featured_project['title'] ?></h3>
                    <p><?= $featured_project['description'] ?></p>
                    
                    <div class="tech-stack">
                        <?php foreach ($featured_project['technologies'] as $tech): ?>
                            <span class="tech-tag"><?= $tech ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="project-links">
                        <?php if (!empty($featured_project['project_url'])): ?>
                            <a href="<?= $featured_project['project_url'] ?>" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Виж Проекта
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($featured_project['github_url'])): ?>
                            <a href="<?= $featured_project['github_url'] ?>" target="_blank" class="btn btn-outline">
                                <i class="fab fa-github"></i> GitHub
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Нашите Услуги</h2>
                <p>Предлагаме широк спектър от технологични решения за всеки бизнес</p>
            </div>
            
            <div class="services-grid">
                <?php foreach ($services_config as $service): ?>
                <div class="service-card fade-in">
                    <div class="service-icon">
                        <i class="<?= $service['icon'] ?>"></i>
                    </div>
                    <h3><?= $service['title'] ?></h3>
                    <p><?= $service['description'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?= $site_config['company_name'] ?></h3>
                    <p><?= $site_config['description'] ?></p>
                    <div class="social-links">
                        <a href="<?= $site_config['social']['github'] ?>" target="_blank"><i class="fab fa-github"></i></a>
                        <a href="<?= $site_config['social']['linkedin'] ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                        <a href="<?= $site_config['social']['facebook'] ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="<?= $site_config['social']['instagram'] ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Контакт</h3>
                    <p><i class="fas fa-envelope"></i> <?= $site_config['email'] ?></p>
                    <p><i class="fas fa-phone"></i> <?= $site_config['phone'] ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?= $site_config['address'] ?></p>
                </div>
                
                <div class="footer-section">
                    <h3>Бързи Връзки</h3>
                    <p><a href="projects.php">Проекти</a></p>
                    <p><a href="#services">Услуги</a></p>
                    <p><a href="#contact">Контакт</a></p>
                    <p><a href="admin.php">Админ Панел</a></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $site_config['company_name'] ?>. Всички права запазени.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
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