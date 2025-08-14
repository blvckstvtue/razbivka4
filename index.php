<?php
require_once 'config.php';

// Получаваме featured проекта
$featured_project = getFeaturedProject();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_config['site_title'] ?></title>
    <meta name="description" content="<?= $site_config['meta_description'] ?>">
    <meta name="keywords" content="<?= $site_config['meta_keywords'] ?>">
    
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
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#services">Услуги</a></li>
                <li><a href="#featured">Проект</a></li>
                <li><a href="#stats">Статистики</a></li>
                <li><a href="projects.php">Портфолио</a></li>
                <li><a href="#contact">Контакт</a></li>
            </ul>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title animate-fade-in-up">
                <?= $company_config['name'] ?>
            </h1>
            <p class="hero-subtitle animate-fade-in-up">
                <?= $company_config['tagline'] ?>
            </p>
            <p class="hero-description animate-fade-in-up">
                <?= $company_config['description'] ?>
            </p>
            
            <div class="d-flex justify-center gap-4 animate-fade-in-up">
                <a href="projects.php" class="btn btn-primary">
                    <i class="fas fa-folder-open"></i>
                    Виж Портфолио
                </a>
                <a href="#contact" class="btn btn-outline">
                    <i class="fas fa-envelope"></i>
                    Свържи се с нас
                </a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section">
        <div class="container">
            <h2 class="section-title">Нашите Услуги</h2>
            <p class="section-subtitle">
                Предлагаме широк спектър от професионални услуги за реализация на вашите идеи
            </p>
            
            <div class="grid grid-3">
                <?php foreach ($services as $service): ?>
                <div class="service-card animate-fade-in-up">
                    <div class="service-icon">
                        <i class="<?= $service['icon'] ?>"></i>
                    </div>
                    <h3 class="service-title"><?= $service['title'] ?></h3>
                    <p class="service-description"><?= $service['description'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Project Section -->
    <?php if ($featured_project): ?>
    <section id="featured" class="section" style="background: var(--bg-secondary);">
        <div class="container">
            <h2 class="section-title">Последен Проект</h2>
            <p class="section-subtitle">
                Разгледайте нашия най-нов завършен проект
            </p>
            
            <div class="featured-project">
                <div class="grid grid-2" style="align-items: center; gap: 4rem;">
                    <div class="project-info animate-fade-in-left">
                        <div class="project-category"><?= $featured_project['category'] ?></div>
                        <h3 class="project-title" style="font-size: var(--font-size-3xl);">
                            <?= $featured_project['title'] ?>
                        </h3>
                        <p class="project-description" style="font-size: var(--font-size-base); margin-bottom: 2rem;">
                            <?= $featured_project['description'] ?>
                        </p>
                        
                        <div class="project-tech mb-3">
                            <?php foreach ($featured_project['technologies'] as $tech): ?>
                            <span class="tech-tag"><?= $tech ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="project-meta mb-3">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                                <div>
                                    <strong style="color: var(--primary);">Клиент:</strong><br>
                                    <span style="color: var(--text-secondary);"><?= $featured_project['client'] ?></span>
                                </div>
                                <div>
                                    <strong style="color: var(--primary);">Статус:</strong><br>
                                    <span style="color: var(--accent); text-transform: capitalize;"><?= $featured_project['status'] ?></span>
                                </div>
                                <div>
                                    <strong style="color: var(--primary);">Дата:</strong><br>
                                    <span style="color: var(--text-secondary);"><?= date('d.m.Y', strtotime($featured_project['completion_date'])) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="project-links">
                            <?php if ($featured_project['demo_url'] && $featured_project['demo_url'] !== '#'): ?>
                            <a href="<?= $featured_project['demo_url'] ?>" class="btn btn-primary" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                Live Demo
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($featured_project['github_url'] && $featured_project['github_url'] !== '#'): ?>
                            <a href="<?= $featured_project['github_url'] ?>" class="btn btn-outline" target="_blank">
                                <i class="fab fa-github"></i>
                                GitHub
                            </a>
                            <?php endif; ?>
                            
                            <a href="projects.php" class="btn btn-accent">
                                <i class="fas fa-folder-open"></i>
                                Виж всички
                            </a>
                        </div>
                    </div>
                    
                    <div class="project-visual animate-fade-in-right">
                        <div class="project-showcase">
                            <div class="project-image-container" style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-heavy);">
                                <img src="<?= $featured_project['image'] ?>" 
                                     alt="<?= $featured_project['title'] ?>" 
                                     style="width: 100%; height: 400px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/600x400/1A1A1A/FF6B35?text=<?= urlencode($featured_project['title']) ?>'">
                                
                                <!-- Status Badge -->
                                <div style="position: absolute; top: 20px; right: 20px; background: var(--accent); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: var(--font-size-sm); font-weight: 600; text-transform: uppercase;">
                                    <?= $featured_project['status'] === 'completed' ? 'Завършен' : 'В процес' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats Section -->
    <section id="stats" class="section">
        <div class="container">
            <h2 class="section-title">Статистики</h2>
            <p class="section-subtitle">
                Нашите постижения в цифри
            </p>
            
            <div class="stats-grid">
                <?php foreach ($company_stats as $stat): ?>
                <div class="stat-item animate-fade-in-up">
                    <div class="stat-icon" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;">
                        <i class="<?= $stat['icon'] ?>"></i>
                    </div>
                    <span class="stat-number"><?= $stat['number'] ?></span>
                    <span class="stat-label"><?= $stat['label'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section" style="background: var(--bg-secondary);">
        <div class="container">
            <h2 class="section-title">Свържете се с нас</h2>
            <p class="section-subtitle">
                Готови сме да реализираме вашия проект. Свържете се с нас още днес!
            </p>
            
            <div class="grid grid-2" style="gap: 4rem; align-items: center;">
                <div class="contact-info animate-fade-in-left">
                    <div class="contact-item" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope" style="color: white; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--text-primary); margin: 0;">Email</h4>
                                <a href="mailto:<?= $company_config['contact_email'] ?>" style="color: var(--text-secondary);">
                                    <?= $company_config['contact_email'] ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 50px; height: 50px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone" style="color: white; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--text-primary); margin: 0;">Телефон</h4>
                                <span style="color: var(--text-secondary);"><?= $company_config['phone'] ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links" style="margin-top: 2rem;">
                        <?php foreach ($company_config['social_media'] as $platform => $url): ?>
                        <a href="<?= $url ?>" class="social-link" target="_blank" title="<?= ucfirst($platform) ?>">
                            <i class="fab fa-<?= $platform ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="contact-form animate-fade-in-right">
                    <form class="card" style="background: var(--bg-card); padding: 3rem;">
                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Име</label>
                            <input type="text" name="name" required style="width: 100%; padding: 1rem; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: var(--font-size-base);">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Email</label>
                            <input type="email" name="email" required style="width: 100%; padding: 1rem; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: var(--font-size-base);">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Съобщение</label>
                            <textarea name="message" rows="5" required style="width: 100%; padding: 1rem; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: var(--font-size-base); resize: vertical;"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-full">
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
                    <a href="#services">Custom Software Development</a>
                    <a href="#services">Gaming Solutions</a>
                    <a href="#services">Design & Branding</a>
                    <a href="#services">Web Development</a>
                </div>
                
                <div class="footer-section">
                    <h3>Навигация</h3>
                    <a href="#home">Начало</a>
                    <a href="projects.php">Портфолио</a>
                    <a href="#services">Услуги</a>
                    <a href="#contact">Контакт</a>
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
    
    <style>
        /* Additional Styles for Enhanced Brutalism */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }
        
        .scroll-arrow {
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
            40% { transform: translateX(-50%) translateY(-10px); }
            60% { transform: translateX(-50%) translateY(-5px); }
        }
        
        .featured-project {
            position: relative;
        }
        
        .featured-project::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 100px;
            height: 100px;
            background: var(--primary);
            opacity: 0.1;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .featured-project::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 60px;
            height: 60px;
            background: var(--accent);
            opacity: 0.1;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        /* Enhanced Form Styles */
        input:focus,
        textarea:focus {
            outline: none !important;
            border-color: var(--primary) !important;
            box-shadow: var(--glow) !important;
        }
        
        input::placeholder,
        textarea::placeholder {
            color: var(--text-muted);
        }
        
        /* Enhanced Button Hover Effects */
        .btn:hover {
            transform: translateY(-2px) scale(1.05);
        }
        
        .gap-4 {
            gap: 2rem;
        }
    </style>
</body>
</html>