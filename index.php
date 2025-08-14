<?php
require_once 'config.php';
$featured_project = getFeaturedProject();
$all_categories = getProjectCategories();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $company_config['name'] ?> - <?= $company_config['tagline'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-cyan: #00ffff;
            --neon-purple: #8a2be2;
            --neon-orange: #ff6600;
            --electric-blue: #0080ff;
            --acid-green: #39ff14;
            --hot-pink: #ff1493;
            
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-tertiary: #1a1a1a;
            --bg-card: rgba(15, 15, 15, 0.9);
            
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --text-accent: #888888;
            
            --border-glow: rgba(0, 255, 255, 0.3);
            --shadow-brutal: 0 0 30px rgba(0, 255, 255, 0.3), 0 0 60px rgba(138, 43, 226, 0.2);
            --shadow-intense: 0 0 50px rgba(255, 102, 0, 0.4), 0 0 100px rgba(255, 20, 147, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Brutal Background Animation */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(138, 43, 226, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 102, 0, 0.05) 0%, transparent 50%);
            animation: backgroundPulse 8s ease-in-out infinite alternate;
            z-index: -1;
        }

        @keyframes backgroundPulse {
            0% { opacity: 0.3; transform: scale(1); }
            100% { opacity: 0.7; transform: scale(1.1); }
        }

        /* Brutal Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--neon-cyan);
            box-shadow: var(--shadow-brutal);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(10, 10, 10, 0.98);
            box-shadow: var(--shadow-intense);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            font-size: 2rem;
            font-weight: 900;
            color: var(--neon-cyan);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            filter: drop-shadow(0 0 10px var(--neon-cyan));
            transition: all 0.3s ease;
        }

        .logo:hover {
            color: var(--neon-purple);
            filter: drop-shadow(0 0 20px var(--neon-purple));
            transform: scale(1.05);
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple));
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
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--neon-cyan);
            filter: drop-shadow(0 0 8px var(--neon-cyan));
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--electric-blue));
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::before {
            width: 120%;
        }

        /* Brutal Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: var(--bg-primary);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(45deg, transparent 30%, rgba(0, 255, 255, 0.1) 50%, transparent 70%),
                linear-gradient(-45deg, transparent 30%, rgba(138, 43, 226, 0.1) 50%, transparent 70%);
            animation: heroShine 6s ease-in-out infinite;
        }

        @keyframes heroShine {
            0%, 100% { opacity: 0.3; transform: translateX(-100%); }
            50% { opacity: 0.8; transform: translateX(100%); }
        }

        .hero-content {
            text-align: center;
            z-index: 2;
            max-width: 1000px;
            padding: 0 30px;
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 8rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 30px;
            background: linear-gradient(45deg, var(--neon-cyan), var(--neon-purple), var(--neon-orange));
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 30px rgba(0, 255, 255, 0.5));
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { filter: drop-shadow(0 0 30px rgba(0, 255, 255, 0.5)); }
            100% { filter: drop-shadow(0 0 50px rgba(138, 43, 226, 0.7)); }
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 2.5rem);
            font-weight: 300;
            color: var(--text-secondary);
            margin-bottom: 50px;
            letter-spacing: 2px;
        }

        .hero-cta {
            display: inline-block;
            padding: 20px 50px;
            background: linear-gradient(45deg, var(--neon-cyan), var(--electric-blue));
            color: var(--bg-primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 50px;
            box-shadow: var(--shadow-brutal);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .hero-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .hero-cta:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: var(--shadow-intense);
        }

        .hero-cta:hover::before {
            left: 100%;
        }

        /* Featured Project Section */
        .featured-section {
            padding: 120px 0;
            background: var(--bg-secondary);
            position: relative;
        }

        .featured-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple), var(--neon-orange));
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        .section-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            text-align: center;
            margin-bottom: 80px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--neon-cyan);
            filter: drop-shadow(0 0 20px var(--neon-cyan));
        }

        .featured-project {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            background: var(--bg-card);
            border: 2px solid var(--border-glow);
            border-radius: 20px;
            padding: 60px;
            box-shadow: var(--shadow-brutal);
            position: relative;
            overflow: hidden;
        }

        .featured-project::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(var(--neon-cyan), var(--neon-purple), var(--neon-orange), var(--neon-cyan));
            animation: rotate 10s linear infinite;
            z-index: -1;
        }

        .featured-project::after {
            content: '';
            position: absolute;
            inset: 3px;
            background: var(--bg-card);
            border-radius: 18px;
            z-index: -1;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .project-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-intense);
        }

        .project-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-image:hover img {
            transform: scale(1.1);
        }

        .project-info h3 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--neon-purple);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .project-category {
            color: var(--neon-orange);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .project-description {
            font-size: 1.2rem;
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .project-technologies {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
        }

        .tech-tag {
            background: linear-gradient(45deg, var(--neon-cyan), var(--electric-blue));
            color: var(--bg-primary);
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .project-cta {
            display: inline-block;
            padding: 15px 40px;
            background: transparent;
            color: var(--neon-cyan);
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid var(--neon-cyan);
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .project-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--neon-cyan);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .project-cta:hover {
            color: var(--bg-primary);
            box-shadow: 0 0 30px var(--neon-cyan);
        }

        .project-cta:hover::before {
            left: 0;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: var(--bg-primary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .stat-item {
            text-align: center;
            padding: 40px;
            background: var(--bg-card);
            border: 1px solid var(--border-glow);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-brutal);
            border-color: var(--neon-cyan);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: var(--neon-cyan);
            margin-bottom: 15px;
            filter: drop-shadow(0 0 10px var(--neon-cyan));
        }

        .stat-label {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 20px;
            }

            .nav-links {
                gap: 20px;
            }

            .nav-links a {
                font-size: 1rem;
            }

            .hero-content {
                padding: 0 20px;
            }

            .featured-project {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 40px;
            }

            .container {
                padding: 0 20px;
            }
        }

        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo"><?= $company_config['name'] ?></a>
            <ul class="nav-links">
                <?php foreach ($navigation as $name => $url): ?>
                    <li><a href="<?= $url ?>"><?= $name ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title"><?= $company_config['name'] ?></h1>
            <p class="hero-subtitle"><?= $company_config['tagline'] ?></p>
            <a href="projects.php" class="hero-cta">Разгледай Проекти</a>
        </div>
    </section>

    <!-- Featured Project Section -->
    <?php if ($featured_project): ?>
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title animate-on-scroll">Featured Project</h2>
            <div class="featured-project animate-on-scroll">
                <div class="project-image">
                    <img src="<?= $portfolio_config['image_path'] . $featured_project['image'] ?>" 
                         alt="<?= htmlspecialchars($featured_project['title']) ?>"
                         onerror="this.src='https://via.placeholder.com/600x400/1a1a1a/00ffff?text=<?= urlencode($featured_project['title']) ?>'">
                </div>
                <div class="project-info">
                    <div class="project-category"><?= htmlspecialchars($featured_project['category']) ?></div>
                    <h3><?= htmlspecialchars($featured_project['title']) ?></h3>
                    <p class="project-description"><?= htmlspecialchars($featured_project['description']) ?></p>
                    <div class="project-technologies">
                        <?php foreach ($featured_project['technologies'] as $tech): ?>
                            <span class="tech-tag"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <a href="projects.php" class="project-cta">Виж Всички Проекти</a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <h2 class="section-title animate-on-scroll">По Числата</h2>
            <div class="stats-grid">
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number"><?= count($projects) ?>+</div>
                    <div class="stat-label">Завършени Проекта</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number"><?= count($all_categories) ?></div>
                    <div class="stat-label">Технологични Области</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Удовлетвореност</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number"><?= $company_config['founded'] ?></div>
                    <div class="stat-label">Година Основаване</div>
                </div>
            </div>
        </div>
    </section>

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

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Enhanced background animation
        const createParticle = () => {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = Math.random() * 3 + 'px';
            particle.style.height = particle.style.width;
            particle.style.background = `rgba(${Math.random() > 0.5 ? '0, 255, 255' : '138, 43, 226'}, ${Math.random() * 0.5 + 0.3})`;
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.top = '100vh';
            particle.style.pointerEvents = 'none';
            particle.style.borderRadius = '50%';
            particle.style.zIndex = '-1';
            
            document.body.appendChild(particle);
            
            const animation = particle.animate([
                { transform: 'translateY(0) rotate(0deg)', opacity: 0 },
                { transform: `translateY(-100vh) rotate(360deg)`, opacity: 1 },
                { transform: `translateY(-120vh) rotate(720deg)`, opacity: 0 }
            ], {
                duration: Math.random() * 3000 + 2000,
                easing: 'linear'
            });
            
            animation.onfinish = () => particle.remove();
        };

        // Create particles periodically
        setInterval(createParticle, 300);
    </script>
</body>
</html>