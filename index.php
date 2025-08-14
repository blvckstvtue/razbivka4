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
    <meta name="description" content="<?= $seo['meta_description'] ?>">
    <meta name="keywords" content="<?= $seo['meta_keywords'] ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $site_config['site_name'] ?>">
    <meta property="og:description" content="<?= $seo['meta_description'] ?>">
    <meta property="og:image" content="<?= $seo['og_image'] ?>">
    <meta property="og:type" content="website">
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: <?= $site_config['primary_color'] ?>;
            --secondary: <?= $site_config['secondary_color'] ?>;
            --accent: <?= $site_config['accent_color'] ?>;
            --dark: #0a0a0a;
            --light: #ffffff;
            --gray: #6b7280;
            --gray-light: #f3f4f6;
            --gradient-1: linear-gradient(135deg, var(--primary), #ff8f5a);
            --gradient-2: linear-gradient(135deg, var(--secondary), var(--accent));
            --gradient-3: linear-gradient(135deg, #667eea, #764ba2);
            --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --shadow-lg: 0 35px 60px -12px rgba(0, 0, 0, 0.4);
            --glow: 0 0 20px rgba(255, 107, 53, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(-45deg, #0a0a0a, var(--secondary), var(--accent), #2d1b69);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--primary);
            border-radius: 50%;
            animation: float 20s infinite linear;
            opacity: 0.1;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 0;
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 15px 0;
            background: rgba(10, 10, 10, 0.95);
            box-shadow: var(--shadow);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(var(--glow));
        }

        .logo .icon {
            font-size: 2rem;
            color: var(--primary);
            animation: rotateLion 4s ease-in-out infinite;
        }

        @keyframes rotateLion {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
        }

        .nav-links a {
            color: var(--light);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            position: relative;
            transition: all 0.3s ease;
            padding: 10px 0;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--gradient-1);
            transition: all 0.3s ease;
        }

        .nav-links a:hover::before {
            width: 100%;
            left: 0;
        }

        .nav-links a:hover {
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            max-width: 1000px;
            padding: 0 20px;
            z-index: 2;
        }

        .hero-title {
            font-family: 'Orbitron', monospace;
            font-size: clamp(3rem, 8vw, 7rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
            animation: titleGlow 3s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% { filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.3)); }
            50% { filter: drop-shadow(0 0 40px rgba(255, 107, 53, 0.5)); }
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 2rem);
            color: var(--gray);
            margin-bottom: 30px;
            font-weight: 300;
        }

        .hero-description {
            font-size: 1.2rem;
            color: var(--light);
            margin-bottom: 50px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 80px;
        }

        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: var(--light);
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: var(--shadow-lg);
            filter: brightness(1.1);
        }

        .btn-secondary {
            background: transparent;
            color: var(--light);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            transform: translateY(-5px) scale(1.05);
            box-shadow: var(--glow);
        }

        /* Featured Project Section */
        .featured-section {
            padding: 100px 0;
            position: relative;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-title h2 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .featured-project {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .featured-project:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 107, 53, 0.3);
        }

        .project-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 60px;
            padding: 60px;
        }

        .project-info h3 {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .project-info p {
            font-size: 1.1rem;
            color: var(--light);
            margin-bottom: 30px;
            line-height: 1.8;
            opacity: 0.9;
        }

        .project-technologies {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }

        .tech-tag {
            background: var(--gradient-2);
            color: var(--light);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .project-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            aspect-ratio: 16/10;
            background: var(--gradient-3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-image i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            
            .project-content {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 40px 20px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        /* Scroll Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    
    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <span class="icon"><?= $site_config['logo'] ?></span>
                <span><?= $site_config['company_name'] ?></span>
            </a>
            <ul class="nav-links">
                <?php foreach ($navigation as $key => $nav): ?>
                    <li><a href="<?= $nav['url'] ?>" class="<?= $key === 'home' ? 'active' : '' ?>"><?= $nav['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title"><?= $site_config['site_name'] ?></h1>
            <p class="hero-subtitle"><?= $site_config['tagline'] ?></p>
            <p class="hero-description"><?= $site_config['description'] ?></p>
            
            <div class="cta-buttons">
                <a href="projects.php" class="btn btn-primary">
                    <i class="fas fa-rocket"></i>
                    Разгледай Проекти
                </a>
                <a href="#contact" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i>
                    Свържи се с нас
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Project Section -->
    <?php if ($featured_project): ?>
    <section class="featured-section">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Най-новият ни проект</h2>
                <p>Вижте най-актуалния проект от нашето портфолио</p>
            </div>
            
            <div class="featured-project fade-in">
                <div class="project-content">
                    <div class="project-info">
                        <h3><?= $featured_project['title'] ?></h3>
                        <p><?= $featured_project['description'] ?></p>
                        
                        <div class="project-technologies">
                            <?php foreach ($featured_project['technologies'] as $tech): ?>
                                <span class="tech-tag"><?= $tech ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="cta-buttons">
                            <a href="projects.php?id=<?= $featured_project['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Виж детайли
                            </a>
                            <?php if ($featured_project['link'] && $featured_project['link'] !== '#'): ?>
                                <a href="<?= $featured_project['link'] ?>" class="btn btn-secondary" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    Live Demo
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="project-image">
                        <?php if (file_exists($featured_project['image'])): ?>
                            <img src="<?= $featured_project['image'] ?>" alt="<?= $featured_project['title'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-image"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Create floating particles
        function createParticles() {
            const particles = document.getElementById('particles');
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particles.appendChild(particle);
            }
        }

        // Scroll animations
        function handleScrollAnimations() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('visible');
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
            handleScrollAnimations();
        });

        window.addEventListener('scroll', handleScrollAnimations);
    </script>
</body>
</html>