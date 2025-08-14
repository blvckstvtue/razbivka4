<?php
require_once 'config.php';

// Получаваме всички проекти и категории
$all_projects = getAllProjects();
$categories = getProjectCategories();

// Филтриране по категория ако е зададена
$filter_category = isset($_GET['category']) ? $_GET['category'] : 'all';
$filtered_projects = $filter_category === 'all' ? $all_projects : getProjectsByCategory($filter_category);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Портфолио - <?= $company_config['name'] ?></title>
    <meta name="description" content="Разгледайте портфолиото на <?= $company_config['full_name'] ?> - завършени проекти, gaming solutions, web development и design проекти.">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-code"></i>
                <?= $company_config['name'] ?>
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#services">Услуги</a></li>
                <li><a href="index.php#featured">Проект</a></li>
                <li><a href="index.php#stats">Статистики</a></li>
                <li><a href="projects.php" class="active">Портфолио</a></li>
                <li><a href="index.php#contact">Контакт</a></li>
            </ul>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" style="min-height: 50vh;">
        <div class="hero-content">
            <h1 class="hero-title animate-fade-in-up" style="font-size: clamp(2.5rem, 6vw, 4rem);">
                Портфолио
            </h1>
            <p class="hero-subtitle animate-fade-in-up">
                Разгледайте нашите завършени проекти и реализации
            </p>
            <p class="hero-description animate-fade-in-up">
                Всеки проект е създаден с внимание към детайлите и професионален подход
            </p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="section" style="padding: 3rem 0;">
        <div class="container">
            <div class="filter-section">
                <h3 class="filter-title" style="text-align: center; margin-bottom: 2rem; color: var(--text-primary);">
                    Филтрирай по категория
                </h3>
                
                <div class="filter-buttons" style="display: flex; justify-content: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 3rem;">
                    <a href="projects.php?category=all" 
                       class="filter-btn <?= $filter_category === 'all' ? 'active' : '' ?>">
                        <i class="fas fa-th"></i>
                        Всички (<?= count($all_projects) ?>)
                    </a>
                    
                    <?php foreach ($categories as $category): ?>
                    <a href="projects.php?category=<?= urlencode($category) ?>" 
                       class="filter-btn <?= $filter_category === $category ? 'active' : '' ?>">
                        <i class="<?= getCategoryIcon($category) ?>"></i>
                        <?= $category ?> (<?= count(getProjectsByCategory($category)) ?>)
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <?php if (!empty($filtered_projects)): ?>
            <div class="projects-grid grid grid-3">
                <?php foreach ($filtered_projects as $project): ?>
                <div class="project-card animate-fade-in-up" data-category="<?= $project['category'] ?>">
                    <div class="project-image-wrapper">
                        <img src="<?= $project['image'] ?>" 
                             alt="<?= $project['title'] ?>" 
                             class="project-image"
                             onerror="this.src='https://via.placeholder.com/400x250/1A1A1A/FF6B35?text=<?= urlencode($project['title']) ?>'">
                        
                        <!-- Project Status Badge -->
                        <div class="project-status-badge status-<?= $project['status'] ?>">
                            <?= getStatusText($project['status']) ?>
                        </div>
                        
                        <!-- Project Overlay -->
                        <div class="project-overlay">
                            <div class="project-overlay-content">
                                <h4><?= $project['title'] ?></h4>
                                <p><?= substr($project['description'], 0, 100) ?>...</p>
                                <div class="project-overlay-links">
                                    <?php if ($project['demo_url'] && $project['demo_url'] !== '#'): ?>
                                    <a href="<?= $project['demo_url'] ?>" class="btn-overlay" target="_blank" title="Live Demo">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($project['github_url'] && $project['github_url'] !== '#'): ?>
                                    <a href="<?= $project['github_url'] ?>" class="btn-overlay" target="_blank" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <button class="btn-overlay" onclick="openProjectModal(<?= $project['id'] ?>)" title="Виж повече">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="project-content">
                        <div class="project-header">
                            <div class="project-category"><?= $project['category'] ?></div>
                            <div class="project-date">
                                <?= date('d.m.Y', strtotime($project['completion_date'])) ?>
                            </div>
                        </div>
                        
                        <h3 class="project-title"><?= $project['title'] ?></h3>
                        <p class="project-description"><?= $project['description'] ?></p>
                        
                        <div class="project-tech">
                            <?php foreach (array_slice($project['technologies'], 0, 4) as $tech): ?>
                            <span class="tech-tag"><?= $tech ?></span>
                            <?php endforeach; ?>
                            
                            <?php if (count($project['technologies']) > 4): ?>
                            <span class="tech-tag tech-more">+<?= count($project['technologies']) - 4 ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="project-meta">
                            <div class="project-client">
                                <i class="fas fa-user"></i>
                                <span><?= $project['client'] ?></span>
                            </div>
                            <div class="project-price">
                                <i class="fas fa-euro-sign"></i>
                                <span><?= $project['price_range'] ?></span>
                            </div>
                        </div>
                        
                        <div class="project-links">
                            <?php if ($project['demo_url'] && $project['demo_url'] !== '#'): ?>
                            <a href="<?= $project['demo_url'] ?>" class="project-link" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                Live Demo
                            </a>
                            <?php endif; ?>
                            
                            <button class="project-link" onclick="openProjectModal(<?= $project['id'] ?>)">
                                <i class="fas fa-info-circle"></i>
                                Детайли
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="no-projects" style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 4rem; color: var(--text-muted); margin-bottom: 2rem;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 style="color: var(--text-primary); margin-bottom: 1rem;">Няма намерени проекти</h3>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                    В момента няма проекти в категория "<?= $filter_category ?>"
                </p>
                <a href="projects.php?category=all" class="btn btn-primary">
                    <i class="fas fa-th"></i>
                    Виж всички проекти
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section" style="background: var(--bg-secondary);">
        <div class="container">
            <div class="cta-section" style="text-align: center; padding: 4rem 2rem;">
                <h2 style="font-size: var(--font-size-3xl); margin-bottom: 1rem; color: var(--text-primary);">
                    Харесва ви това, което виждате?
                </h2>
                <p style="font-size: var(--font-size-lg); color: var(--text-secondary); margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Нека обсъдим вашия следващ проект и как можем да го реализираме заедно
                </p>
                
                <div style="display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap;">
                    <a href="index.php#contact" class="btn btn-primary">
                        <i class="fas fa-envelope"></i>
                        Свържете се с нас
                    </a>
                    <a href="mailto:<?= $company_config['contact_email'] ?>" class="btn btn-outline">
                        <i class="fas fa-paper-plane"></i>
                        Изпратете email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Modal -->
    <div id="projectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"></h3>
                <button class="modal-close" onclick="closeProjectModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Project details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?= $company_config['full_name'] ?></h3>
                    <p><?= $company_config['description'] ?></p>
                    <div class="social-links">
                        <?php foreach ($company_config['social_media'] as $platform => $url): ?>
                        <a href="<?= $url ?>" class="social-link" target="_blank">
                            <i class="fab fa-<?= $platform ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Услуги</h3>
                    <a href="index.php#services">Custom Software Development</a>
                    <a href="index.php#services">Gaming Solutions</a>
                    <a href="index.php#services">Design & Branding</a>
                    <a href="index.php#services">Web Development</a>
                </div>
                
                <div class="footer-section">
                    <h3>Портфолио</h3>
                    <a href="projects.php?category=all">Всички проекти</a>
                    <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                    <a href="projects.php?category=<?= urlencode($category) ?>"><?= $category ?></a>
                    <?php endforeach; ?>
                </div>
                
                <div class="footer-section">
                    <h3>Контакт</h3>
                    <p><?= $company_config['contact_email'] ?></p>
                    <p><?= $company_config['phone'] ?></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $company_config['full_name'] ?>. Всички права запазени.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script>
        // Project Modal Functions
        function openProjectModal(projectId) {
            const projects = <?= json_encode($all_projects) ?>;
            const project = projects.find(p => p.id === projectId);
            
            if (!project) return;
            
            document.getElementById('modalTitle').textContent = project.title;
            document.getElementById('modalBody').innerHTML = generateModalContent(project);
            document.getElementById('projectModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeProjectModal() {
            document.getElementById('projectModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function generateModalContent(project) {
            const techTags = project.technologies.map(tech => 
                `<span class="tech-tag">${tech}</span>`
            ).join('');
            
            const demoButton = project.demo_url && project.demo_url !== '#' 
                ? `<a href="${project.demo_url}" class="btn btn-primary" target="_blank">
                     <i class="fas fa-external-link-alt"></i> Live Demo
                   </a>` 
                : '';
                
            const githubButton = project.github_url && project.github_url !== '#'
                ? `<a href="${project.github_url}" class="btn btn-outline" target="_blank">
                     <i class="fab fa-github"></i> GitHub
                   </a>`
                : '';
            
            return `
                <div class="modal-project-image">
                    <img src="${project.image}" alt="${project.title}" 
                         onerror="this.src='https://via.placeholder.com/600x300/1A1A1A/FF6B35?text=${encodeURIComponent(project.title)}'">
                    <div class="project-status-badge status-${project.status}">
                        ${getStatusTextJS(project.status)}
                    </div>
                </div>
                
                <div class="modal-project-info">
                    <div class="project-category">${project.category}</div>
                    <p class="project-description" style="font-size: var(--font-size-base); margin-bottom: 2rem;">
                        ${project.description}
                    </p>
                    
                    <div class="project-tech" style="margin-bottom: 2rem;">
                        ${techTags}
                    </div>
                    
                    <div class="project-details-grid">
                        <div class="detail-item">
                            <strong>Клиент:</strong>
                            <span>${project.client}</span>
                        </div>
                        <div class="detail-item">
                            <strong>Статус:</strong>
                            <span style="color: var(--accent); text-transform: capitalize;">${getStatusTextJS(project.status)}</span>
                        </div>
                        <div class="detail-item">
                            <strong>Дата на завършване:</strong>
                            <span>${new Date(project.completion_date).toLocaleDateString('bg-BG')}</span>
                        </div>
                        <div class="detail-item">
                            <strong>Ценови диапазон:</strong>
                            <span>${project.price_range} EUR</span>
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        ${demoButton}
                        ${githubButton}
                    </div>
                </div>
            `;
        }
        
        function getStatusTextJS(status) {
            switch(status) {
                case 'completed': return 'Завършен';
                case 'in_progress': return 'В процес';
                case 'planned': return 'Планиран';
                default: return status;
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('projectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProjectModal();
            }
        });
        
        // Filter animation
        document.addEventListener('DOMContentLoaded', function() {
            const projectCards = document.querySelectorAll('.project-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 100);
                    }
                });
            });
            
            projectCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
    
    <style>
        /* Additional styles for projects page */
        .filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 25px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }
        
        .filter-btn.active,
        .filter-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--glow);
        }
        
        .project-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 16px 16px 0 0;
        }
        
        .project-status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: var(--font-size-xs);
            font-weight: 600;
            text-transform: uppercase;
            z-index: 3;
        }
        
        .status-completed {
            background: var(--accent);
            color: white;
        }
        
        .status-in_progress {
            background: var(--secondary);
            color: var(--bg-primary);
        }
        
        .status-planned {
            background: var(--text-muted);
            color: white;
        }
        
        .project-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            opacity: 0;
            transition: opacity var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        
        .project-card:hover .project-overlay {
            opacity: 1;
        }
        
        .project-overlay-content {
            text-align: center;
            padding: 2rem;
            color: white;
        }
        
        .project-overlay-content h4 {
            font-size: var(--font-size-lg);
            margin-bottom: 1rem;
        }
        
        .project-overlay-content p {
            margin-bottom: 1.5rem;
            color: var(--text-secondary);
        }
        
        .project-overlay-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .btn-overlay {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all var(--transition-fast);
        }
        
        .btn-overlay:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }
        
        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .project-date {
            color: var(--text-muted);
            font-size: var(--font-size-sm);
        }
        
        .project-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1.5rem 0;
            font-size: var(--font-size-sm);
        }
        
        .project-client,
        .project-price {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }
        
        .tech-more {
            background: var(--bg-tertiary);
            color: var(--text-muted);
            border-color: var(--border);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .modal-content {
            background: var(--bg-card);
            border-radius: 16px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid var(--border);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        .modal-header h3 {
            color: var(--text-primary);
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all var(--transition-fast);
        }
        
        .modal-close:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-project-image {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .modal-project-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .project-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .detail-item strong {
            color: var(--primary);
            font-size: var(--font-size-sm);
        }
        
        .detail-item span {
            color: var(--text-secondary);
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .filter-buttons {
                justify-content: center !important;
            }
            
            .filter-btn {
                padding: 0.5rem 1rem;
                font-size: var(--font-size-sm);
            }
            
            .project-meta {
                grid-template-columns: 1fr;
            }
            
            .modal {
                padding: 1rem;
            }
            
            .modal-content {
                max-height: 95vh;
            }
            
            .modal-header,
            .modal-body {
                padding: 1.5rem;
            }
            
            .project-details-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>

<?php
// Helper functions for category icons and status text
function getCategoryIcon($category) {
    switch(strtolower($category)) {
        case 'gaming': return 'fas fa-gamepad';
        case 'web development': return 'fas fa-globe';
        case 'software': return 'fas fa-code';
        case 'design': return 'fas fa-paint-brush';
        case 'mobile': return 'fas fa-mobile-alt';
        default: return 'fas fa-folder';
    }
}

function getStatusText($status) {
    switch($status) {
        case 'completed': return 'Завършен';
        case 'in_progress': return 'В процес';
        case 'planned': return 'Планиран';
        default: return $status;
    }
}
?>