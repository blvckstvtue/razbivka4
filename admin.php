<?php
session_start();
require_once 'config.php';

// Simple authentication (за демонстрация - в реален проект използвай по-сериозна защита)
$admin_password = 'liondevs2024'; // Промени това!

// Check authentication
if (!isset($_SESSION['admin_authenticated'])) {
    if (isset($_POST['admin_password'])) {
        if ($_POST['admin_password'] === $admin_password) {
            $_SESSION['admin_authenticated'] = true;
        } else {
            $error = 'Грешна парола!';
        }
    }
    
    if (!isset($_SESSION['admin_authenticated'])) {
        ?>
        <!DOCTYPE html>
        <html lang="bg">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login - <?= $company_config['name'] ?></title>
            <style>
                body {
                    font-family: 'Space Grotesk', sans-serif;
                    background: #0a0a0a;
                    color: #ffffff;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .login-form {
                    background: rgba(15, 15, 15, 0.9);
                    padding: 40px;
                    border-radius: 20px;
                    border: 2px solid rgba(0, 255, 255, 0.3);
                    box-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
                }
                .login-form h2 {
                    color: #00ffff;
                    margin-bottom: 30px;
                    text-align: center;
                }
                .login-form input {
                    width: 100%;
                    padding: 15px;
                    background: rgba(26, 26, 26, 0.8);
                    border: 2px solid rgba(0, 255, 255, 0.3);
                    border-radius: 10px;
                    color: #ffffff;
                    margin-bottom: 20px;
                    font-size: 16px;
                }
                .login-form button {
                    width: 100%;
                    padding: 15px;
                    background: linear-gradient(45deg, #00ffff, #0080ff);
                    border: none;
                    border-radius: 10px;
                    color: #0a0a0a;
                    font-weight: 700;
                    cursor: pointer;
                    transition: transform 0.3s ease;
                }
                .login-form button:hover {
                    transform: translateY(-2px);
                }
                .error {
                    color: #ff6600;
                    text-align: center;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="login-form">
                <h2>Admin Panel</h2>
                <?php if (isset($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="password" name="admin_password" placeholder="Админ парола" required>
                    <button type="submit">Влез</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle file upload
function handleImageUpload($file) {
    global $portfolio_config;
    
    if (!validateImageUpload($file)) {
        return false;
    }
    
    $uploadDir = $portfolio_config['image_path'];
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $filename;
    }
    
    return false;
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_project':
                $projectId = sanitizeInput($_POST['project_id']);
                $title = sanitizeInput($_POST['title']);
                $category = sanitizeInput($_POST['category']);
                $description = sanitizeInput($_POST['description']);
                $longDescription = sanitizeInput($_POST['long_description']);
                $technologies = array_map('trim', explode(',', $_POST['technologies']));
                $year = sanitizeInput($_POST['year']);
                $client = sanitizeInput($_POST['client']);
                $status = sanitizeInput($_POST['status']);
                $featured = isset($_POST['featured']);
                
                $imageName = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $imageName = handleImageUpload($_FILES['image']);
                    if (!$imageName) {
                        $message = 'Грешка при качване на снимката!';
                        $messageType = 'error';
                        break;
                    }
                } else {
                    $imageName = 'placeholder.jpg';
                }
                
                // Add to config (в реален проект би трябвало да се запише в база данни)
                $newProject = [
                    'id' => $projectId,
                    'title' => $title,
                    'category' => $category,
                    'description' => $description,
                    'long_description' => $longDescription,
                    'technologies' => $technologies,
                    'image' => $imageName,
                    'featured' => $featured,
                    'completed' => true,
                    'year' => $year,
                    'client' => $client,
                    'demo_url' => null,
                    'github_url' => null,
                    'status' => $status
                ];
                
                $message = 'Проектът е добавен успешно! (Промените са временни - в реален проект би трябвало да се запишат в база данни)';
                $messageType = 'success';
                break;
                
            case 'set_featured':
                $featuredId = sanitizeInput($_POST['featured_project_id']);
                $message = 'Featured проектът е обновен! (В реален проект промената би била запазена в база данни)';
                $messageType = 'success';
                break;
        }
    }
}

$allProjects = getAllProjects();
$allCategories = getProjectCategories();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= $company_config['name'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-cyan: #00ffff;
            --neon-purple: #8a2be2;
            --neon-orange: #ff6600;
            --electric-blue: #0080ff;
            --acid-green: #39ff14;
            
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-card: rgba(15, 15, 15, 0.9);
            
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --text-accent: #888888;
            
            --border-glow: rgba(0, 255, 255, 0.3);
            --shadow-brutal: 0 0 30px rgba(0, 255, 255, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .admin-header {
            background: var(--bg-secondary);
            padding: 20px 0;
            border-bottom: 2px solid var(--neon-cyan);
            box-shadow: var(--shadow-brutal);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--neon-cyan);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .admin-nav {
            display: flex;
            gap: 20px;
        }

        .admin-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid var(--border-glow);
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .admin-nav a:hover {
            color: var(--bg-primary);
            background: var(--neon-cyan);
            border-color: var(--neon-cyan);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .admin-section {
            background: var(--bg-card);
            border: 2px solid var(--border-glow);
            border-radius: 20px;
            padding: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--neon-purple);
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(26, 26, 26, 0.8);
            border: 2px solid var(--border-glow);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--neon-cyan), var(--electric-blue));
            color: var(--bg-primary);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 255, 0.4);
        }

        .btn-success {
            background: var(--acid-green);
            color: var(--bg-primary);
        }

        .btn-danger {
            background: var(--neon-orange);
            color: var(--bg-primary);
        }

        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .message.success {
            background: rgba(57, 255, 20, 0.2);
            border: 2px solid var(--acid-green);
            color: var(--acid-green);
        }

        .message.error {
            background: rgba(255, 102, 0, 0.2);
            border: 2px solid var(--neon-orange);
            color: var(--neon-orange);
        }

        .projects-list {
            grid-column: 1 / -1;
        }

        .project-item {
            background: rgba(26, 26, 26, 0.5);
            border: 1px solid var(--border-glow);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .project-info h4 {
            color: var(--neon-cyan);
            margin-bottom: 5px;
        }

        .project-info p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .project-actions {
            display: flex;
            gap: 10px;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .file-upload input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .file-upload-label {
            display: inline-block;
            padding: 10px 20px;
            background: var(--bg-secondary);
            border: 2px solid var(--border-glow);
            border-radius: 10px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        .featured-badge {
            background: var(--acid-green);
            color: var(--bg-primary);
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
            
            .header-container {
                flex-direction: column;
                gap: 20px;
            }
            
            .admin-nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .project-item {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="header-container">
            <h1 class="admin-title">Admin Panel</h1>
            <nav class="admin-nav">
                <a href="index.php" target="_blank"><i class="fas fa-home"></i> Към сайта</a>
                <a href="projects.php" target="_blank"><i class="fas fa-project-diagram"></i> Проекти</a>
                <a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Изход</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="admin-grid">
            <!-- Add New Project -->
            <div class="admin-section">
                <h2 class="section-title"><i class="fas fa-plus"></i> Добави Проект</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_project">
                    
                    <div class="form-group">
                        <label for="project_id">Project ID (уникален)</label>
                        <input type="text" id="project_id" name="project_id" required 
                               placeholder="project-unique-id">
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Заглавие</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Категория</label>
                        <select id="category" name="category" required>
                            <option value="">Избери категория</option>
                            <?php foreach ($allCategories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                            <?php endforeach; ?>
                            <option value="new">Нова категория...</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Кратко описание</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="long_description">Подробно описание</label>
                        <textarea id="long_description" name="long_description" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="technologies">Технологии (разделени със запетая)</label>
                        <input type="text" id="technologies" name="technologies" required 
                               placeholder="PHP, JavaScript, MySQL">
                    </div>
                    
                    <div class="form-group">
                        <label for="year">Година</label>
                        <input type="number" id="year" name="year" value="<?= date('Y') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="client">Клиент</label>
                        <input type="text" id="client" name="client" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Статус</label>
                        <select id="status" name="status" required>
                            <option value="live">Live</option>
                            <option value="development">Development</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Снимка на проекта</label>
                        <div class="file-upload">
                            <input type="file" id="image" name="image" accept="image/*">
                            <label for="image" class="file-upload-label">
                                <i class="fas fa-upload"></i> Избери файл
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="featured" name="featured">
                            <label for="featured">Featured проект</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добави Проект
                    </button>
                </form>
            </div>

            <!-- Set Featured Project -->
            <div class="admin-section">
                <h2 class="section-title"><i class="fas fa-star"></i> Featured Проект</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="set_featured">
                    
                    <div class="form-group">
                        <label for="featured_project_id">Избери featured проект</label>
                        <select id="featured_project_id" name="featured_project_id" required>
                            <?php foreach ($allProjects as $project): ?>
                                <option value="<?= htmlspecialchars($project['id']) ?>" 
                                        <?= ($project['id'] === $portfolio_config['featured_project_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($project['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-star"></i> Задай Featured
                    </button>
                </form>

                <div style="margin-top: 30px;">
                    <h3 style="color: var(--neon-orange); margin-bottom: 15px;">Статистики</h3>
                    <p><strong>Общо проекти:</strong> <?= count($allProjects) ?></p>
                    <p><strong>Категории:</strong> <?= count($allCategories) ?></p>
                    <p><strong>Завършени:</strong> <?= count(array_filter($allProjects, fn($p) => $p['completed'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Projects List -->
        <div class="admin-section projects-list">
            <h2 class="section-title"><i class="fas fa-project-diagram"></i> Всички Проекти</h2>
            <?php foreach ($allProjects as $project): ?>
                <div class="project-item">
                    <div class="project-info">
                        <h4>
                            <?= htmlspecialchars($project['title']) ?>
                            <?php if ($project['id'] === $portfolio_config['featured_project_id']): ?>
                                <span class="featured-badge">FEATURED</span>
                            <?php endif; ?>
                        </h4>
                        <p><strong>Категория:</strong> <?= htmlspecialchars($project['category']) ?></p>
                        <p><strong>Статус:</strong> <?= htmlspecialchars($project['status']) ?></p>
                        <p><strong>Година:</strong> <?= htmlspecialchars($project['year']) ?></p>
                    </div>
                    <div class="project-actions">
                        <button class="btn btn-primary" onclick="alert('Edit функцията не е имплементирана в демото')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" onclick="alert('Delete функцията не е имплементирана в демото')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('image').addEventListener('change', function(e) {
            const label = this.nextElementSibling;
            if (e.target.files.length > 0) {
                label.innerHTML = '<i class="fas fa-check"></i> ' + e.target.files[0].name;
                label.style.borderColor = 'var(--acid-green)';
                label.style.color = 'var(--acid-green)';
            }
        });

        // Category select handler
        document.getElementById('category').addEventListener('change', function() {
            if (this.value === 'new') {
                const newCategory = prompt('Въведи име на новата категория:');
                if (newCategory) {
                    const option = document.createElement('option');
                    option.value = newCategory;
                    option.textContent = newCategory;
                    option.selected = true;
                    this.insertBefore(option, this.lastElementChild);
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const projectId = document.getElementById('project_id').value;
            if (!/^[a-z0-9-]+$/.test(projectId)) {
                e.preventDefault();
                alert('Project ID може да съдържа само малки букви, цифри и тире!');
                return;
            }
        });
    </script>
</body>
</html>