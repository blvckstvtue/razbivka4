<?php
require_once 'config.php';

// Get filter parameters
$category_filter = isset($_GET['category']) ? $_GET['category'] : null;
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;

// Get filtered projects
$projects = getProjectsByCategory($category_filter);
if ($status_filter) {
    $projects = array_filter($projects, function($project) use ($status_filter) {
        return $project['status'] === $status_filter;
    });
}

$categories = getProjectCategories();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - <?= $site_config['company_name'] ?></title>
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
            padding-top: 80px;
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

        .nav-links a:hover, .nav-links a.active {
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

        .nav-links a:hover::after, .nav-links a.active::after {
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

        /* Page Header */
        .page-header {
            padding: 6rem 2rem 4rem;
            text-align: center;
            background: radial-gradient(ellipse at center, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
            position: relative;
        }

        .page-header::before {
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

        .page-title {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -2px;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 2;
        }

        .page-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Filters */
        .filters {
            padding: 2rem;
            background: rgba(26, 26, 26, 0.8);
            border-top: 1px solid rgba(255, 107, 53, 0.3);
            border-bottom: 1px solid rgba(255, 107, 53, 0.3);
        }

        .filters-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-label {
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            background: transparent;
            border: 2px solid rgba(255, 107, 53, 0.3);
            color: var(--text);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: var(--bg);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .clear-filters {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: var(--text);
        }

        .clear-filters:hover {
            background: var(--text);
            color: var(--bg);
            border-color: var(--text);
        }

        /* Projects Grid */
        .projects-section {
            padding: 4rem 2rem;
            min-height: 60vh;
        }

        .projects-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .projects-count {
            margin-bottom: 2rem;
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
        }

        .projects-count span {
            color: var(--primary);
            font-weight: 700;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2.5rem;
            margin-bottom: 4rem;
        }

        .project-card {
            background: rgba(255, 255, 255, 0.02);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            backdrop-filter: blur(10px);
        }

        .project-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: var(--shadow-dark);
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 107, 53, 0.05), transparent);
            transition: left 0.6s ease;
            z-index: 1;
        }

        .project-card:hover::before {
            left: 100%;
        }

        .project-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .project-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-card:hover .project-image {
            transform: scale(1.1);
        }

        .project-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }

        .status-in_progress {
            background: rgba(245, 158, 11, 0.9);
            color: white;
        }

        .project-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 2rem 1.5rem 1.5rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .project-card:hover .project-overlay {
            transform: translateY(0);
        }

        .project-links {
            display: flex;
            gap: 1rem;
        }

        .project-link {
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: var(--bg);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .project-link:hover {
            background: var(--accent);
            transform: translateY(-2px);
        }

        .project-content {
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .project-category {
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .project-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .project-description {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .tech-tag {
            background: rgba(255, 107, 53, 0.15);
            color: var(--primary);
            padding: 0.3rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            border: 1px solid rgba(255, 107, 53, 0.3);
        }

        .project-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 8px;
            border: 1px solid rgba(255, 107, 53, 0.1);
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .meta-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-weight: 600;
            color: var(--text);
        }

        .project-features {
            margin-bottom: 1.5rem;
        }

        .features-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .feature-item {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            padding-left: 1.2rem;
        }

        .feature-item::before {
            content: '▶';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-size: 0.7rem;
        }

        .project-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border-radius: 6px;
            flex: 1;
            text-align: center;
            min-width: 120px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--bg);
            box-shadow: 4px 4px 0px rgba(255, 107, 53, 0.3);
            border: 2px solid var(--primary);
        }

        .btn-primary:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 4px 4px 0px rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px rgba(255, 255, 255, 0.2);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .empty-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: var(--bg);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        }

        /* Animations */
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

            .page-title {
                font-size: clamp(2.5rem, 12vw, 4rem);
            }

            .filters-container {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .filter-group {
                flex-wrap: wrap;
            }

            .projects-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .project-meta {
                grid-template-columns: 1fr;
            }

            .project-actions {
                flex-direction: column;
            }

            .btn {
                flex: none;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 0 1rem;
            }

            .page-header {
                padding: 4rem 1rem 3rem;
            }

            .projects-section {
                padding: 2rem 1rem;
            }

            .project-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Lion</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="projects.php" class="active">Projects</a></li>
                <li><a href="index.php#services">Services</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
            <div class="mobile-menu" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <h1 class="page-title">Our Projects</h1>
        <p class="page-subtitle">Explore our portfolio of innovative solutions and creative works</p>
    </section>

    <!-- Filters -->
    <section class="filters">
        <div class="filters-container">
            <div class="filter-group">
                <span class="filter-label">Category:</span>
                <a href="projects.php" class="filter-btn <?= !$category_filter ? 'active' : '' ?>">All</a>
                <?php foreach ($categories as $category): ?>
                    <a href="projects.php?category=<?= urlencode($category) ?><?= $status_filter ? '&status=' . urlencode($status_filter) : '' ?>" 
                       class="filter-btn <?= $category_filter === $category ? 'active' : '' ?>">
                        <?= $category ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="filter-group">
                <span class="filter-label">Status:</span>
                <a href="projects.php<?= $category_filter ? '?category=' . urlencode($category_filter) : '' ?>" 
                   class="filter-btn <?= !$status_filter ? 'active' : '' ?>">All</a>
                <a href="projects.php?status=completed<?= $category_filter ? '&category=' . urlencode($category_filter) : '' ?>" 
                   class="filter-btn <?= $status_filter === 'completed' ? 'active' : '' ?>">Completed</a>
                <a href="projects.php?status=in_progress<?= $category_filter ? '&category=' . urlencode($category_filter) : '' ?>" 
                   class="filter-btn <?= $status_filter === 'in_progress' ? 'active' : '' ?>">In Progress</a>
            </div>

            <?php if ($category_filter || $status_filter): ?>
            <a href="projects.php" class="filter-btn clear-filters">
                <i class="fas fa-times"></i> Clear Filters
            </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="projects-section">
        <div class="projects-container">
            <div class="projects-count">
                Showing <span><?= count($projects) ?></span> project<?= count($projects) !== 1 ? 's' : '' ?>
                <?php if ($category_filter): ?>
                    in <span><?= $category_filter ?></span>
                <?php endif; ?>
                <?php if ($status_filter): ?>
                    with status <span><?= ucfirst(str_replace('_', ' ', $status_filter)) ?></span>
                <?php endif; ?>
            </div>

            <?php if (empty($projects)): ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open empty-icon"></i>
                    <h3 class="empty-title">No Projects Found</h3>
                    <p class="empty-description">Try adjusting your filters to see more projects.</p>
                    <a href="projects.php" class="btn btn-primary">View All Projects</a>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card animate-on-scroll" id="<?= $project['id'] ?>">
                            <div class="project-image-container">
                                <img src="<?= $project['image'] ?>" alt="<?= $project['title'] ?>" class="project-image">
                                <div class="project-status status-<?= $project['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                                </div>
                                <div class="project-overlay">
                                    <div class="project-links">
                                        <?php if (!empty($project['live_demo'])): ?>
                                            <a href="<?= $project['live_demo'] ?>" target="_blank" class="project-link">
                                                <i class="fas fa-external-link-alt"></i> Live Demo
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($project['github_link'])): ?>
                                            <a href="<?= $project['github_link'] ?>" target="_blank" class="project-link">
                                                <i class="fab fa-github"></i> GitHub
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="project-content">
                                <div class="project-category"><?= $project['category'] ?></div>
                                <h3 class="project-title"><?= $project['title'] ?></h3>
                                <p class="project-description"><?= $project['description'] ?></p>
                                
                                <div class="project-tech">
                                    <?php foreach ($project['technologies'] as $tech): ?>
                                        <span class="tech-tag"><?= $tech ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="project-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Client</span>
                                        <span class="meta-value"><?= $project['client'] ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Duration</span>
                                        <span class="meta-value"><?= $project['duration'] ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Completed</span>
                                        <span class="meta-value"><?= date('M Y', strtotime($project['date'])) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Price Range</span>
                                        <span class="meta-value"><?= $project['price_range'] ?></span>
                                    </div>
                                </div>

                                <?php if (!empty($project['features'])): ?>
                                <div class="project-features">
                                    <div class="features-title">Key Features</div>
                                    <div class="features-list">
                                        <?php foreach (array_slice($project['features'], 0, 4) as $feature): ?>
                                            <div class="feature-item"><?= $feature ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="project-actions">
                                    <a href="mailto:<?= $site_config['contact_email'] ?>?subject=Project Inquiry - <?= urlencode($project['title']) ?>" class="btn btn-primary">
                                        <i class="fas fa-envelope"></i> Get Quote
                                    </a>
                                    <a href="#" onclick="shareProject('<?= $project['title'] ?>', '<?= $project['id'] ?>')" class="btn btn-secondary">
                                        <i class="fas fa-share"></i> Share
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            const backToTop = document.getElementById('backToTop');
            
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        // Back to top functionality
        document.getElementById('backToTop').addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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

        // Share project function
        function shareProject(title, id) {
            const url = window.location.origin + window.location.pathname + '#' + id;
            
            if (navigator.share) {
                navigator.share({
                    title: title + ' - Lion Developments',
                    url: url
                });
            } else {
                // Fallback - copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    // Show notification
                    const notification = document.createElement('div');
                    notification.style.cssText = `
                        position: fixed;
                        top: 100px;
                        right: 20px;
                        background: var(--primary);
                        color: var(--bg);
                        padding: 1rem 2rem;
                        border-radius: 8px;
                        font-weight: 600;
                        z-index: 10000;
                        animation: slideIn 0.3s ease;
                    `;
                    notification.textContent = 'Project link copied to clipboard!';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                });
            }
        }

        // Mobile menu toggle
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = document.querySelector('.nav-links');
        
        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Auto-scroll to project if hash is present
        if (window.location.hash) {
            setTimeout(() => {
                const element = document.querySelector(window.location.hash);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 500);
        }
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Mobile menu styles */
        @media (max-width: 768px) {
            .nav-links.active {
                display: flex;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(10, 10, 10, 0.98);
                flex-direction: column;
                padding: 2rem;
                gap: 1rem;
                border-top: 1px solid var(--primary);
            }
        }
    </style>
</body>
</html>