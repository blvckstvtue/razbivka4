<?php
require_once 'config.php';

// Получаваме избраната категория от URL
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Ако има избран проект, показваме детайлите му
if ($project_id) {
    $selected_project = getProjectById($project_id);
}

// Получаваме проектите според категорията
$projects = getProjectsByCategory($selected_category);
$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекти - <?= $site_config['site_name'] ?></title>
    <meta name="description" content="Разгледайте всички проекти на <?= $site_config['site_name'] ?> - програмиране, дизайн, игрови сървъри и още.">
    
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
            line-height: 1.6;
            padding-top: 100px;
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

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 0;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
            z-index: 1000;
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

        .nav-links a:hover::before,
        .nav-links a.active::before {
            width: 100%;
            left: 0;
        }

        .nav-links a:hover {
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 80px;
            padding: 60px 0;
        }

        .page-title {
            font-family: 'Orbitron', monospace;
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            animation: titleGlow 3s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% { filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.3)); }
            50% { filter: drop-shadow(0 0 40px rgba(255, 107, 53, 0.5)); }
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Category Filters */
        .filter-section {
            margin-bottom: 60px;
            text-align: center;
        }

        .filter-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 12px 30px;
            border: 2px solid var(--primary);
            background: transparent;
            color: var(--light);
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary);
            color: var(--dark);
            transform: translateY(-3px);
            box-shadow: var(--glow);
        }

        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 40px;
            margin-bottom: 100px;
        }

        .project-card {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 107, 53, 0.3);
        }

        .project-image {
            position: relative;
            height: 250px;
            background: var(--gradient-3);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .project-card:hover .project-image img {
            transform: scale(1.1);
        }

        .project-image i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .project-status {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gradient-1);
            color: var(--light);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .project-content {
            padding: 30px;
        }

        .project-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .project-description {
            color: var(--light);
            margin-bottom: 20px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .project-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .project-technologies {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .tech-tag {
            background: var(--gradient-2);
            color: var(--light);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .project-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            flex: 1;
            justify-content: center;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: var(--light);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Project Detail Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--dark);
            border: 1px solid rgba(255, 107, 53, 0.2);
            border-radius: 20px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: var(--shadow-lg);
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 2rem;
            color: var(--gray);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: var(--primary);
        }

        .modal-project-image {
            height: 300px;
            background: var(--gradient-3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-project-content {
            padding: 40px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            
            .projects-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .filter-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .filter-btn {
                width: 200px;
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

        .project-card {
            animation: slideInUp 0.6s ease forwards;
            opacity: 0;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <span class="icon"><?= $site_config['logo'] ?></span>
                <span><?= $site_config['company_name'] ?></span>
            </a>
            <ul class="nav-links">
                <?php foreach ($navigation as $key => $nav): ?>
                    <li><a href="<?= $nav['url'] ?>" class="<?= $key === 'projects' ? 'active' : '' ?>"><?= $nav['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <!-- Page Header -->
        <div class="page-header fade-in">
            <h1 class="page-title">Нашите Проекти</h1>
            <p class="page-subtitle">Разгледайте най-добрите ни разработки в областта на програмирането, дизайна и игровите технологии</p>
        </div>

        <!-- Category Filters -->
        <div class="filter-section fade-in">
            <div class="filter-buttons">
                <a href="projects.php" class="filter-btn <?= $selected_category === null ? 'active' : '' ?>">
                    <i class="fas fa-th"></i> Всички
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="projects.php?category=<?= urlencode($category) ?>" class="filter-btn <?= $selected_category === $category ? 'active' : '' ?>">
                        <i class="fas fa-<?= $category === 'Gaming' ? 'gamepad' : ($category === 'Web Development' ? 'code' : 'palette') ?>"></i>
                        <?= $category ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="projects-grid">
            <?php foreach ($projects as $index => $project): ?>
                <div class="project-card fade-in" style="animation-delay: <?= $index * 0.1 ?>s;" onclick="openProjectModal(<?= $project['id'] ?>)">
                    <div class="project-image">
                        <?php if (file_exists($project['image'])): ?>
                            <img src="<?= $project['image'] ?>" alt="<?= $project['title'] ?>">
                        <?php else: ?>
                            <i class="fas fa-image"></i>
                        <?php endif; ?>
                        <div class="project-status"><?= $project['status'] === 'completed' ? 'Завършен' : 'В процес' ?></div>
                    </div>
                    
                    <div class="project-content">
                        <h3 class="project-title"><?= $project['title'] ?></h3>
                        <p class="project-description"><?= $project['description'] ?></p>
                        
                        <div class="project-meta">
                            <span><i class="fas fa-calendar"></i> <?= $project['year'] ?></span>
                            <span><i class="fas fa-tag"></i> <?= $project['category'] ?></span>
                        </div>
                        
                        <div class="project-technologies">
                            <?php foreach ($project['technologies'] as $tech): ?>
                                <span class="tech-tag"><?= $tech ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="project-actions">
                            <a href="#" class="btn btn-primary" onclick="event.stopPropagation(); openProjectModal(<?= $project['id'] ?>)">
                                <i class="fas fa-eye"></i> Детайли
                            </a>
                            <?php if ($project['link'] && $project['link'] !== '#'): ?>
                                <a href="<?= $project['link'] ?>" class="btn btn-secondary" target="_blank" onclick="event.stopPropagation();">
                                    <i class="fas fa-external-link-alt"></i> Демо
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Project Detail Modal -->
    <div id="projectModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeProjectModal()">&times;</span>
            <div id="modalContent">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
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

        // Project modal functions
        function openProjectModal(projectId) {
            const projects = <?= json_encode($projects) ?>;
            const project = projects.find(p => p.id === projectId);
            
            if (!project) return;
            
            const modal = document.getElementById('projectModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.innerHTML = `
                <div class="modal-project-image">
                    ${project.image && project.image !== 'images/project' + project.id + '.jpg' ? 
                        '<img src="' + project.image + '" alt="' + project.title + '" style="width: 100%; height: 100%; object-fit: cover;">' : 
                        '<i class="fas fa-image" style="font-size: 4rem; color: rgba(255, 255, 255, 0.3);"></i>'
                    }
                </div>
                <div class="modal-project-content">
                    <h2 style="font-family: 'Orbitron', monospace; color: var(--primary); margin-bottom: 20px;">${project.title}</h2>
                    <p style="font-size: 1.1rem; margin-bottom: 20px; line-height: 1.8;">${project.description}</p>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: var(--primary); margin-bottom: 10px;">Технологии:</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            ${project.technologies.map(tech => '<span class="tech-tag">' + tech + '</span>').join('')}
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                        <div>
                            <strong>Категория:</strong> ${project.category}
                        </div>
                        <div>
                            <strong>Година:</strong> ${project.year}
                        </div>
                        <div>
                            <strong>Статус:</strong> ${project.status === 'completed' ? 'Завършен' : 'В процес'}
                        </div>
                        <div>
                            <strong>Клиент:</strong> ${project.client}
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 15px;">
                        ${project.link && project.link !== '#' ? 
                            '<a href="' + project.link + '" class="btn btn-primary" target="_blank"><i class="fas fa-external-link-alt"></i> Live Demo</a>' : ''
                        }
                        ${project.github && project.github !== '' ? 
                            '<a href="' + project.github + '" class="btn btn-secondary" target="_blank"><i class="fab fa-github"></i> GitHub</a>' : ''
                        }
                    </div>
                </div>
            `;
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeProjectModal() {
            const modal = document.getElementById('projectModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('projectModal');
            if (event.target === modal) {
                closeProjectModal();
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            handleScrollAnimations();
            
            // Add staggered animation to project cards
            const cards = document.querySelectorAll('.project-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
        });

        window.addEventListener('scroll', handleScrollAnimations);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeProjectModal();
            }
        });
    </script>
</body>
</html>