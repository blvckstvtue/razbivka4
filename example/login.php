<?php
// login.php - User Login
session_start();
require_once 'config.php';
require_once 'database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';
$message_type = '';

// Check for registration success message
if (isset($_GET['registered'])) {
    $message = 'Регистрацията е успешна! Моля влезте с вашите данни.';
    $message_type = 'success';
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $result = loginUser($username, $password);
    $message = $result['message'];
    $message_type = $result['success'] ? 'success' : 'error';
    
    if ($result['success']) {
        // Redirect to shop or previous page
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
        header('Location: ' . $redirect);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - <?= $site_config['site_name'] ?></title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--surface);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
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
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
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

        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Обратно към началото
    </a>

    <div class="login-container">
        <div class="login-header">
            <h1 class="login-title">
                <i class="fas fa-sign-in-alt"></i>
                Вход
            </h1>
            <p class="login-subtitle">Влезте в своя акаунт</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $message_type ?>">
                <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <div class="form-group">
                <label class="form-label" for="username">
                    <i class="fas fa-user"></i> Потребителско име или имейл
                </label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="form-input" 
                    placeholder="Въведете потребителско име или имейл"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-lock"></i> Парола
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-input" 
                    placeholder="Въведете парола"
                    required
                >
            </div>

            <button type="submit" name="login" class="btn">
                <i class="fas fa-sign-in-alt"></i>
                Вход
            </button>
        </form>

        <div class="register-link">
            Нямате акаунт? <a href="register.php">Регистрирайте се тук</a>
        </div>
    </div>

    <script>
        // Focus on username field
        document.getElementById('username').focus();
    </script>
</body>
</html>