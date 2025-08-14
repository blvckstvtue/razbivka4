<?php
require_once 'config.php';

// Get selected project if provided
$selected_project = $_GET['project'] ?? null;
$project_details = null;

if ($selected_project && isset($projects[$selected_project])) {
    $project_details = $projects[$selected_project];
}

// Get filter category
$filter_category = $_GET['category'] ?? 'all';
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекти - <?= $site_config['company_name'] ?></title>
    <meta name="description" content="Вижте нашите завършени проекти в областта на програмирането, дизайна и игровите решения.">
    
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
            padding-top: 80px;
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

        /* Page Header */
        .page-header {
            padding: 6rem 0 4rem;
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
            background: 
                radial-gradient(circle at 30% 40%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(255, 215, 0, 0.1) 0%, transparent 50%);
            animation: headerGlow 6s ease-in-out infinite alternate;
        }

        @keyframes headerGlow {
            0% {
                transform: scale(1) rotate(0deg);
                opacity: 0.7;
            }
            100% {
                transform: scale(1.1) rotate(10deg);
                opacity: 1;
            }
        }

        .page-header .container {
            position: relative;
            z-index: 2;
        }

        .page-header h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .page-header p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Filters */
        .filters {
            padding: 3rem 0;
            background: var(--bg-primary);
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .filter-btn {
            padding: 0.8rem 1.5rem;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--gradient);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-brutal);
        }

        /* Projects Grid */
        .projects {
            padding: 0 0 6rem;
            background: var(--bg-primary);
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2.5rem;
        }

        .project-card {
            background: var(--bg-secondary);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            box-shadow: var(--shadow);
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-brutal);
            border-color: var(--primary);
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }

        .project-image {
            height: 250px;
            background: var(--gradient-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .project-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient);
            opacity: 0.1;
        }

        .project-image i {
            font-size: 4rem;
            color: var(--primary);
            position: relative;
            z-index: 2;
        }

        .project-content {
            padding: 2rem;
        }

        .project-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .status-completed {
            background: var(--success);
            color: white;
        }

        .status-in_progress {
            background: var(--warning);
            color: white;
        }

        .project-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .project-description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .project-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .meta-item {
            color: var(--text-muted);
        }

        .meta-item strong {
            color: var(--text-secondary);
            display: block;
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
            font-size: 0.75rem;
            font-family: 'JetBrains Mono', monospace;
            border: 1px solid var(--border);
        }

        .project-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border-color: var(--primary);
        }

        /* Project Modal */
        .project-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .project-modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--bg-secondary);
            border-radius: 20px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            border: 1px solid var(--border);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(50px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            color: var(--primary);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
        }

        .project-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .gallery-item {
            height: 200px;
            background: var(--bg-tertiary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
        }

        .features-list {
            list-style: none;
            margin: 2rem 0;
        }

        .features-list li {
            padding: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
            color: var(--text-secondary);
        }

        .features-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--success);
            font-weight: bold;
        }

        /* Search */
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .search-box {
            width: 100%;
            max-width: 400px;
            padding: 1rem 1.5rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 25px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-box:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .search-box::placeholder {
            color: var(--text-muted);
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 0;
            color: var(--text-muted);
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--border);
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
                justify-content: center;
            }

            .project-meta {
                grid-template-columns: 1fr;
            }

            .project-actions {
                flex-direction: column;
            }

            .modal-content {
                margin: 1rem;
                max-height: 95vh;
            }
        }

        /* Animations */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

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

        /* Loading Animation */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }

        .loading i {
            font-size: 2rem;
            color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-dragon"></i>
                <?= $site_config['company_short'] ?>
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php">Начало</a></li>
                <li><a href="index.php#services">Услуги</a></li>
                <li><a href="projects.php" class="active">Проекти</a></li>
                <li><a href="index.php#contact">Контакт</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Нашите проекти</h1>
            <p>Вижте какво сме създали за нашите клиенти и как превръщаме идеите в реалност</p>
        </div>
    </section>

    <!-- Filters -->
    <section class="filters">
        <div class="container">
            <div class="search-container">
                <input type="text" class="search-box" placeholder="Търсете проект..." id="searchBox">
            </div>
            
            <div class="filter-buttons">
                <?php foreach ($categories as $cat_id => $cat_name): ?>
                <a href="?category=<?= $cat_id ?>" 
                   class="filter-btn <?= $filter_category === $cat_id ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat_name) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="projects">
        <div class="container">
            <div class="projects-grid" id="projectsGrid">
                <?php 
                $visible_projects = [];
                foreach ($projects as $project_id => $project):
                    if ($filter_category === 'all' || $project['category'] === $filter_category):
                        $visible_projects[] = [$project_id, $project];
                    endif;
                endforeach;
                
                if (empty($visible_projects)):
                ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Няма намерени проекти</h3>
                    <p>Опитайте с различен филтър или търсене</p>
                </div>
                <?php else: ?>
                
                <?php foreach ($visible_projects as [$project_id, $project]): ?>
                <div class="project-card fade-in-up" data-category="<?= $project['category'] ?>" data-title="<?= strtolower($project['title']) ?>" data-tech="<?= strtolower(implode(' ', $project['technologies'])) ?>">
                    <div class="project-image">
                        <?php
                        $icon_map = [
                            'web' => 'fas fa-globe',
                            'mobile' => 'fas fa-mobile-alt',
                            'gaming' => 'fas fa-gamepad',
                            'software' => 'fas fa-code',
                            'design' => 'fas fa-paint-brush'
                        ];
                        $icon = $icon_map[$project['category']] ?? 'fas fa-cog';
                        ?>
                        <i class="<?= $icon ?>"></i>
                    </div>
                    
                    <div class="project-content">
                        <span class="project-status status-<?= $project['status'] ?>">
                            <?= $project['status'] === 'completed' ? 'Завършен' : 'В процес' ?>
                        </span>
                        
                        <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
                        <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
                        
                        <div class="project-meta">
                            <div class="meta-item">
                                <strong>Година</strong>
                                <?= $project['year'] ?>
                            </div>
                            <div class="meta-item">
                                <strong>Времетраене</strong>
                                <?= $project['duration'] ?>
                            </div>
                            <div class="meta-item">
                                <strong>Клиент</strong>
                                <?= htmlspecialchars($project['client']) ?>
                            </div>
                            <div class="meta-item">
                                <strong>Категория</strong>
                                <?= htmlspecialchars($categories[$project['category']]) ?>
                            </div>
                        </div>
                        
                        <div class="tech-stack">
                            <?php foreach ($project['technologies'] as $tech): ?>
                                <span class="tech-tag"><?= htmlspecialchars($tech) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="project-actions">
                            <button class="btn btn-primary" onclick="openProjectModal('<?= $project_id ?>')">
                                <i class="fas fa-eye"></i>
                                Детайли
                            </button>
                            <a href="#" class="btn btn-secondary">
                                <i class="fas fa-external-link-alt"></i>
                                Live Demo
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Project Modal -->
    <div class="project-modal" id="projectModal">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close-modal" onclick="closeProjectModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        const searchBox = document.getElementById('searchBox');
        const projectsGrid = document.getElementById('projectsGrid');
        const projectCards = document.querySelectorAll('.project-card');

        searchBox.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            projectCards.forEach(card => {
                const title = card.dataset.title;
                const tech = card.dataset.tech;
                const isVisible = title.includes(searchTerm) || tech.includes(searchTerm);
                
                card.style.display = isVisible ? 'block' : 'none';
                if (isVisible) visibleCount++;
            });

            // Show no results message if needed
            const noResults = document.querySelector('.no-results');
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            } else if (visibleCount === 0) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-results';
                noResultsDiv.innerHTML = `
                    <i class="fas fa-search"></i>
                    <h3>Няма намерени проекти</h3>
                    <p>Опитайте с различно търсене</p>
                `;
                projectsGrid.appendChild(noResultsDiv);
            }
        });

        // Project modal functionality
        const projectsData = <?= json_encode($projects) ?>;

        function openProjectModal(projectId) {
            const project = projectsData[projectId];
            if (!project) return;

            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <h2 style="margin-bottom: 1rem; color: var(--text-primary);">${project.title}</h2>
                <span class="project-status status-${project.status}" style="margin-bottom: 1rem;">
                    ${project.status === 'completed' ? 'Завършен' : 'В процес'}
                </span>
                
                <p style="color: var(--text-secondary); margin: 1.5rem 0; font-size: 1.1rem; line-height: 1.7;">
                    ${project.description}
                </p>
                
                <div class="project-meta" style="margin: 2rem 0;">
                    <div class="meta-item">
                        <strong>Клиент</strong>
                        ${project.client}
                    </div>
                    <div class="meta-item">
                        <strong>Година</strong>
                        ${project.year}
                    </div>
                    <div class="meta-item">
                        <strong>Времетраене</strong>
                        ${project.duration}
                    </div>
                    <div class="meta-item">
                        <strong>Статус</strong>
                        ${project.status === 'completed' ? 'Завършен' : 'В процес'}
                    </div>
                </div>
                
                <h3 style="color: var(--primary); margin: 2rem 0 1rem;">Използвани технологии</h3>
                <div class="tech-stack">
                    ${project.technologies.map(tech => `<span class="tech-tag">${tech}</span>`).join('')}
                </div>
                
                <h3 style="color: var(--primary); margin: 2rem 0 1rem;">Ключови функции</h3>
                <ul class="features-list">
                    ${project.features.map(feature => `<li>${feature}</li>`).join('')}
                </ul>
                
                <div class="project-gallery">
                    ${project.gallery.map(img => `
                        <div class="gallery-item">
                            <i class="fas fa-image" style="font-size: 2rem; color: var(--text-muted);"></i>
                        </div>
                    `).join('')}
                </div>
                
                <div class="project-actions" style="margin-top: 2rem;">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i>
                        Вижте проекта
                    </a>
                    <a href="index.php#contact" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i>
                        Свържете се с нас
                    </a>
                </div>
            `;

            document.getElementById('projectModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProjectModal() {
            document.getElementById('projectModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('projectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProjectModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProjectModal();
            }
        });

        // Scroll animations
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

        // Check if a specific project should be opened
        <?php if ($selected_project && $project_details): ?>
        // Auto-open project modal if project parameter is provided
        setTimeout(() => {
            openProjectModal('<?= $selected_project ?>');
        }, 500);
        <?php endif; ?>
    </script>
</body>
</html>