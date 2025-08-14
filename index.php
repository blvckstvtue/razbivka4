<?php
require_once 'config.php';
$featured_project = getFeaturedProject();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_config['company_name'] ?> - <?= $site_config['tagline'] ?></title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Meta Tags -->
    <meta name="description" content="<?= $site_config['description'] ?>">
    <meta name="keywords" content="програмиране, дизайн, игрови сървъри, уеб разработка, България">
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
            --accent-purple: #8b5cf6;
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

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.98);
            box-shadow: var(--shadow-brutal);
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
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--gradient-fire);
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
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            padding: 10px 0;
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

        .nav-links a:hover {
            color: var(--primary-gold);
            text-shadow: 0 0 10px var(--primary-gold);
        }

        .nav-links a:hover::before {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23333" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.1;
            animation: gridMove 30s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(10px, 10px); }
        }

        .hero-content {
            max-width: 1000px;
            padding: 0 20px;
            z-index: 10;
            position: relative;
        }

        .hero-title {
            font-family: 'Orbitron', monospace;
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-fire);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 0 50px rgba(255, 215, 0, 0.5);
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.5)); }
            100% { filter: drop-shadow(0 0 40px rgba(255, 107, 53, 0.8)); }
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 2rem);
            font-weight: 300;
            margin-bottom: 30px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero-description {
            font-size: 1.3rem;
            margin-bottom: 50px;
            color: var(--text-muted);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-button {
            display: inline-block;
            padding: 18px 40px;
            background: var(--gradient-fire);
            color: var(--darker-bg);
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
            border-color: var(--primary-gold);
        }

        .cta-secondary {
            background: transparent;
            color: var(--primary-gold);
            border: 2px solid var(--primary-gold);
        }

        .cta-secondary:hover {
            background: var(--primary-gold);
            color: var(--darker-bg);
        }

        /* Featured Project Section */
        .featured-section {
            padding: 120px 0;
            background: var(--surface-dark);
            position: relative;
        }

        .section-title {
            text-align: center;
            font-family: 'Orbitron', monospace;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            margin-bottom: 80px;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: var(--gradient-fire);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .featured-project {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            background: var(--surface-darker);
            border: 2px solid var(--primary-gold);
            border-radius: 0;
            overflow: hidden;
            position: relative;
        }

        .featured-project::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-fire);
            opacity: 0.05;
            z-index: 1;
        }

        .project-image {
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        .project-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.5s ease;
            filter: brightness(0.8) contrast(1.2);
        }

        .featured-project:hover .project-image img {
            transform: scale(1.1);
            filter: brightness(1) contrast(1.3);
        }

        .project-content {
            padding: 60px;
            position: relative;
            z-index: 2;
        }

        .project-category {
            display: inline-block;
            background: var(--gradient-fire);
            color: var(--darker-bg);
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .project-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 20px;
            color: var(--primary-gold);
            text-transform: uppercase;
        }

        .project-description {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 40px;
        }

        .tech-tag {
            background: rgba(255, 107, 53, 0.2);
            color: var(--primary-gold);
            padding: 6px 15px;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid var(--primary-gold);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .project-links {
            display: flex;
            gap: 20px;
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background: transparent;
            color: var(--primary-gold);
            text-decoration: none;
            border: 2px solid var(--primary-gold);
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .project-link:hover {
            background: var(--primary-gold);
            color: var(--darker-bg);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        /* Services Section */
        .services-section {
            padding: 120px 0;
            background: var(--dark-bg);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }

        .service-card {
            background: var(--surface-darker);
            border: 2px solid var(--surface-dark);
            padding: 50px 40px;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
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

        .service-card:hover::before {
            opacity: 0.1;
        }

        .service-card:hover {
            border-color: var(--primary-gold);
            transform: translateY(-10px);
            box-shadow: var(--shadow-brutal);
        }

        .service-content {
            position: relative;
            z-index: 2;
        }

        .service-icon {
            font-size: 4rem;
            color: var(--primary-gold);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            color: var(--primary-orange);
            transform: scale(1.1);
        }

        .service-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-primary);
            text-transform: uppercase;
        }

        .service-description {
            color: var(--text-secondary);
            line-height: 1.8;
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
            
            .featured-project {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .project-content {
                padding: 40px 30px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Scroll animations */
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
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo"><?= $site_config['company_short'] ?></a>
            <ul class="nav-links">
                <li><a href="index.php">Начало</a></li>
                <li><a href="projects.php">Проекти</a></li>
                <li><a href="#services">Услуги</a></li>
                <li><a href="#contact">Контакт</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title"><?= $site_config['company_name'] ?></h1>
            <p class="hero-subtitle"><?= $site_config['tagline'] ?></p>
            <p class="hero-description"><?= $site_config['description'] ?></p>
            <div class="cta-buttons">
                <a href="projects.php" class="cta-button">Разгледай Проекти</a>
                <a href="#contact" class="cta-button cta-secondary">Свържи се с нас</a>
            </div>
        </div>
    </section>

    <!-- Featured Project Section -->
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title fade-in">Избран Проект</h2>
            <div class="featured-project fade-in">
                <div class="project-image">
                    <img src="<?= $featured_project['image'] ?>" alt="<?= $featured_project['title'] ?>" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMzMzIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIE5vdCBGb3VuZDwvdGV4dD48L3N2Zz4='">
                </div>
                <div class="project-content">
                    <span class="project-category"><?= $featured_project['category'] ?></span>
                    <h3 class="project-title"><?= $featured_project['title'] ?></h3>
                    <p class="project-description"><?= $featured_project['description'] ?></p>
                    <div class="project-tech">
                        <?php foreach ($featured_project['technologies'] as $tech): ?>
                            <span class="tech-tag"><?= $tech ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="project-links">
                        <?php if (!empty($featured_project['github'])): ?>
                            <a href="<?= $featured_project['github'] ?>" class="project-link" target="_blank">
                                <i class="fab fa-github"></i> GitHub
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($featured_project['demo'])): ?>
                            <a href="<?= $featured_project['demo'] ?>" class="project-link" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Demo
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <h2 class="section-title fade-in">Нашите Услуги</h2>
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                    <div class="service-card fade-in">
                        <div class="service-content">
                            <i class="<?= $service['icon'] ?> service-icon"></i>
                            <h3 class="service-title"><?= $service['title'] ?></h3>
                            <p class="service-description"><?= $service['description'] ?></p>
                        </div>
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
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
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