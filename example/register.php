<?php
// register.php - User Registration
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

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $message = 'Паролите не съвпадат!';
        $message_type = 'error';
    } else {
        $result = registerUser($username, $email, $password);
        $message = $result['message'];
        $message_type = $result['success'] ? 'success' : 'error';
        
        if ($result['success']) {
            // Redirect to login page after successful registration
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - <?= $site_config['site_name'] ?></title>
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

        .register-container {
            background: var(--surface);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .register-subtitle {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
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

        .password-requirements {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Обратно към началото
    </a>

    <div class="register-container">
        <div class="register-header">
            <h1 class="register-title">
                <i class="fas fa-user-plus"></i>
                Регистрация
            </h1>
            <p class="register-subtitle">Създайте акаунт за по-лесно пазаруване</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $message_type ?>">
                <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <div class="form-group">
                <label class="form-label" for="username">
                    <i class="fas fa-user"></i> Потребителско име
                </label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="form-input" 
                    placeholder="Въведете потребителско име"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    required
                    minlength="3"
                    maxlength="50"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope"></i> Имейл адрес
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-input" 
                    placeholder="Въведете имейл адрес"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
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
                    minlength="6"
                >
                <div class="password-requirements">
                    Паролата трябва да бъде поне 6 символа
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirm_password">
                    <i class="fas fa-lock"></i> Потвърдете паролата
                </label>
                <input 
                    type="password" 
                    name="confirm_password" 
                    id="confirm_password" 
                    class="form-input" 
                    placeholder="Потвърдете паролата"
                    required
                    minlength="6"
                >
            </div>

            <button type="submit" name="register" class="btn">
                <i class="fas fa-user-plus"></i>
                Регистрация
            </button>
        </form>

        <div class="login-link">
            Вече имате акаунт? <a href="login.php">Влезте тук</a>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Паролите не съвпадат');
            } else {
                this.setCustomValidity('');
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Паролите не съвпадат!');
                return false;
            }
        });
    </script>
</body>
</html>