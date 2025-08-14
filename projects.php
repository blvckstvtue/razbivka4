<?php
require_once 'config.php';

// Получаване на филтъра по категория
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
$filtered_projects = getProjectsByCategory($selected_category);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекти - <?= $site_config['site_name'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-code"></i>
                <span><?= $site_config['site_name'] ?></span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Начало</a></li>
                <li><a href="projects.php" class="active">Проекти</a></li>
                <li><a href="index.php#services">Услуги</a></li>
                <li><a href="index.php#about">За нас</a></li>
                <li><a href="index.php#contact">Контакт</a></li>
            </ul>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Projects Hero -->
    <section class="projects-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="glitch" data-text="ПРОЕКТИ">ПРОЕКТИ</span>
                </h1>
                <p class="hero-description">
                    Разгледайте нашите реализирани проекти и вижте как превръщаме идеи в реалност
                </p>
            </div>
        </div>
        <div class="hero-background">
            <div class="code-rain"></div>
        </div>
    </section>

    <!-- Projects Filter -->
    <section class="projects-filter">
        <div class="container">
            <div class="filter-tabs">
                <a href="projects.php" class="filter-tab <?= $selected_category === null ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    Всички проекти
                    <span class="count"><?= count($projects) ?></span>
                </a>
                
                <?php foreach ($project_categories as $category_key => $category): ?>
                    <?php 
                    $category_projects = getProjectsByCategory($category_key);
                    $count = count($category_projects);
                    ?>
                    <a href="projects.php?category=<?= $category_key ?>" 
                       class="filter-tab <?= $selected_category === $category_key ? 'active' : '' ?>"
                       style="--category-color: <?= $category['color'] ?>">
                        <i class="<?= $category['icon'] ?>"></i>
                        <?= $category['name'] ?>
                        <span class="count"><?= $count ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="projects-section">
        <div class="container">
            <?php if (empty($filtered_projects)): ?>
                <div class="no-projects">
                    <i class="fas fa-folder-open"></i>
                    <h3>Няма проекти в тази категория</h3>
                    <p>Моля, изберете друга категория или се върнете към всички проекти</p>
                    <a href="projects.php" class="btn btn-primary">
                        <i class="fas fa-th-large"></i>
                        Всички проекти
                    </a>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($filtered_projects as $index => $project): ?>
                        <div class="project-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                            <div class="project-image">
                                <img src="<?= $project['image'] ?>" alt="<?= $project['title'] ?>" loading="lazy">
                                <div class="project-overlay">
                                    <div class="project-status <?= $project['status'] ?>">
                                        <?= $project['status'] === 'completed' ? 'Завършен' : 'В процес' ?>
                                    </div>
                                    <?php if ($project['featured']): ?>
                                        <div class="featured-badge">
                                            <i class="fas fa-star"></i>
                                            Избран
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="project-hover-overlay">
                                    <div class="project-actions">
                                        <?php if ($project['demo_url']): ?>
                                            <a href="<?= $project['demo_url'] ?>" class="action-btn" target="_blank" title="Live Demo">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($project['github_url']): ?>
                                            <a href="<?= $project['github_url'] ?>" class="action-btn" target="_blank" title="GitHub">
                                                <i class="fab fa-github"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button class="action-btn" onclick="openProjectModal(<?= $project['id'] ?>)" title="Повече информация">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="project-content">
                                <div class="project-header">
                                    <div class="project-category" style="color: <?= $project_categories[$project['category']]['color'] ?>">
                                        <i class="<?= $project_categories[$project['category']]['icon'] ?>"></i>
                                        <?= $project_categories[$project['category']]['name'] ?>
                                    </div>
                                    <?php if ($project['date_completed']): ?>
                                        <div class="project-date">
                                            <?= date('M Y', strtotime($project['date_completed'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="project-title"><?= $project['title'] ?></h3>
                                <p class="project-description"><?= $project['description'] ?></p>
                                
                                <div class="project-tech">
                                    <?php foreach (array_slice($project['technologies'], 0, 3) as $tech): ?>
                                        <span class="tech-tag"><?= $tech ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($project['technologies']) > 3): ?>
                                        <span class="tech-more">+<?= count($project['technologies']) - 3 ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="project-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span><?= $project['client'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
            <div class="modal-body">
                <div class="modal-image">
                    <img id="modalImage" src="" alt="">
                </div>
                <div class="modal-info">
                    <div id="modalCategory" class="project-category"></div>
                    <p id="modalDescription" class="modal-description"></p>
                    <p id="modalDetails" class="modal-details"></p>
                    
                    <div class="modal-tech">
                        <h4>Използвани технологии:</h4>
                        <div id="modalTechnologies" class="tech-grid"></div>
                    </div>
                    
                    <div class="modal-meta">
                        <div class="meta-grid">
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <div>
                                    <strong>Клиент:</strong>
                                    <span id="modalClient"></span>
                                </div>
                            </div>
                            <div id="modalDateContainer" class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <strong>Завършен:</strong>
                                    <span id="modalDate"></span>
                                </div>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-flag"></i>
                                <div>
                                    <strong>Статус:</strong>
                                    <span id="modalStatus"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="modalActions" class="modal-actions"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Готови за следващия проект?</h2>
                <p>Нека обсъдим вашата идея и я превърнем в реалност</p>
                <div class="cta-buttons">
                    <a href="index.php#contact" class="btn btn-primary">
                        <i class="fas fa-comments"></i>
                        Свържете се с нас
                    </a>
                    <a href="index.php#services" class="btn btn-outline">
                        <i class="fas fa-list"></i>
                        Нашите услуги
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="logo">
                        <i class="fas fa-code"></i>
                        <span><?= $site_config['site_name'] ?></span>
                    </div>
                    <p><?= $site_config['company_description'] ?></p>
                </div>
                
                <div class="footer-links">
                    <div class="link-group">
                        <h4>Услуги</h4>
                        <ul>
                            <li><a href="index.php#services">Уеб разработка</a></li>
                            <li><a href="index.php#services">Дизайн</a></li>
                            <li><a href="index.php#services">Игрови сървъри</a></li>
                            <li><a href="index.php#services">Мобилни приложения</a></li>
                        </ul>
                    </div>
                    
                    <div class="link-group">
                        <h4>Компания</h4>
                        <ul>
                            <li><a href="index.php#about">За нас</a></li>
                            <li><a href="projects.php">Проекти</a></li>
                            <li><a href="index.php#contact">Контакт</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $site_config['company_name'] ?>. Всички права запазени.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
        // Проекти данни за модала
        const projectsData = <?= json_encode($projects) ?>;
        const categoriesData = <?= json_encode($project_categories) ?>;
        
        function openProjectModal(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            const modal = document.getElementById('projectModal');
            const category = categoriesData[project.category];
            
            document.getElementById('modalTitle').textContent = project.title;
            document.getElementById('modalImage').src = project.image;
            document.getElementById('modalImage').alt = project.title;
            document.getElementById('modalDescription').textContent = project.description;
            document.getElementById('modalDetails').textContent = project.details;
            document.getElementById('modalClient').textContent = project.client;
            document.getElementById('modalStatus').textContent = project.status === 'completed' ? 'Завършен' : 'В процес';
            
            // Категория
            const categoryEl = document.getElementById('modalCategory');
            categoryEl.innerHTML = `<i class="${category.icon}"></i> ${category.name}`;
            categoryEl.style.color = category.color;
            
            // Дата
            const dateContainer = document.getElementById('modalDateContainer');
            if (project.date_completed) {
                const date = new Date(project.date_completed);
                document.getElementById('modalDate').textContent = date.toLocaleDateString('bg-BG');
                dateContainer.style.display = 'flex';
            } else {
                dateContainer.style.display = 'none';
            }
            
            // Технологии
            const techGrid = document.getElementById('modalTechnologies');
            techGrid.innerHTML = project.technologies.map(tech => 
                `<span class="tech-tag">${tech}</span>`
            ).join('');
            
            // Действия
            const actionsEl = document.getElementById('modalActions');
            let actions = '';
            
            if (project.demo_url) {
                actions += `<a href="${project.demo_url}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Live Demo
                </a>`;
            }
            
            if (project.github_url) {
                actions += `<a href="${project.github_url}" class="btn btn-secondary" target="_blank">
                    <i class="fab fa-github"></i> GitHub
                </a>`;
            }
            
            actionsEl.innerHTML = actions;
            
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeProjectModal() {
            const modal = document.getElementById('projectModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Затваряне при клик извън модала
        document.getElementById('projectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProjectModal();
            }
        });
        
        // Затваряне с ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProjectModal();
            }
        });
    </script>
</body>
</html>