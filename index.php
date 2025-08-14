<?php
require_once 'config.php';
$featured_project = getFeaturedProject();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_config['site_name'] ?> - <?= $site_config['company_name'] ?></title>
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
                <li><a href="projects.php">Проекти</a></li>
                <li><a href="#services">Услуги</a></li>
                <li><a href="#about">За нас</a></li>
                <li><a href="#contact">Контакт</a></li>
            </ul>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background">
            <div class="code-rain"></div>
            <div class="geometric-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
            </div>
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="glitch" data-text="LIONDEVS">LIONDEVS</span>
                    <span class="subtitle">РАЗВИВАМЕ БЪДЕЩЕТО</span>
                </h1>
                <p class="hero-description">
                    Професионална компания за разработка на софтуер, дизайн, сървъри за игри и персонализирани решения. 
                    Превръщаме идеи в реалност с най-съвременните технологии.
                </p>
                <div class="hero-buttons">
                    <a href="projects.php" class="btn btn-primary">
                        <i class="fas fa-rocket"></i>
                        Вижте проектите ни
                    </a>
                    <a href="#contact" class="btn btn-secondary">
                        <i class="fas fa-comments"></i>
                        Свържете се с нас
                    </a>
                </div>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number" data-count="50">0</div>
                    <div class="stat-label">Проекта</div>
                </div>
                <div class="stat">
                    <div class="stat-number" data-count="100">0</div>
                    <div class="stat-label">Клиента</div>
                </div>
                <div class="stat">
                    <div class="stat-number" data-count="3">0</div>
                    <div class="stat-label">Години опит</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Project -->
    <?php if ($featured_project): ?>
    <section class="featured-project">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Избран проект</h2>
                <p class="section-subtitle">Разгледайте нашия най-нов и впечатляващ проект</p>
            </div>
            
            <div class="featured-content">
                <div class="project-visual">
                    <div class="project-image">
                        <img src="<?= $featured_project['image'] ?>" alt="<?= $featured_project['title'] ?>" loading="lazy">
                        <div class="project-overlay">
                            <div class="project-status <?= $featured_project['status'] ?>">
                                <?= $featured_project['status'] === 'completed' ? 'Завършен' : 'В процес' ?>
                            </div>
                        </div>
                    </div>
                    <div class="project-tech">
                        <?php foreach ($featured_project['technologies'] as $tech): ?>
                            <span class="tech-tag"><?= $tech ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="project-info">
                    <div class="project-category">
                        <i class="<?= $project_categories[$featured_project['category']]['icon'] ?>"></i>
                        <?= $project_categories[$featured_project['category']]['name'] ?>
                    </div>
                    <h3 class="project-title"><?= $featured_project['title'] ?></h3>
                    <p class="project-description"><?= $featured_project['description'] ?></p>
                    <p class="project-details"><?= $featured_project['details'] ?></p>
                    
                    <div class="project-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Клиент: <?= $featured_project['client'] ?></span>
                        </div>
                        <?php if ($featured_project['date_completed']): ?>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Завършен: <?= date('d.m.Y', strtotime($featured_project['date_completed'])) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="project-actions">
                        <?php if ($featured_project['demo_url']): ?>
                        <a href="<?= $featured_project['demo_url'] ?>" class="btn btn-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            Live Demo
                        </a>
                        <?php endif; ?>
                        <?php if ($featured_project['github_url']): ?>
                        <a href="<?= $featured_project['github_url'] ?>" class="btn btn-secondary" target="_blank">
                            <i class="fab fa-github"></i>
                            GitHub
                        </a>
                        <?php endif; ?>
                        <a href="projects.php" class="btn btn-outline">
                            <i class="fas fa-th-large"></i>
                            Всички проекти
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Нашите услуги</h2>
                <p class="section-subtitle">Предлагаме широк спектър от професионални услуги</p>
            </div>
            
            <div class="services-grid">
                <?php foreach ($services as $index => $service): ?>
                <div class="service-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <div class="service-icon">
                        <i class="<?= $service['icon'] ?>"></i>
                    </div>
                    <h3 class="service-title"><?= $service['title'] ?></h3>
                    <p class="service-description"><?= $service['description'] ?></p>
                    <ul class="service-features">
                        <?php foreach ($service['features'] as $feature): ?>
                            <li><i class="fas fa-check"></i> <?= $feature ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title">За нас</h2>
                    <p class="about-description">
                        <?= $site_config['company_description'] ?>. Нашият екип от опитни разработчици и дизайнери 
                        работи с най-новите технологии, за да създаде решения, които отговарят на най-високите стандарти.
                    </p>
                    <div class="about-features">
                        <div class="feature">
                            <i class="fas fa-rocket"></i>
                            <div>
                                <h4>Иновации</h4>
                                <p>Използваме най-новите технологии</p>
                            </div>
                        </div>
                        <div class="feature">
                            <i class="fas fa-users"></i>
                            <div>
                                <h4>Екипна работа</h4>
                                <p>Професионален и отдаден екип</p>
                            </div>
                        </div>
                        <div class="feature">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Навременност</h4>
                                <p>Доставяме проектите в срок</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="team-section">
                    <h3>Нашият екип</h3>
                    <div class="team-grid">
                        <?php foreach ($team_members as $member): ?>
                        <div class="team-member">
                            <div class="member-image">
                                <img src="<?= $member['image'] ?>" alt="<?= $member['name'] ?>" loading="lazy">
                            </div>
                            <div class="member-info">
                                <h4><?= $member['name'] ?></h4>
                                <p class="member-position"><?= $member['position'] ?></p>
                                <p class="member-description"><?= $member['description'] ?></p>
                                <div class="member-skills">
                                    <?php foreach ($member['skills'] as $skill): ?>
                                        <span class="skill-tag"><?= $skill ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Свържете се с нас</h2>
                <p class="section-subtitle">Готови сме да превърнем вашата идея в реалност</p>
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p><?= $site_config['contact_email'] ?></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Телефон</h4>
                            <p><?= $site_config['phone'] ?></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Адрес</h4>
                            <p><?= $site_config['address'] ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Вашето име" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Вашия email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Тема">
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Вашето съобщение" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Изпрати съобщение
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
                            <li><a href="#services">Уеб разработка</a></li>
                            <li><a href="#services">Дизайн</a></li>
                            <li><a href="#services">Игрови сървъри</a></li>
                            <li><a href="#services">Мобилни приложения</a></li>
                        </ul>
                    </div>
                    
                    <div class="link-group">
                        <h4>Компания</h4>
                        <ul>
                            <li><a href="#about">За нас</a></li>
                            <li><a href="projects.php">Проекти</a></li>
                            <li><a href="#contact">Контакт</a></li>
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
</body>
</html>