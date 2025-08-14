<?php
// database.php - Database connection and user management
require_once 'config.php';

// Database connection
function getDatabase() {
    global $db_config;
    
    try {
        $pdo = new PDO(
            "mysql:host={$db_config['host']};dbname={$db_config['database']};charset={$db_config['charset']}",
            $db_config['username'],
            $db_config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Initialize database tables
function initializeDatabase() {
    $pdo = getDatabase();
    
    // Create users table
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        steam_id VARCHAR(50) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_active BOOLEAN DEFAULT TRUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
    
    // Create sessions table for better session management
    $sql = "
    CREATE TABLE IF NOT EXISTS user_sessions (
        id VARCHAR(128) PRIMARY KEY,
        user_id INT NOT NULL,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
    
    // Create chat messages table
    $sql = "
    CREATE TABLE IF NOT EXISTS chat_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        username VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_deleted BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_created_at (created_at),
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
}

// User registration
function registerUser($username, $email, $password) {
    $pdo = getDatabase();
    
    // Validate input
    if (strlen($username) < 3 || strlen($username) > 50) {
        return ['success' => false, 'message' => 'Потребителското име трябва да бъде между 3 и 50 символа'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Невалиден имейл адрес'];
    }
    
    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Паролата трябва да бъде поне 6 символа'];
    }
    
    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Потребителското име или имейл вече съществуват'];
        }
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash]);
        
        return ['success' => true, 'message' => 'Успешна регистрация! Можете да се влезете.'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Грешка при регистрацията: ' . $e->getMessage()];
    }
}

// User login
function loginUser($username, $password) {
    $pdo = getDatabase();
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, password_hash, steam_id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Невалидно потребителско име или парола'];
        }
        
        // Start session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['steam_id'] = $user['steam_id'];
        $_SESSION['logged_in'] = true;
        
        return ['success' => true, 'message' => 'Успешно влизане!', 'user' => $user];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Грешка при влизане: ' . $e->getMessage()];
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'steam_id' => $_SESSION['steam_id']
    ];
}

// Update user Steam ID
function updateUserSteamId($user_id, $steam_id) {
    $pdo = getDatabase();
    
    // Validate Steam ID format
    if ($steam_id && !preg_match('/^STEAM_[01]:[01]:\d+$/', $steam_id)) {
        return ['success' => false, 'message' => 'Невалиден SteamID формат! Използвай формат: STEAM_X:Y:Z'];
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET steam_id = ? WHERE id = ?");
        $stmt->execute([$steam_id, $user_id]);
        
        // Update session
        $_SESSION['steam_id'] = $steam_id;
        
        return ['success' => true, 'message' => 'Steam ID е обновен успешно!'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Грешка при обновяване: ' . $e->getMessage()];
    }
}

// Update user password
function updateUserPassword($user_id, $current_password, $new_password) {
    $pdo = getDatabase();
    
    // Validate new password
    if (strlen($new_password) < 6) {
        return ['success' => false, 'message' => 'Новата парола трябва да бъде поне 6 символа'];
    }
    
    try {
        // First, verify current password
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($current_password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Текущата парола е невалидна'];
        }
        
        // Hash new password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$new_password_hash, $user_id]);
        
        return ['success' => true, 'message' => 'Паролата е променена успешно!'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Грешка при промяна на паролата: ' . $e->getMessage()];
    }
}

// User logout
function logoutUser() {
    session_start();
    session_destroy();
    return ['success' => true, 'message' => 'Успешно излизане!'];
}

// Get top donors
function getTopDonors($limit = 5) {
    try {
        $pdo = getDatabase();
        $stmt = $pdo->prepare("
            SELECT 
                u.username,
                u.steam_id,
                SUM(p.price) as total_donated,
                COUNT(p.id) as purchase_count,
                MAX(p.purchase_date) as last_purchase
            FROM users u
            INNER JOIN purchases p ON u.id = p.user_id
            WHERE u.is_active = 1
            GROUP BY u.id, u.username, u.steam_id
            ORDER BY total_donated DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Add chat message
function addChatMessage($user_id, $username, $message) {
    global $widget_config;
    
    if (!$widget_config['chat']['enabled']) {
        return ['success' => false, 'message' => 'Чатът е деактивиран'];
    }
    
    $message = trim($message);
    if (empty($message)) {
        return ['success' => false, 'message' => 'Съобщението не може да бъде празно'];
    }
    
    if (strlen($message) > $widget_config['chat']['max_message_length']) {
        return ['success' => false, 'message' => 'Съобщението е твърде дълго'];
    }
    
    try {
        $pdo = getDatabase();
        $stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, username, message) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $username, $message]);
        
        return ['success' => true, 'message' => 'Съобщението е изпратено'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Грешка при изпращане на съобщението'];
    }
}

// Get chat messages
function getChatMessages($limit = 50) {
    try {
        $pdo = getDatabase();
        $stmt = $pdo->prepare("
            SELECT 
                id,
                username,
                message,
                created_at
            FROM chat_messages 
            WHERE is_deleted = 0
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return array_reverse($stmt->fetchAll()); // Reverse to show oldest first
    } catch (Exception $e) {
        return [];
    }
}

// Функция за броене на регистрирани потребители
function getRegisteredUsersCount() {
    try {
        $pdo = getDatabase();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

// Initialize database on first run
if (!file_exists('db_initialized.lock')) {
    initializeDatabase();
    file_put_contents('db_initialized.lock', date('Y-m-d H:i:s'));
}
?>