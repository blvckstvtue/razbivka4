<?php
// keys_management.php - Управление на ключове за магазина
require_once 'config.php';
require_once 'database.php';

// Създаване на таблица за ключовете
function createKeysTable() {
    $pdo = getDatabase();
    
    $sql = "
    CREATE TABLE IF NOT EXISTS store_keys (
        id INT AUTO_INCREMENT PRIMARY KEY,
        key_code VARCHAR(255) NOT NULL UNIQUE,
        key_type ENUM('ingame_vipkey', 'ingame_trialvipkey', 'ingame_shopkey1', 'ingame_shopkey2') NOT NULL,
        server_id VARCHAR(50) NOT NULL,
        item_key VARCHAR(50) NOT NULL,
        is_used BOOLEAN DEFAULT FALSE,
        used_by_steam_id VARCHAR(50) NULL,
        used_by_user_id INT NULL,
        used_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_key_type (key_type),
        INDEX idx_server_id (server_id),
        INDEX idx_item_key (item_key),
        INDEX idx_is_used (is_used),
        FOREIGN KEY (used_by_user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
}

// Генериране на уникален ключ
function generateUniqueKey($length = 32) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $key;
}

// Добавяне на ключове в базата данни
function addKeys($key_type, $server_id, $item_key, $count = 1) {
    $pdo = getDatabase();
    
    $keys_added = [];
    
    for ($i = 0; $i < $count; $i++) {
        $attempts = 0;
        $max_attempts = 10;
        
        do {
            $key_code = generateUniqueKey();
            $attempts++;
            
            try {
                $stmt = $pdo->prepare("INSERT INTO store_keys (key_code, key_type, server_id, item_key) VALUES (?, ?, ?, ?)");
                $stmt->execute([$key_code, $key_type, $server_id, $item_key]);
                $keys_added[] = $key_code;
                break;
            } catch (PDOException $e) {
                if ($attempts >= $max_attempts) {
                    throw new Exception("Unsuccessful generation of a unique key after $max_attempts attempts");
                }
                // Ключът вече съществува, опитай отново
            }
        } while ($attempts < $max_attempts);
    }
    
    return $keys_added;
}

// Получаване на наличен ключ за даден тип и предмет
function getAvailableKey($key_type, $server_id, $item_key) {
    $pdo = getDatabase();
    
    try {
        $stmt = $pdo->prepare("
            SELECT id, key_code 
            FROM store_keys 
            WHERE key_type = ? AND server_id = ? AND item_key = ? AND is_used = FALSE 
            ORDER BY created_at ASC 
            LIMIT 1
        ");
        $stmt->execute([$key_type, $server_id, $item_key]);
        
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

// Отбелязване на ключ като използван
function markKeyAsUsed($key_id, $steam_id, $user_id = null) {
    $pdo = getDatabase();
    
    try {
        $stmt = $pdo->prepare("
            UPDATE store_keys 
            SET is_used = TRUE, used_by_steam_id = ?, used_by_user_id = ?, used_at = NOW() 
            WHERE id = ? AND is_used = FALSE
        ");
        $stmt->execute([$steam_id, $user_id, $key_id]);
        
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

// Получаване на статистики за ключовете
function getKeyStatistics($server_id = null, $item_key = null) {
    $pdo = getDatabase();
    
    $where_conditions = [];
    $params = [];
    
    if ($server_id) {
        $where_conditions[] = "server_id = ?";
        $params[] = $server_id;
    }
    
    if ($item_key) {
        $where_conditions[] = "item_key = ?";
        $params[] = $item_key;
    }
    
    $where_clause = $where_conditions ? "WHERE " . implode(" AND ", $where_conditions) : "";
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                key_type,
                server_id,
                item_key,
                COUNT(*) as total_keys,
                SUM(CASE WHEN is_used = FALSE THEN 1 ELSE 0 END) as available_keys,
                SUM(CASE WHEN is_used = TRUE THEN 1 ELSE 0 END) as used_keys
            FROM store_keys 
            $where_clause
            GROUP BY key_type, server_id, item_key
            ORDER BY server_id, item_key, key_type
        ");
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Проверка дали даден предмет има ключ
function itemHasKey($item_config) {
    $key_types = ['ingame_vipkey', 'ingame_trialvipkey', 'ingame_shopkey1', 'ingame_shopkey2'];
    
    foreach ($key_types as $key_type) {
        if (isset($item_config[$key_type]) && $item_config[$key_type] === true) {
            return $key_type;
        }
    }
    
    return false;
}

// Получаване на съобщение за ключа според типа
function getKeyMessage($key_type, $item_name, $key_code) {
    $messages = [
        'ingame_vipkey' => "You have successfully purchased a VIP key for $item_name! Your key: $key_code. Activate it in the game with the command /vip $key_code",
        'ingame_trialvipkey' => "You have successfully purchased a Trial VIP key for $item_name! Your key: $key_code. Activate it in the game with the command /vip $key_code", 
        'ingame_shopkey1' => "You have successfully purchased a Shop 1 key for $item_name! Your key: $key_code. Activate it in the game with the command /vip $key_code",
        'ingame_shopkey2' => "You have successfully purchased a Shop 2 key for $item_name! Your key: $key_code. Activate it in the game with the command /vip $key_code"
    ];
    
    return $messages[$key_type] ?? "You have successfully purchased key for $item_name! Your key: $key_code, activate it ingame with /vip $key_code";
}

// Инициализация на таблицата при първо зареждане
try {
    createKeysTable();
} catch (Exception $e) {
    error_log("Error in creating the key table: " . $e->getMessage());
}

?>