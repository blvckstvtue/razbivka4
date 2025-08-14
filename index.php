<?php
require_once 'config.php';
$featured_project = getFeaturedProject();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_config['company_name'] ?> - <?= $site_config['company_slogan'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=JetBrains+Mono:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: <?= $theme_config['primary_color'] ?>;
            --secondary: <?= $theme_config['secondary_color'] ?>;
            --accent: <?= $theme_config['accent_color'] ?>;
            --bg: <?= $theme_config['background_color'] ?>;
            --text: <?= $theme_config['text_color'] ?>;
            --gradient-primary: <?= $theme_config['gradient_primary'] ?>;
            --gradient-secondary: <?= $theme_config['gradient_secondary'] ?>;
            --shadow-brutal: 8px 8px 0px rgba(255, 107, 53, 0.3);
            --shadow-brutal-hover: 12px 12px 0px rgba(255, 107, 53, 0.4);
            --shadow-dark: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--primary);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: rgba(10, 10, 10, 0.98);
            box-shadow: var(--shadow-dark);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: -1px;
            position: relative;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo::before {
            content: '<';
            color: var(--accent);
            margin-right: 5px;
        }

        .logo::after {
            content: '/>';
            color: var(--accent);
            margin-left: 5px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
        }

        .nav-links a:hover {
            color: var(--primary);
            transform: translateY(-2px);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .mobile-menu span {
            width: 25px;
            height: 3px;
            background: var(--primary);
            transition: all 0.3s ease;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: radial-gradient(ellipse at center, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(45deg, transparent 24%, rgba(255, 107, 53, 0.03) 25%, rgba(255, 107, 53, 0.03) 26%, transparent 27%, transparent 74%, rgba(255, 107, 53, 0.03) 75%, rgba(255, 107, 53, 0.03) 76%, transparent 77%),
                linear-gradient(-45deg, transparent 24%, rgba(255, 107, 53, 0.03) 25%, rgba(255, 107, 53, 0.03) 26%, transparent 27%, transparent 74%, rgba(255, 107, 53, 0.03) 75%, rgba(255, 107, 53, 0.03) 76%, transparent 77%);
            background-size: 60px 60px;
            opacity: 0.5;
        }

        .hero-content {
            text-align: center;
            max-width: 1000px;
            padding: 0 2rem;
            z-index: 2;
            position: relative;
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 8rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -2px;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.5)); }
            to { filter: drop-shadow(0 0 40px rgba(255, 107, 53, 0.8)); }
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 2rem);
            font-weight: 300;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-family: 'JetBrains Mono', monospace;
        }

        .hero-description {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }

        .btn {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--bg);
            box-shadow: var(--shadow-brutal);
            border: 3px solid var(--primary);
        }

        .btn-primary:hover {
            transform: translate(-4px, -4px);
            box-shadow: var(--shadow-brutal-hover);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            border: 3px solid var(--text);
            box-shadow: 8px 8px 0px rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: var(--text);
            color: var(--bg);
            transform: translate(-4px, -4px);
            box-shadow: 12px 12px 0px rgba(255, 255, 255, 0.2);
        }

        /* Featured Project Section */
        .featured-project {
            padding: 8rem 2rem;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.05) 0%, rgba(26, 26, 26, 0.8) 100%);
            position: relative;
        }

        .section-title {
            text-align: center;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            margin-bottom: 4rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--primary);
        }

        .project-showcase {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .project-info {
            z-index: 2;
        }

        .project-category {
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .project-title {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .project-description {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .tech-tag {
            background: rgba(255, 107, 53, 0.2);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid var(--primary);
        }

        .project-visual {
            position: relative;
            height: 500px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-dark);
            border: 3px solid var(--primary);
        }

        .project-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-visual:hover .project-image {
            transform: scale(1.05);
        }

        .project-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 107, 53, 0.8), rgba(255, 210, 63, 0.6));
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-visual:hover .project-overlay {
            opacity: 1;
        }

        .view-project {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Stats Section */
        .stats {
            padding: 6rem 2rem;
            background: var(--secondary);
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 107, 53, 0.05);
            border: 2px solid var(--primary);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-brutal);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: var(--primary);
            margin-bottom: 1rem;
            font-family: 'JetBrains Mono', monospace;
        }

        .stat-label {
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Services Preview */
        .services-preview {
            padding: 8rem 2rem;
            background: linear-gradient(135deg, rgba(10, 10, 10, 0.95) 0%, rgba(26, 26, 26, 0.9) 100%);
        }

        .services-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.02);
            border: 2px solid rgba(255, 107, 53, 0.3);
            border-radius: 12px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            background: linear-gradient(90deg, transparent, rgba(255, 107, 53, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .service-card:hover::before {
            left: 100%;
        }

        .service-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: var(--shadow-dark);
        }

        .service-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .service-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .service-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: var(--bg);
            border-top: 3px solid var(--primary);
            padding: 4rem 2rem 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            line-height: 1.8;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--primary);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: rgba(255, 107, 53, 0.1);
            border: 2px solid var(--primary);
            border-radius: 8px;
            color: var(--primary);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            color: var(--bg);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 107, 53, 0.3);
            color: rgba(255, 255, 255, 0.5);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: flex;
            }

            .hero-title {
                font-size: clamp(2.5rem, 12vw, 5rem);
            }

            .project-showcase {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .project-visual {
                order: -1;
                height: 300px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 0 1rem;
            }

            .hero-content {
                padding: 0 1rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Lion</a>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="mobile-menu" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title"><?= $site_config['company_name'] ?></h1>
            <p class="hero-subtitle"><?= $site_config['company_slogan'] ?></p>
            <p class="hero-description"><?= $site_config['company_description'] ?></p>
            <div class="cta-buttons">
                <a href="projects.php" class="btn btn-primary">View Projects</a>
                <a href="#contact" class="btn btn-secondary">Get In Touch</a>
            </div>
        </div>
    </section>

    <!-- Featured Project -->
    <?php if ($featured_project): ?>
    <section class="featured-project" id="featured">
        <h2 class="section-title animate-on-scroll">Featured Project</h2>
        <div class="project-showcase">
            <div class="project-info animate-on-scroll">
                <div class="project-category"><?= $featured_project['category'] ?></div>
                <h3 class="project-title"><?= $featured_project['title'] ?></h3>
                <p class="project-description"><?= $featured_project['description'] ?></p>
                <div class="project-tech">
                    <?php foreach ($featured_project['technologies'] as $tech): ?>
                        <span class="tech-tag"><?= $tech ?></span>
                    <?php endforeach; ?>
                </div>
                <a href="projects.php#<?= $featured_project['id'] ?>" class="btn btn-primary">View Details</a>
            </div>
            <div class="project-visual animate-on-scroll">
                <img src="<?= $featured_project['image'] ?>" alt="<?= $featured_project['title'] ?>" class="project-image">
                <div class="project-overlay">
                    <span class="view-project">View Project</span>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item animate-on-scroll">
                <div class="stat-number">50+</div>
                <div class="stat-label">Projects Completed</div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-number">25+</div>
                <div class="stat-label">Happy Clients</div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-number">3+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="services-preview" id="services">
        <h2 class="section-title animate-on-scroll">Our Services</h2>
        <div class="services-grid">
            <?php foreach ($services_config['services'] as $service): ?>
            <div class="service-card animate-on-scroll">
                <i class="service-icon <?= $service['icon'] ?>"></i>
                <h3 class="service-title"><?= $service['title'] ?></h3>
                <p class="service-description"><?= $service['description'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?= $site_config['company_name'] ?></h3>
                <p><?= $site_config['company_description'] ?></p>
                <div class="social-links">
                    <?php foreach ($site_config['social_links'] as $platform => $url): ?>
                        <a href="<?= $url ?>" target="_blank">
                            <i class="fab fa-<?= $platform ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p><i class="fas fa-envelope"></i> <?= $site_config['contact_email'] ?></p>
                <p><i class="fas fa-phone"></i> <?= $site_config['phone'] ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <?= $site_config['address'] ?></p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="#home">Home</a></p>
                <p><a href="projects.php">Projects</a></p>
                <p><a href="#services">Services</a></p>
                <p><a href="#contact">Contact</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 <?= $site_config['company_name'] ?>. All rights reserved.</p>
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

        // Animate on scroll
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

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
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

        // Mobile menu toggle
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = document.querySelector('.nav-links');
        
        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>