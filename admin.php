<?php
require_once 'config.php';

// Проста защита - в реален проект трябва да има истинска аутентификация
session_start();

// Проверка за login (просто за демо)
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['admin_login'])) {
        $password = $_POST['password'] ?? '';
        // В реален проект паролата трябва да е hash-ната
        if ($password === 'liondevs2024') {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $login_error = 'Грешна парола!';
        }
    }
    
    if (!isset($_SESSION['admin_logged_in'])) {
        // Показваме login форма
        ?>
        <!DOCTYPE html>
        <html lang="bg">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login - <?= $company_config['name'] ?></title>
            <link href="css/style.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        </head>
        <body>
            <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bg-gradient);">
                <div class="card" style="max-width: 400px; width: 100%; margin: 2rem;">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h2 style="color: var(--text-primary); margin-bottom: 0.5rem;">Admin Panel</h2>
                        <p style="color: var(--text-secondary);">Влезте в администраторския панел</p>
                    </div>
                    
                    <?php if (isset($login_error)): ?>
                    <div style="background: var(--danger-color); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= $login_error ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div style="margin-bottom: 2rem;">
                            <label style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Парола</label>
                            <input type="password" name="password" required style="width: 100%; padding: 1rem; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: var(--font-size-base);">
                        </div>
                        
                        <button type="submit" name="admin_login" class="btn btn-primary w-full">
                            <i class="fas fa-sign-in-alt"></i>
                            Влез в Admin Panel
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 2rem;">
                        <a href="index.php" style="color: var(--text-secondary); font-size: var(--font-size-sm);">
                            <i class="fas fa-arrow-left"></i>
                            Назад към сайта
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Logout функционалност
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Обработка на форми
$message = '';
$projects = $portfolio_projects['projects'];

// Добавяне на нов проект
if (isset($_POST['add_project'])) {
    $new_project = [
        'id' => count($projects) + 1,
        'title' => $_POST['title'] ?? '',
        'category' => $_POST['category'] ?? '',
        'description' => $_POST['description'] ?? '',
        'technologies' => array_map('trim', explode(',', $_POST['technologies'] ?? '')),
        'image' => $_POST['image'] ?? '',
        'demo_url' => $_POST['demo_url'] ?? '#',
        'github_url' => $_POST['github_url'] ?? '#',
        'status' => $_POST['status'] ?? 'completed',
        'completion_date' => $_POST['completion_date'] ?? date('Y-m-d'),
        'client' => $_POST['client'] ?? '',
        'price_range' => $_POST['price_range'] ?? '',
        'featured' => isset($_POST['featured'])
    ];
    
    // В реален проект тук би се записало в база данни
    $message = 'Проектът беше добавен успешно! (Демо режим - не се запазва реално)';
}

// Обновяване на featured проект
if (isset($_POST['update_featured'])) {
    $featured_id = $_POST['featured_project'] ?? 1;
    // В реален проект тук би се обновило в база данни
    $message = "Featured проектът беше обновен на ID: $featured_id (Демо режим)";
}

// Изтриване на проект
if (isset($_POST['delete_project'])) {
    $project_id = $_POST['project_id'] ?? 0;
    // В реален проект тук би се изтрил от база данни
    $message = "Проектът с ID: $project_id беше изтрит (Демо режим)";
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= $company_config['name'] ?></title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="navbar" style="position: relative;">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-cogs"></i>
                Admin Panel
            </div>
            
            <div style="display: flex; align-items: center; gap: 2rem;">
                <a href="index.php" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: var(--font-size-sm);">
                    <i class="fas fa-eye"></i>
                    Виж сайта
                </a>
                <a href="admin.php?logout=1" class="btn btn-accent" style="padding: 0.5rem 1rem; font-size: var(--font-size-sm);">
                    <i class="fas fa-sign-out-alt"></i>
                    Излез
                </a>
            </div>
        </div>
    </nav>

    <div style="padding-top: 100px;">
        <!-- Success/Error Messages -->
        <?php if ($message): ?>
        <div style="background: var(--accent); color: white; padding: 1rem; text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-check-circle"></i>
            <?= $message ?>
        </div>
        <?php endif; ?>

        <div class="container" style="max-width: 1600px;">
            <!-- Admin Dashboard Header -->
            <div style="text-align: center; margin-bottom: 4rem;">
                <h1 style="font-family: var(--font-accent); font-size: var(--font-size-4xl); margin-bottom: 1rem; color: var(--text-primary);">
                    <i class="fas fa-tachometer-alt"></i>
                    Admin Dashboard
                </h1>
                <p style="color: var(--text-secondary); font-size: var(--font-size-lg);">
                    Управлявайте портфолиото на <?= $company_config['full_name'] ?>
                </p>
            </div>

            <!-- Admin Sections -->
            <div class="admin-tabs" style="margin-bottom: 3rem;">
                <button class="tab-btn active" onclick="showTab('projects')">
                    <i class="fas fa-folder-open"></i>
                    Управление на проекти
                </button>
                <button class="tab-btn" onclick="showTab('featured')">
                    <i class="fas fa-star"></i>
                    Featured проект
                </button>
                <button class="tab-btn" onclick="showTab('settings')">
                    <i class="fas fa-cog"></i>
                    Настройки
                </button>
            </div>

            <!-- Projects Management Tab -->
            <div id="projects-tab" class="tab-content active">
                <div class="grid grid-2" style="gap: 3rem; align-items: start;">
                    <!-- Add New Project Form -->
                    <div class="card">
                        <h3 style="color: var(--primary); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-plus-circle"></i>
                            Добави нов проект
                        </h3>
                        
                        <form method="POST" class="project-form">
                            <div class="form-group">
                                <label>Заглавие *</label>
                                <input type="text" name="title" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Категория *</label>
                                <select name="category" required>
                                    <option value="">Избери категория</option>
                                    <option value="Gaming">Gaming</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Software">Software</option>
                                    <option value="Design">Design</option>
                                    <option value="Mobile">Mobile</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Описание *</label>
                                <textarea name="description" rows="4" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Технологии (разделени със запетая) *</label>
                                <input type="text" name="technologies" placeholder="PHP, MySQL, JavaScript, Vue.js" required>
                            </div>
                            
                            <div class="form-group">
                                <label>URL на изображение</label>
                                <input type="url" name="image" placeholder="https://example.com/image.jpg">
                            </div>
                            
                            <div class="form-group">
                                <label>Demo URL</label>
                                <input type="url" name="demo_url" placeholder="https://demo.example.com">
                            </div>
                            
                            <div class="form-group">
                                <label>GitHub URL</label>
                                <input type="url" name="github_url" placeholder="https://github.com/user/repo">
                            </div>
                            
                            <div class="form-group">
                                <label>Статус *</label>
                                <select name="status" required>
                                    <option value="completed">Завършен</option>
                                    <option value="in_progress">В процес</option>
                                    <option value="planned">Планиран</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Дата на завършване *</label>
                                <input type="date" name="completion_date" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Клиент *</label>
                                <input type="text" name="client" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Ценови диапазон (EUR) *</label>
                                <input type="text" name="price_range" placeholder="500-1000" required>
                            </div>
                            
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" name="featured" style="transform: scale(1.2);">
                                    Featured проект
                                </label>
                            </div>
                            
                            <button type="submit" name="add_project" class="btn btn-primary w-full">
                                <i class="fas fa-plus"></i>
                                Добави проект
                            </button>
                        </form>
                    </div>

                    <!-- Existing Projects List -->
                    <div class="card">
                        <h3 style="color: var(--primary); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-list"></i>
                            Съществуващи проекти (<?= count($projects) ?>)
                        </h3>
                        
                        <div class="projects-list" style="max-height: 600px; overflow-y: auto;">
                            <?php foreach ($projects as $project): ?>
                            <div class="project-item" style="border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; background: var(--bg-tertiary);">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                    <div>
                                        <h4 style="color: var(--text-primary); margin-bottom: 0.5rem;">
                                            <?= $project['title'] ?>
                                            <?php if ($project['featured']): ?>
                                            <span style="background: var(--accent); color: white; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: var(--font-size-xs); margin-left: 0.5rem;">FEATURED</span>
                                            <?php endif; ?>
                                        </h4>
                                        <div style="color: var(--text-secondary); font-size: var(--font-size-sm);">
                                            <span style="background: rgba(255, 107, 53, 0.1); color: var(--primary); padding: 0.2rem 0.5rem; border-radius: 4px; margin-right: 0.5rem;">
                                                <?= $project['category'] ?>
                                            </span>
                                            <span><?= $project['client'] ?></span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-outline" onclick="editProject(<?= $project['id'] ?>)" title="Редактирай">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Сигурни ли сте, че искате да изтриете този проект?')">
                                            <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                                            <button type="submit" name="delete_project" class="btn-small btn-danger" title="Изтрий">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <p style="color: var(--text-secondary); font-size: var(--font-size-sm); margin-bottom: 1rem;">
                                    <?= substr($project['description'], 0, 150) ?>...
                                </p>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                                    <?php foreach (array_slice($project['technologies'], 0, 3) as $tech): ?>
                                    <span style="background: var(--bg-primary); color: var(--text-secondary); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: var(--font-size-xs);">
                                        <?= $tech ?>
                                    </span>
                                    <?php endforeach; ?>
                                    <?php if (count($project['technologies']) > 3): ?>
                                    <span style="color: var(--text-muted); font-size: var(--font-size-xs);">
                                        +<?= count($project['technologies']) - 3 ?> още
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: var(--font-size-sm); color: var(--text-muted);">
                                    <span>
                                        <i class="fas fa-calendar"></i>
                                        <?= date('d.m.Y', strtotime($project['completion_date'])) ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-euro-sign"></i>
                                        <?= $project['price_range'] ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Project Tab -->
            <div id="featured-tab" class="tab-content">
                <div class="card" style="max-width: 600px; margin: 0 auto;">
                    <h3 style="color: var(--primary); margin-bottom: 2rem; text-align: center;">
                        <i class="fas fa-star"></i>
                        Избери Featured проект
                    </h3>
                    
                    <p style="text-align: center; color: var(--text-secondary); margin-bottom: 3rem;">
                        Featured проектът се показва на началната страница като highlight
                    </p>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Избери проект за featured *</label>
                            <select name="featured_project" required style="width: 100%;">
                                <?php foreach ($projects as $project): ?>
                                <option value="<?= $project['id'] ?>" <?= $portfolio_projects['featured_project'] == $project['id'] ? 'selected' : '' ?>>
                                    <?= $project['title'] ?> - <?= $project['category'] ?>
                                    <?= $project['featured'] ? ' (Currently Featured)' : '' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="update_featured" class="btn btn-accent w-full">
                            <i class="fas fa-star"></i>
                            Обнови Featured проект
                        </button>
                    </form>
                </div>
            </div>

            <!-- Settings Tab -->
            <div id="settings-tab" class="tab-content">
                <div class="grid grid-2" style="gap: 3rem;">
                    <div class="card">
                        <h3 style="color: var(--primary); margin-bottom: 2rem;">
                            <i class="fas fa-building"></i>
                            Информация за компанията
                        </h3>
                        
                        <div class="settings-info">
                            <div class="info-item">
                                <strong>Име:</strong> <?= $company_config['name'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Пълно име:</strong> <?= $company_config['full_name'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Мото:</strong> <?= $company_config['tagline'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Email:</strong> <?= $company_config['contact_email'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Телефон:</strong> <?= $company_config['phone'] ?>
                            </div>
                        </div>
                        
                        <p style="color: var(--text-muted); font-size: var(--font-size-sm); margin-top: 2rem; text-align: center;">
                            За промяна на тези настройки, редактирайте config.php файла
                        </p>
                    </div>
                    
                    <div class="card">
                        <h3 style="color: var(--primary); margin-bottom: 2rem;">
                            <i class="fas fa-chart-bar"></i>
                            Статистики
                        </h3>
                        
                        <div class="admin-stats">
                            <div class="stat-card">
                                <div class="stat-number"><?= count($projects) ?></div>
                                <div class="stat-label">Общо проекти</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-number"><?= count(array_filter($projects, function($p) { return $p['status'] === 'completed'; })) ?></div>
                                <div class="stat-label">Завършени</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-number"><?= count(array_filter($projects, function($p) { return $p['status'] === 'in_progress'; })) ?></div>
                                <div class="stat-label">В процес</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-number"><?= count(getProjectCategories()) ?></div>
                                <div class="stat-label">Категории</div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 2rem;">
                            <h4 style="color: var(--text-primary); margin-bottom: 1rem;">Категории:</h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <?php foreach (getProjectCategories() as $category): ?>
                                <span class="tech-tag"><?= $category ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active to clicked button
            event.target.classList.add('active');
        }
        
        // Edit project function (simplified for demo)
        function editProject(projectId) {
            alert('Редактиране на проект #' + projectId + '\n\nВ реален проект тук би се отворил modal или отделна страница за редактиране.');
        }
        
        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                let hasEmpty = false;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        hasEmpty = true;
                        field.style.borderColor = 'var(--danger-color)';
                    } else {
                        field.style.borderColor = 'var(--border)';
                    }
                });
                
                if (hasEmpty) {
                    e.preventDefault();
                    alert('Моля, попълнете всички задължителни полета!');
                }
            });
        });
    </script>

    <style>
        /* Admin specific styles */
        .admin-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 3rem;
        }
        
        .tab-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all var(--transition-normal);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .tab-btn.active,
        .tab-btn:hover {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: var(--font-size-sm);
            transition: border-color var(--transition-fast);
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.1);
        }
        
        .btn-small {
            padding: 0.5rem;
            font-size: var(--font-size-sm);
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
            border: 1px solid var(--danger-color);
        }
        
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .stat-card {
            background: var(--bg-tertiary);
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid var(--border);
        }
        
        .stat-card .stat-number {
            font-family: var(--font-accent);
            font-size: var(--font-size-2xl);
            font-weight: 900;
            color: var(--primary);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .stat-label {
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .settings-info .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
        }
        
        .settings-info .info-item:last-child {
            border-bottom: none;
        }
        
        .settings-info .info-item strong {
            color: var(--text-primary);
        }
        
        /* Responsive admin styles */
        @media (max-width: 768px) {
            .admin-tabs {
                flex-direction: column;
                align-items: center;
            }
            
            .tab-btn {
                padding: 0.75rem 1.5rem;
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            .admin-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>