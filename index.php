<?php
require_once 'config.php';

// Get featured project
$featured = $projects[$featured_project] ?? null;
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $seo['title'] ?></title>
    <meta name="description" content="<?= $seo['description'] ?>">
    <meta name="keywords" content="<?= $seo['keywords'] ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $seo['title'] ?>">
    <meta property="og:description" content="<?= $seo['description'] ?>">
    <meta property="og:image" content="<?= $seo['og_image'] ?>">
    <meta property="og:type" content="website">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --primary: <?= $theme['primary_color'] ?>;
            --secondary: <?= $theme['secondary_color'] ?>;
            --accent: <?= $theme['accent_color'] ?>;
            --success: <?= $theme['success_color'] ?>;
            --danger: <?= $theme['danger_color'] ?>;
            --warning: <?= $theme['warning_color'] ?>;
            
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --text-muted: #888888;
            --border: #333333;
            --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
            --shadow-brutal: 0 35px 70px -15px rgba(255, 107, 53, 0.3);
            --gradient: linear-gradient(135deg, var(--primary) 0%, #ff8f65 100%);
            --gradient-dark: linear-gradient(135deg, #111111 0%, #222222 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ff8f65;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border);
        }

        .navbar.scrolled {
            background: rgba(10, 10, 10, 0.98);
            box-shadow: var(--shadow);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo i {
            color: var(--primary);
            filter: drop-shadow(0 0 10px var(--primary));
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--text-primary);
            background: var(--bg-tertiary);
            transform: translateY(-2px);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--gradient);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 80%;
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .mobile-menu span {
            width: 25px;
            height: 3px;
            background: var(--text-primary);
            transition: 0.3s;
            border-radius: 2px;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
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
                radial-gradient(circle at 20% 50%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(255, 107, 53, 0.05) 0%, transparent 50%);
            animation: heroGlow 8s ease-in-out infinite alternate;
        }

        @keyframes heroGlow {
            0% {
                transform: scale(1) rotate(0deg);
                opacity: 0.7;
            }
            100% {
                transform: scale(1.05) rotate(5deg);
                opacity: 1;
            }
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 50px rgba(255, 107, 53, 0.3);
        }

        .hero-content .tagline {
            font-size: 1.5rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .hero-content .description {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            line-height: 1.8;
        }

        .hero-cta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: var(--shadow-brutal);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 40px 80px -20px rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-brutal);
        }

        /* Featured Project */
        .featured-project {
            background: var(--bg-secondary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .featured-project::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }

        .featured-project .tag {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .featured-project h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .featured-project .description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .tech-tag {
            background: var(--bg-tertiary);
            color: var(--accent);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-family: 'JetBrains Mono', monospace;
            border: 1px solid var(--border);
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .project-link:hover {
            transform: translateX(5px);
        }

        /* Services Section */
        .services {
            padding: 6rem 0;
            background: var(--bg-primary);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 900;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .section-header p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: var(--bg-secondary);
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
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
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .service-card:hover::before {
            opacity: 0.05;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-brutal);
            border-color: var(--primary);
        }

        .service-card > * {
            position: relative;
            z-index: 2;
        }

        .service-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 0 20px var(--primary));
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .service-card .description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .service-technologies {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .tech-tag.small {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
        }

        /* Contact Section */
        .contact {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .contact-info {
            space-y: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .contact-item i {
            font-size: 1.5rem;
            color: var(--primary);
            width: 30px;
        }

        .contact-form {
            background: var(--bg-primary);
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        /* Footer */
        .footer {
            background: var(--bg-primary);
            padding: 3rem 0 1rem;
            border-top: 1px solid var(--border);
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer h4 {
            color: var(--primary);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .footer p,
        .footer a {
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: flex;
            }

            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-cta {
                justify-content: center;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Floating Elements */
        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Code Animation */
        .code-animation {
            font-family: 'JetBrains Mono', monospace;
            background: var(--bg-tertiary);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            margin: 1rem 0;
            position: relative;
            overflow: hidden;
        }

        .code-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 107, 53, 0.1), transparent);
            animation: codeGlow 3s ease-in-out infinite;
        }

        @keyframes codeGlow {
            0%, 100% {
                left: -100%;
            }
            50% {
                left: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-dragon"></i>
                <?= $site_config['company_short'] ?>
            </a>
            
            <ul class="nav-links">
                <li><a href="#home" class="active">Начало</a></li>
                <li><a href="#services">Услуги</a></li>
                <li><a href="projects.php">Проекти</a></li>
                <li><a href="#contact">Контакт</a></li>
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
        <div class="hero-container">
            <div class="hero-content fade-in-up">
                <h1><?= $site_config['company_name'] ?></h1>
                <p class="tagline"><?= $site_config['tagline'] ?></p>
                <p class="description"><?= $site_config['description'] ?></p>
                
                <div class="hero-cta">
                    <a href="projects.php" class="btn btn-primary">
                        Вижте проектите ни
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#contact" class="btn btn-secondary">
                        Свържете се с нас
                    </a>
                </div>

                <div class="code-animation">
                    <span style="color: var(--accent);">const</span> 
                    <span style="color: var(--primary);">lionDevs</span> = {<br>
                    &nbsp;&nbsp;<span style="color: var(--accent);">mission</span>: 
                    <span style="color: #4ade80;">'превръщаме идеи в реалност'</span>,<br>
                    &nbsp;&nbsp;<span style="color: var(--accent);">quality</span>: 
                    <span style="color: var(--primary);">100</span><span style="color: var(--text-secondary);">%</span><br>
                    };
                </div>
            </div>
            
            <div class="hero-project fade-in-up floating">
                <?php if ($featured): ?>
                <div class="featured-project">
                    <span class="tag">Последен проект</span>
                    <h3><?= htmlspecialchars($featured['title']) ?></h3>
                    <p class="description"><?= htmlspecialchars($featured['description']) ?></p>
                    
                    <div class="tech-stack">
                        <?php foreach ($featured['technologies'] as $tech): ?>
                            <span class="tech-tag"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="projects.php?project=<?= $featured_project ?>" class="project-link">
                        Вижте повече <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2>Нашите услуги</h2>
                <p>Предлагаме пълен спектър от IT услуги за превръщане на вашите идеи в реалност</p>
            </div>
            
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="<?= $service['icon'] ?>"></i>
                    </div>
                    <h3><?= htmlspecialchars($service['title']) ?></h3>
                    <p class="description"><?= htmlspecialchars($service['description']) ?></p>
                    <div class="service-technologies">
                        <?php foreach ($service['technologies'] as $tech): ?>
                            <span class="tech-tag small"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2>Свържете се с нас</h2>
                <p>Готови сме да превърнем вашата идея в реалност</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info fade-in-up">
                    <h3 style="margin-bottom: 2rem; color: var(--text-primary);">Как да ни намерите</h3>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong><br>
                            <a href="mailto:<?= $contact['email'] ?>"><?= $contact['email'] ?></a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Телефон</strong><br>
                            <a href="tel:<?= $contact['phone'] ?>"><?= $contact['phone'] ?></a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fab fa-discord"></i>
                        <div>
                            <strong>Discord</strong><br>
                            <?= $contact['discord'] ?>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fab fa-github"></i>
                        <div>
                            <strong>GitHub</strong><br>
                            <a href="<?= $contact['github'] ?>" target="_blank">github.com/liondevs</a>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form fade-in-up">
                    <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">Изпратете съобщение</h3>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="name">Име</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Тема</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Съобщение</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Изпратете съобщение
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <h4><?= $site_config['company_name'] ?></h4>
                    <p><?= $site_config['description'] ?></p>
                    <p><strong>Адрес:</strong> <?= $site_config['address'] ?></p>
                </div>
                
                <div>
                    <h4>Бързи връзки</h4>
                    <a href="#home">Начало</a>
                    <a href="#services">Услуги</a>
                    <a href="projects.php">Проекти</a>
                    <a href="#contact">Контакт</a>
                </div>
                
                <div>
                    <h4>Контакт</h4>
                    <a href="mailto:<?= $contact['email'] ?>"><?= $contact['email'] ?></a>
                    <a href="tel:<?= $contact['phone'] ?>"><?= $contact['phone'] ?></a>
                    <a href="<?= $contact['github'] ?>" target="_blank">GitHub</a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $site_config['company_name'] ?>. Всички права запазени.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
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
        
        mobileMenu.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        // Add fade-in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(el);
        });

        // Particles effect for hero background
        function createParticle() {
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = '2px';
            particle.style.height = '2px';
            particle.style.background = 'var(--primary)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.opacity = '0.5';
            
            const hero = document.querySelector('.hero');
            hero.appendChild(particle);
            
            const x = Math.random() * hero.offsetWidth;
            const y = Math.random() * hero.offsetHeight;
            
            particle.style.left = x + 'px';
            particle.style.top = y + 'px';
            
            const animation = particle.animate([
                { transform: 'translate(0, 0)', opacity: 0.5 },
                { transform: `translate(${(Math.random() - 0.5) * 100}px, ${(Math.random() - 0.5) * 100}px)`, opacity: 0 }
            ], {
                duration: 3000,
                easing: 'ease-out'
            });
            
            animation.onfinish = () => particle.remove();
        }

        // Create particles periodically
        setInterval(createParticle, 200);
    </script>
</body>
</html>