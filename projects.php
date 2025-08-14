<?php
require_once 'config.php';

// Get filter parameters
$category_filter = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';
$search_query = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get projects based on filters
$all_projects = getAllProjects();
$filtered_projects = $all_projects;

// Apply category filter
if ($category_filter && $category_filter !== 'all') {
    $filtered_projects = getProjectsByCategory($category_filter);
}

// Apply search filter
if ($search_query) {
    $filtered_projects = array_filter($filtered_projects, function($project) use ($search_query) {
        return stripos($project['title'], $search_query) !== false ||
               stripos($project['description'], $search_query) !== false ||
               in_array(strtolower($search_query), array_map('strtolower', $project['technologies']));
    });
}

$all_categories = getProjectCategories();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекти - <?= $company_config['name'] ?></title>
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
            padding-top: 80px;
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

        .nav-links a:hover, .nav-links a.active {
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

        .nav-links a:hover::before, .nav-links a.active::before {
            width: 120%;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* Page Header */
        .page-header {
            padding: 60px 0;
            text-align: center;
            background: var(--bg-secondary);
            position: relative;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple), var(--neon-orange));
        }

        .page-title {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 20px;
            background: linear-gradient(45deg, var(--neon-cyan), var(--neon-purple), var(--neon-orange));
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 30px rgba(0, 255, 255, 0.5));
        }

        .page-subtitle {
            font-size: 1.5rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Filters Section */
        .filters-section {
            padding: 60px 0;
            background: var(--bg-primary);
        }

        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
            max-width: 500px;
        }

        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            background: var(--bg-card);
            border: 2px solid var(--border-glow);
            border-radius: 50px;
            color: var(--text-primary);
            font-size: 1.1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
        }

        .search-button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(45deg, var(--neon-cyan), var(--electric-blue));
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: var(--bg-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 0 15px var(--neon-cyan);
        }

        .category-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-btn {
            padding: 12px 25px;
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--border-glow);
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .filter-btn:hover, .filter-btn.active {
            color: var(--bg-primary);
            background: var(--neon-cyan);
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
        }

        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .project-card {
            background: var(--bg-card);
            border: 2px solid var(--border-glow);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            position: relative;
            cursor: pointer;
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-brutal);
            border-color: var(--neon-cyan);
        }

        .project-card:hover::before {
            opacity: 1;
        }

        .project-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
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
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-live {
            background: var(--acid-green);
            color: var(--bg-primary);
        }

        .status-development {
            background: var(--neon-orange);
            color: var(--bg-primary);
        }

        .status-delivered {
            background: var(--neon-purple);
            color: var(--text-primary);
        }

        .project-content {
            padding: 30px;
        }

        .project-category {
            color: var(--neon-orange);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .project-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .project-description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .project-technologies {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .tech-tag {
            background: rgba(0, 255, 255, 0.1);
            color: var(--neon-cyan);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid rgba(0, 255, 255, 0.3);
        }

        .project-year {
            color: var(--text-accent);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 80px 0;
            color: var(--text-secondary);
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--neon-cyan);
        }

        .no-results h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--text-primary);
        }

        /* Results Counter */
        .results-counter {
            text-align: center;
            margin-bottom: 40px;
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .results-counter span {
            color: var(--neon-cyan);
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }

            .nav-container {
                padding: 0 20px;
            }

            .nav-links {
                gap: 20px;
            }

            .nav-links a {
                font-size: 1rem;
            }

            .filters-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
                max-width: none;
            }

            .category-filters {
                justify-content: center;
            }

            .projects-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .project-card {
                max-width: none;
            }
        }

        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
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
                    <li><a href="<?= $url ?>" <?= ($url === 'projects.php') ? 'class="active"' : '' ?>><?= $name ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">Нашите Проекти</h1>
            <p class="page-subtitle">Разгледайте нашето портфолио от иновативни решения и успешни проекти</p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <div class="filters-container">
                <form method="GET" class="search-box">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Търсете проекти, технологии..."
                           value="<?= htmlspecialchars($search_query) ?>">
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category_filter) ?>">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="category-filters">
                    <a href="?search=<?= urlencode($search_query) ?>" 
                       class="filter-btn <?= empty($category_filter) ? 'active' : '' ?>">
                        Всички
                    </a>
                    <?php foreach ($all_categories as $category): ?>
                        <a href="?category=<?= urlencode($category) ?>&search=<?= urlencode($search_query) ?>" 
                           class="filter-btn <?= ($category_filter === $category) ? 'active' : '' ?>">
                            <?= htmlspecialchars($category) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Results Counter -->
            <div class="results-counter">
                Показани <span><?= count($filtered_projects) ?></span> от <span><?= count($all_projects) ?></span> проекта
            </div>

            <!-- Projects Grid -->
            <?php if (empty($filtered_projects)): ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Няма намерени проекти</h3>
                    <p>Опитайте с различни филтри или ключови думи</p>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($filtered_projects as $project): ?>
                        <div class="project-card animate-on-scroll">
                            <div class="project-image">
                                <img src="<?= $portfolio_config['image_path'] . $project['image'] ?>" 
                                     alt="<?= htmlspecialchars($project['title']) ?>"
                                     onerror="this.src='https://via.placeholder.com/400x250/1a1a1a/00ffff?text=<?= urlencode($project['title']) ?>'">
                                <div class="project-status status-<?= $project['status'] ?>">
                                    <?= htmlspecialchars($project['status']) ?>
                                </div>
                            </div>
                            <div class="project-content">
                                <div class="project-category"><?= htmlspecialchars($project['category']) ?></div>
                                <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
                                <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
                                <div class="project-technologies">
                                    <?php foreach (array_slice($project['technologies'], 0, 4) as $tech): ?>
                                        <span class="tech-tag"><?= htmlspecialchars($tech) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($project['technologies']) > 4): ?>
                                        <span class="tech-tag">+<?= count($project['technologies']) - 4 ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="project-year"><?= htmlspecialchars($project['year']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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

        // Enhanced background particles
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
        setInterval(createParticle, 400);

        // Project card click handler
        document.querySelectorAll('.project-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Add click effect
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(0, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = (e.clientX - card.getBoundingClientRect().left) + 'px';
                ripple.style.top = (e.clientY - card.getBoundingClientRect().top) + 'px';
                ripple.style.width = ripple.style.height = '20px';
                ripple.style.pointerEvents = 'none';
                
                card.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>