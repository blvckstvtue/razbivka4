<?php
// settings.php - User Settings
session_start();
require_once 'config.php';
require_once 'database.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=settings.php');
    exit;
}

$user = getCurrentUser();
$message = '';
$message_type = '';

// Handle Steam ID update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_steam_id'])) {
    $steam_id = trim($_POST['steam_id']);
    
    $result = updateUserSteamId($user['id'], $steam_id);
    $message = $result['message'];
    $message_type = $result['success'] ? 'success' : 'error';
    
    if ($result['success']) {
        // Refresh user data
        $user = getCurrentUser();
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords match
    if ($new_password !== $confirm_password) {
        $message = 'Новите пароли не съвпадат!';
        $message_type = 'error';
    } else {
        $result = updateUserPassword($user['id'], $current_password, $new_password);
        $message = $result['message'];
        $message_type = $result['success'] ? 'success' : 'error';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    logoutUser();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки - <?= $site_config['site_name'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #5855eb;
            --secondary-color: #1f2937;
            --accent-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --background: #0f172a;
            --surface: #1e293b;
            --surface-light: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --border: #475569;
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            padding-top: 70px;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            z-index: 100;
            border-bottom: 1px solid var(--border);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-dropdown {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 5px 0;
            position: absolute;
            right: 0;
            top: 100%;
            min-width: 200px;
            box-shadow: var(--shadow);
            display: none;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown a {
            display: block;
            padding: 10px 15px;
            color: var(--text-primary);
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .user-dropdown a:hover {
            background: var(--surface-light);
        }

        /* Main content */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .settings-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .settings-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .settings-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .settings-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            background: var(--background);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--background);
            border: 2px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn.btn-danger {
            background: var(--danger-color);
        }

        .btn.btn-danger:hover {
            background: #dc2626;
        }

        .message {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .message.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
        }

        .message.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }

        .steamid-help {
            background: var(--background);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
        }

        .steamid-help h4 {
            color: var(--text-primary);
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .steamid-help ul {
            list-style: none;
            margin-left: 0;
        }

        .steamid-help li {
            color: var(--text-secondary);
            margin-bottom: 5px;
            position: relative;
            padding-left: 20px;
        }

        .steamid-help li::before {
            content: "•";
            color: var(--primary-color);
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-crown"></i>
                <?= $site_config['server_name'] ?>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Начало</a></li>
                <li><a href="shop.php">VIP Магазин</a></li>
                <li><a href="<?= $site_config['discord_url'] ?>" target="_blank">Discord</a></li>
                <li><a href="<?= $site_config['steam_group'] ?>" target="_blank">Steam Group</a></li>
                <li class="user-menu">
                    <a href="#" onclick="toggleUserMenu()">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($user['username']) ?>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="settings.php"><i class="fas fa-cog"></i> Настройки</a>
                        <a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Изход</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="settings-header">
            <h1 class="settings-title">
                <i class="fas fa-cog"></i>
                Настройки на акаунта
            </h1>
            <p class="settings-subtitle">Управлявайте вашия профил и настройки</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $message_type ?>">
                <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- User Information -->
        <div class="settings-card">
            <h2 class="card-title">
                <i class="fas fa-user"></i>
                Информация за профила
            </h2>
            <div class="user-info">
                <div class="info-item">
                    <div class="info-label">Потребителско име</div>
                    <div class="info-value"><?= htmlspecialchars($user['username']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Имейл адрес</div>
                    <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Steam ID</div>
                    <div class="info-value">
                        <?= $user['steam_id'] ? htmlspecialchars($user['steam_id']) : '<span style="color: var(--text-secondary);">Не е зададен</span>' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Steam ID Management -->
        <div class="settings-card">
            <h2 class="card-title">
                <i class="fab fa-steam"></i>
                Steam ID настройки
            </h2>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                Добавете вашия Steam ID за автоматично попълване при покупки. Ако не добавите Steam ID, ще трябва да го въвеждате всеки път при покупка.
            </p>

            <form method="POST" id="steamIdForm">
                <div class="form-group">
                    <label class="form-label" for="steam_id">
                        <i class="fab fa-steam"></i> Steam ID
                    </label>
                    <input 
                        type="text" 
                        name="steam_id" 
                        id="steam_id" 
                        class="form-input" 
                        placeholder="STEAM_0:1:123456789"
                        value="<?= htmlspecialchars($user['steam_id'] ?? '') ?>"
                        pattern="^STEAM_[01]:[01]:\d+$"
                    >
                    <div class="steamid-help">
                        <h4><i class="fas fa-info-circle"></i> Как да намеря моя Steam ID?</h4>
                        <ul>
                            <li>Отворете Steam клиента</li>
                            <li>Идете в Settings → Interface</li>
                            <li>Включете "Display Steam URL address bar when available"</li>
                            <li>Рестартирайте Steam и отворете профила си</li>
                            <li>В адресната лента ще видите числа след /profiles/</li>
                            <li>Използвайте <a href="https://steamidfinder.com/" target="_blank" style="color: var(--primary-color);">SteamID Finder</a> за конвертиране</li>
                        </ul>
                    </div>
                </div>

                <button type="submit" name="update_steam_id" class="btn">
                    <i class="fas fa-save"></i>
                    <?= $user['steam_id'] ? 'Обнови Steam ID' : 'Добави Steam ID' ?>
                </button>
            </form>
        </div>

        <!-- Password Change -->
        <div class="settings-card">
            <h2 class="card-title">
                <i class="fas fa-lock"></i>
                Смяна на парола
            </h2>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                Променете паролата си за по-добра сигурност на акаунта.
            </p>

            <form method="POST" id="passwordForm">
                <div class="form-group">
                    <label class="form-label" for="current_password">
                        <i class="fas fa-key"></i> Текуща парола
                    </label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password" 
                        class="form-input" 
                        placeholder="Въведете текущата парола"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">
                        <i class="fas fa-lock"></i> Нова парола
                    </label>
                    <input 
                        type="password" 
                        name="new_password" 
                        id="new_password" 
                        class="form-input" 
                        placeholder="Въведете новата парола (минимум 6 символа)"
                        required
                        minlength="6"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirm_password">
                        <i class="fas fa-lock"></i> Потвърдете новата парола
                    </label>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password" 
                        class="form-input" 
                        placeholder="Въведете отново новата парола"
                        required
                        minlength="6"
                    >
                </div>

                <button type="submit" name="change_password" class="btn">
                    <i class="fas fa-save"></i>
                    Смени парола
                </button>
            </form>
        </div>

        <!-- Account Actions -->
        <div class="settings-card">
            <h2 class="card-title">
                <i class="fas fa-shield-alt"></i>
                Действия с акаунта
            </h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="shop.php" class="btn">
                    <i class="fas fa-shopping-cart"></i>
                    VIP Магазин
                </a>
                <a href="?logout=1" class="btn btn-danger" onclick="return confirm('Сигурни ли сте, че искате да излезете?')">
                    <i class="fas fa-sign-out-alt"></i>
                    Изход
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.user-menu a')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        });

        // Steam ID validation
        document.getElementById('steam_id').addEventListener('input', function(e) {
            const steamid = e.target.value;
            const isValid = /^STEAM_[01]:[01]:\d+$/.test(steamid);
            
            if (steamid && !isValid) {
                this.setCustomValidity('Невалиден SteamID формат! Използвай формат: STEAM_X:Y:Z');
            } else {
                this.setCustomValidity('');
            }
        });

        // Password validation
        const newPasswordField = document.getElementById('new_password');
        const confirmPasswordField = document.getElementById('confirm_password');
        
        function validatePasswords() {
            const newPassword = newPasswordField.value;
            const confirmPassword = confirmPasswordField.value;
            
            // Check if passwords match
            if (confirmPassword && newPassword !== confirmPassword) {
                confirmPasswordField.setCustomValidity('Паролите не съвпадат!');
            } else {
                confirmPasswordField.setCustomValidity('');
            }
            
            // Check password strength
            if (newPassword && newPassword.length < 6) {
                newPasswordField.setCustomValidity('Паролата трябва да бъде поне 6 символа!');
            } else {
                newPasswordField.setCustomValidity('');
            }
        }
        
        newPasswordField.addEventListener('input', validatePasswords);
        confirmPasswordField.addEventListener('input', validatePasswords);
        
        // Form submission validation
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPassword = newPasswordField.value;
            const confirmPassword = confirmPasswordField.value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Паролите не съвпадат!');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Новата парола трябва да бъде поне 6 символа!');
                return false;
            }
            
            return confirm('Сигурни ли сте, че искате да смените паролата си?');
        });
    </script>
</body>
</html>