<?php
// setup_database.php - Database initialization script
require_once 'config.php';

echo "<h2>Database Setup for VIP Store</h2>";

// Check if database exists and create if needed
try {
    // First connect without database to create it
    $pdo = new PDO(
        "mysql:host={$db_config['host']};charset={$db_config['charset']}",
        $db_config['username'],
        $db_config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✓ Database '{$db_config['database']}' created/verified</p>";
    
    // Now connect to the specific database
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['database']};charset={$db_config['charset']}",
        $db_config['username'],
        $db_config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
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
        is_active BOOLEAN DEFAULT TRUE,
        INDEX idx_username (username),
        INDEX idx_email (email),
        INDEX idx_steam_id (steam_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
    echo "<p>✓ Users table created/verified</p>";
    
    // Create sessions table
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
    echo "<p>✓ User sessions table created/verified</p>";
    
    // Create purchases log table for better tracking
    $sql = "
    CREATE TABLE IF NOT EXISTS purchases (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        steam_id VARCHAR(50) NOT NULL,
        item_key VARCHAR(50) NOT NULL,
        item_name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45),
        server_id VARCHAR(50) DEFAULT NULL,
        status ENUM('completed', 'failed', 'pending') DEFAULT 'completed',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_steam_id (steam_id),
        INDEX idx_purchase_date (purchase_date),
        INDEX idx_server_id (server_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
    echo "<p>✓ Purchases table created/verified</p>";
    
    // Add server_id column if it doesn't exist (for existing installations)
    try {
        $pdo->exec("ALTER TABLE purchases ADD COLUMN server_id VARCHAR(50) DEFAULT NULL AFTER ip_address");
        echo "<p>✓ Server ID column added to purchases table</p>";
    } catch (Exception $e) {
        // Column might already exist
        if (strpos($e->getMessage(), 'Duplicate column name') === false) {
            echo "<p>ℹ Server ID column already exists in purchases table</p>";
        }
    }
    
    // Add index for server_id if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE purchases ADD INDEX idx_server_id (server_id)");
        echo "<p>✓ Server ID index added</p>";
    } catch (Exception $e) {
        // Index might already exist
        if (strpos($e->getMessage(), 'Duplicate key name') === false) {
            echo "<p>ℹ Server ID index already exists</p>";
        }
    }
    
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
    echo "<p>✓ Chat messages table created/verified</p>";
    
    // Create lock file
    file_put_contents('db_initialized.lock', date('Y-m-d H:i:s'));
    echo "<p>✓ Database initialization completed successfully!</p>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<ul>";
    echo "<li>Visit <a href='index.php'>the homepage</a> to see the updated navigation</li>";
    echo "<li>Try <a href='register.php'>registering a new account</a></li>";
    echo "<li>Go to <a href='shop.php'>the VIP shop</a> to test the new purchase flow</li>";
    echo "</ul>";
    
    echo "<p><strong>Note:</strong> Make sure your MySQL server is running and the database credentials in config.php are correct.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database setup failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config.php</p>";
}
?>