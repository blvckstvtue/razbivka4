<?php
// shop.php - VIP Магазин
session_start();
require_once 'config.php';
require_once 'database.php';
require_once 'rcon.php';
require_once 'stripe_payments.php';
require_once 'keys_management.php';

$user = getCurrentUser();

// Обработка на redirect от PayPal/Stripe плащане
if (isset($_GET['payment_success']) && isset($_GET['payment_intent'])) {
    $payment_intent_id = $_GET['payment_intent'];
    $redirect_status = $_GET['redirect_status'] ?? '';
    
    if ($redirect_status === 'succeeded' && !$payment_config['testpayments']) {
        try {
            // Проверяваме статуса на плащането
            $stripe = initStripe($payment_config);
            $payment_check = $stripe->getPaymentIntent($payment_intent_id);
            
            if ($payment_check['success'] && $payment_check['status'] === 'succeeded') {
                $metadata = $payment_check['metadata'];
                $steamid = $metadata['steamid'] ?? '';
                $item = $metadata['item'] ?? '';
                $server_id = $metadata['server_id'] ?? '';
                
                if ($steamid && $item && $server_id && isset($servers_config[$server_id]['vip_items'][$item])) {
                    $server_vip_items = $servers_config[$server_id]['vip_items'];
                    $server_rcon_config = $servers_config[$server_id]['rcon'];
                    $vip_item = $server_vip_items[$item];
                    
                    // Проверка дали предметът има ключ
                    $key_type = itemHasKey($vip_item);
                    $key_data = null;
                    $activation_success = true;
                    
                    if ($key_type) {
                        // Предметът изисква ключ - вземаме наличен ключ
                        $key_data = getAvailableKey($key_type, $server_id, $item);
                        
                        if (!$key_data) {
                            $payment_error_message = 'Payment was successful, but no keys are available for this item. Please contact administrator.';
                            $activation_success = false;
                        } else {
                            // Отбелязваме ключа като използван
                            markKeyAsUsed($key_data['id'], $steamid, $user ? $user['id'] : null);
                        }
                    } else {
                        // Активираме VIP статуса с RCON команда
                        $command = str_replace('{steamid}', $steamid, $vip_item['rcon_command']);
                        $rcon_result = sendRconCommand($command, $server_rcon_config);
                        
                        if (!$rcon_result['success']) {
                            $payment_error_message = 'Payment was successful, but there was an activation error: ' . $rcon_result['error'];
                            $activation_success = false;
                        }
                    }
                    
                    if ($activation_success) {
                        // Лог на транзакцията
                        $username_info = $user ? " (user: {$user['username']})" : "";
                        $key_info = $key_type && $key_data ? " [KEY: {$key_data['key_code']}]" : "";
                        $log = date('Y-m-d H:i:s') . " - [PAYPAL] {$steamid}{$username_info} purchase {$item} for {$payment_check['amount']} eur in server {$servers_config[$server_id]['name']}{$key_info}\n";
                        file_put_contents('purchases.log', $log, FILE_APPEND);
                        
                        // Запис в базата данни
                        try {
                            $pdo = getDatabase();
                            $stmt = $pdo->prepare("INSERT INTO purchases (user_id, steam_id, item_key, item_name, price, ip_address, server_id, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([
                                $user ? $user['id'] : null,
                                $steamid,
                                $item,
                                $vip_item['name'],
                                $payment_check['amount'],
                                $_SERVER['REMOTE_ADDR'] ?? null,
                                $server_id,
                                'paypal'
                            ]);
                        } catch (Exception $e) {
                            error_log("Database error after PayPal payment: " . $e->getMessage());
                        }
                        
                        if ($key_type && $key_data) {
                            $payment_success_message = getKeyMessage($key_type, $vip_item['name'], $key_data['key_code']);
                        } else {
                            $payment_success_message = 'Successfully purchased ' . $vip_item['name'] . ' for ' . $steamid . ' with paypal!';
                        }
                    }
                } else {
                    $payment_error_message = 'The payment was successful, but there is no data to activate the VIP status.';
                }
            } else {
                $payment_error_message = 'Payment verification error.';
            }
        } catch (Exception $e) {
            $payment_error_message = 'Payment processing error: ' . $e->getMessage();
        }
    } elseif ($redirect_status === 'failed') {
        $payment_error_message = 'Payment was canceled or failed.';
    }
    
    // Redirect без URL параметрите за да се избегне refresh проблеми
    $clean_url = strtok($_SERVER["REQUEST_URI"], '?');
    if (isset($_GET['server'])) {
        $clean_url .= '?server=' . $_GET['server'];
    }
    
    echo "<script>
        window.history.replaceState({}, '', '$clean_url');
    </script>";
}

// Обработка на избрания сървър
$selected_server = $_GET['server'] ?? null;

// Проверка дали избрания сървър е валиден
if (!$selected_server || !isset($servers_config[$selected_server]) || !$servers_config[$selected_server]['shop_enabled']) {
    // Намиране на първия наличен сървър с магазин
    $selected_server = null;
    foreach ($servers_config as $server_id => $server) {
        if ($server['shop_enabled'] && !empty($server['vip_items'])) {
            $selected_server = $server_id;
            break;
        }
    }
    
    // Ако няма налични сървъри с магазини
    if (!$selected_server) {
        die('There are no servers with shops available at the moment.');
    }
}

$current_server = $servers_config[$selected_server];
$current_vip_items = $current_server['vip_items'];

// Генериране на уникален токен за защита от повторни заявки
if (!isset($_SESSION['purchase_token'])) {
    $_SESSION['purchase_token'] = bin2hex(random_bytes(32));
}

// Обработка на AJAX заявка за създаване на Stripe Payment Intent
if (isset($_POST['action']) && $_POST['action'] === 'create_payment_intent') {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['purchase_token']) {
        $_SESSION['purchase_token'] = bin2hex(random_bytes(32));
        
        $response = [
            'success' => false,
            'message' => 'Session has expired. Please try again.',
            'new_token' => $_SESSION['purchase_token']
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // НЕ унищожаваме token-а тук, защото ще го използваме за следващата заявка
    
    $item = $_POST['item'];
    $server_id = $_POST['server'] ?? $selected_server;
    $steamid = '';
    
    if ($user && $user['steam_id']) {
        $steamid = $user['steam_id'];
    } else {
        $steamid = trim($_POST['steamid'] ?? '');
    }
    
    $response = ['success' => false, 'message' => ''];
    
    // Проверка дали сървъра е валиден
    if (!isset($servers_config[$server_id]) || !$servers_config[$server_id]['shop_enabled']) {
        $response['message'] = 'Unknown server!';
    } elseif (empty($steamid)) {
        $response['message'] = 'SteamID is required!';
    } elseif (!preg_match('/^STEAM_[01]:[01]:\d+$/', $steamid)) {
        $response['message'] = 'Wrong SteamID format! Use: STEAM_X:Y:Z';
    } elseif (!isset($servers_config[$server_id]['vip_items'][$item])) {
        $response['message'] = 'Unknown item!';
    } else {
        $vip_item = $servers_config[$server_id]['vip_items'][$item];
        
        try {
            $stripe = initStripe($payment_config);
            $payment_intent = $stripe->createPaymentIntent(
                $vip_item['price'],
                "VIP покупка: {$vip_item['name']} за {$steamid}",
                [
                    'steamid' => $steamid,
                    'item' => $item,
                    'server_id' => $server_id,
                    'user_id' => $user ? $user['id'] : null
                ]
            );
            
            if ($payment_intent['success']) {
                $response['success'] = true;
                $response['client_secret'] = $payment_intent['client_secret'];
                $response['publishable_key'] = $stripe->getPublishableKey();
            } else {
                $response['message'] = $payment_intent['error'];
            }
        } catch (Exception $e) {
            $response['message'] = 'Error initializing payment: ' . $e->getMessage();
        }
         }
     
     // Запазваме същия token за следващата заявка (purchase)
     $response['new_token'] = $_SESSION['purchase_token'];
     
     header('Content-Type: application/json');
     echo json_encode($response);
     exit;
}

// Обработка на AJAX заявка за покупка
if (isset($_POST['action']) && $_POST['action'] === 'purchase') {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['purchase_token']) {
        // Невалиден токен - генерираме нов и връщаме грешка
        $_SESSION['purchase_token'] = bin2hex(random_bytes(32));
        
        $response = [
            'success' => false,
            'message' => 'Session has expired. Please try again.',
            'new_token' => $_SESSION['purchase_token']
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    // Унищожаване на токена за еднократна употреба
    unset($_SESSION['purchase_token']);
    
    // Генериране на нов токен за следващата покупка
    $_SESSION['purchase_token'] = bin2hex(random_bytes(32));
    
    // Get Steam ID from user account or POST data
    $steamid = '';
    if ($user && $user['steam_id']) {
        $steamid = $user['steam_id'];
    } else {
        $steamid = trim($_POST['steamid'] ?? '');
    }
    
    $item = $_POST['item'];
    $server_id = $_POST['server'] ?? $selected_server;
    
    $response = ['success' => false, 'message' => ''];
    
    // Проверка дали сървъра е валиден
    if (!isset($servers_config[$server_id]) || !$servers_config[$server_id]['shop_enabled']) {
        $response['message'] = 'Невалиден сървър!';
    } else {
        $server_vip_items = $servers_config[$server_id]['vip_items'];
        $server_rcon_config = $servers_config[$server_id]['rcon'];
        
        // Валидация
        if (empty($steamid)) {
            $response['message'] = 'SteamID is required!';
        } elseif (!preg_match('/^STEAM_[01]:[01]:\d+$/', $steamid)) {
            $response['message'] = 'Wrong SteamID format! Use: STEAM_X:Y:Z';
        } elseif (!isset($server_vip_items[$item])) {
            $response['message'] = 'Unknown item!';
        } else {
            // Проверка за test mode или реално плащане
            if ($payment_config['testpayments']) {
                // Test mode - симулация на успешно плащане
                $payment_success = true;
            } else {
                // Реален Stripe payment - проверка на payment intent
                $payment_intent_id = $_POST['payment_intent_id'] ?? '';
                if (empty($payment_intent_id)) {
                    $response['message'] = 'Payment intent ID is missing!';
                    $payment_success = false;
                } else {
                    try {
                        $stripe = initStripe($payment_config);
                        $payment_check = $stripe->getPaymentIntent($payment_intent_id);
                        
                        if ($payment_check['success'] && $payment_check['status'] === 'succeeded') {
                            $payment_success = true;
                        } else {
                            $payment_success = false;
                            $response['message'] = 'Payment was not completed successfully!';
                        }
                    } catch (Exception $e) {
                        $payment_success = false;
                        $response['message'] = 'Payment verification error: ' . $e->getMessage();
                    }
                }
            }
            
            if ($payment_success) {
                // Проверка дали предметът има ключ
                $key_type = itemHasKey($server_vip_items[$item]);
                $key_data = null;
                
                if ($key_type) {
                    // Предметът изисква ключ - вземаме наличен ключ
                    $key_data = getAvailableKey($key_type, $server_id, $item);
                    
                    if (!$key_data) {
                        $response['message'] = 'Няма налични ключове за този предмет. Моля свържете се с администратор.';
                        $payment_success = false;
                    }
                }
                
                if ($payment_success) {
                    // Ако предметът няма ключ, изпълняваме RCON команда
                    if (!$key_type) {
                        $command = str_replace('{steamid}', $steamid, $server_vip_items[$item]['rcon_command']);
                        $rcon_result = sendRconCommand($command, $server_rcon_config);
                        
                        if (!$rcon_result['success']) {
                            $response['message'] = 'Error with activating: ' . $rcon_result['error'];
                            $payment_success = false;
                        }
                    }
                    
                    if ($payment_success) {
                        $response['success'] = true;
                        
                        if ($key_type && $key_data) {
                            // Отбелязваме ключа като използван
                            markKeyAsUsed($key_data['id'], $steamid, $user ? $user['id'] : null);
                            
                            // Връщаме специално съобщение с ключа
                            $response['key_message'] = getKeyMessage($key_type, $server_vip_items[$item]['name'], $key_data['key_code']);
                            $response['show_key'] = true;
                            $response['message'] = 'Successfully purchased ' . $server_vip_items[$item]['name'] . ' for ' . $steamid . ' in server ' . $servers_config[$server_id]['name'] . '!';
                        } else {
                            $response['message'] = 'Successfully purchased ' . $server_vip_items[$item]['name'] . ' for ' . $steamid . ' in server ' . $servers_config[$server_id]['name'] . '!';
                        }
                        
                        // Лог на транзакцията
                        $username_info = $user ? " (user: {$user['username']})" : "";
                        $key_info = $key_type && $key_data ? " [KEY: {$key_data['key_code']}]" : "";
                        $log = date('Y-m-d H:i:s') . " - {$steamid}{$username_info} purchase {$item} for {$server_vip_items[$item]['price']} eur in server {$servers_config[$server_id]['name']}{$key_info}\n";
                        file_put_contents('purchases.log', $log, FILE_APPEND);
                        
                        // Save to database if available
                        try {
                            $pdo = getDatabase();
                            $stmt = $pdo->prepare("INSERT INTO purchases (user_id, steam_id, item_key, item_name, price, ip_address, server_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([
                                $user ? $user['id'] : null,
                                $steamid,
                                $item,
                                $server_vip_items[$item]['name'],
                                $server_vip_items[$item]['price'],
                                $_SERVER['REMOTE_ADDR'] ?? null,
                                $server_id
                            ]);
                        } catch (Exception $e) {
                            // Database logging failed, but continue with the purchase
                            error_log("Failed to log purchase to database: " . $e->getMessage());
                        }
                    }
                }
            } else {
                $response['message'] = 'Error in payment!';
            }
        }
    }
    
    // Генериране на нов токен за следваща покупка
    $_SESSION['purchase_token'] = bin2hex(random_bytes(32));
    
    // Добавяне на новия токен към response-а
    $response['new_token'] = $_SESSION['purchase_token'];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIP Магазин - <?= $site_config['site_name'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php if (!$payment_config['testpayments']): ?>
        <script src="https://js.stripe.com/v3/"></script>
    <?php endif; ?>
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
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--primary-color);
            transition: all 0.3s ease;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
            left: 0;
        }

        /* User Menu */
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
            z-index: 1000;
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Server Selection */
        .server-selection {
            margin-bottom: 40px;
        }

        .server-selection h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            text-align: center;
        }

        .server-tabs {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .server-tab {
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: 15px;
            padding: 15px 25px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 150px;
        }

        .server-tab:hover {
            border-color: var(--primary-color);
            background: var(--surface-light);
            transform: translateY(-2px);
        }

        .server-tab.active {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .server-ip {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 5px;
            font-family: 'Courier New', monospace;
        }

        .current-server-info {
            text-align: center;
            margin: 40px 0;
            padding: 20px;
            background: var(--surface);
            border-radius: 15px;
            border: 1px solid var(--border);
        }

        .current-server-info h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .current-server-info p {
            color: var(--text-secondary);
            font-family: 'Courier New', monospace;
        }

        /* Header */
        .page-header {
            text-align: center;
            padding: 80px 0 60px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            margin-bottom: 80px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .page-header h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .page-header .subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            font-weight: 500;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 100px;
        }

        .product-card {
            background: var(--surface);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .product-content {
            padding: 30px;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .product-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .product-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent-color);
            text-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .product-description {
            color: var(--text-secondary);
            margin-bottom: 20px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .product-features {
            margin-bottom: 24px;
        }

        .features-list {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--text-secondary);
            padding: 8px 0;
        }

        .feature-item i {
            color: var(--accent-color);
            margin-right: 10px;
            width: 16px;
            font-size: 0.75rem;
        }

        .buy-button {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            overflow: hidden;
        }

        .buy-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
        }

        .buy-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .buy-button:hover::before {
            left: 100%;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: var(--surface);
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            transform: scale(0.9);
            animation: modalSlideIn 0.3s ease forwards;
        }

        @keyframes modalSlideIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .close-button {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .close-button:hover {
            background: var(--surface-light);
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            background: var(--background);
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
            opacity: 0.7;
        }

        .steamid-help {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .steamid-help h4 {
            color: #3b82f6;
            margin-bottom: 8px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .steamid-help ul {
            list-style: none;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .steamid-help li {
            margin-bottom: 4px;
            padding-left: 16px;
            position: relative;
        }

        .steamid-help li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: #3b82f6;
        }

        .submit-button {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-color) 0%, #059669 100%);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            overflow: hidden;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.4);
        }

        .submit-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .submit-button.success-state {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            opacity: 1;
        }

        .submit-button.success-state:hover {
            transform: none;
            box-shadow: 0 8px 16px rgba(34, 197, 94, 0.3);
        }

        .submit-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-button:hover::before {
            left: 100%;
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 500;
            border-left: 4px solid;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border-color: #10b981;
        }

        .alert.error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: #ef4444;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .modal-content {
                padding: 24px;
                margin: 20px;
            }
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
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php" class="active">Store</a></li>
                <li><a href="<?= $site_config['discord_url'] ?>" target="_blank">Discord</a></li>
                <!--<li><a href="<?= $site_config['steam_group'] ?>" target="_blank">Steam Group</a></li>-->
                <li><a href="https://zanedemos.zone.id/">Demos</a></li>
                <li><a href="https://zanestats.zone.id/" target="_blank">Stats</a></li>
                <li><a href="https://zanebans.zone.id/" target="_blank">Bans</a></li>
                <?php if ($user): ?>
                    <li class="user-menu">
                        <a href="#" onclick="toggleUserMenu()">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($user['username']) ?>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                            <a href="settings.php?logout=1"><i class="fas fa-sign-out-alt"></i> Exit</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> Store</h1>
            <p class="subtitle">Choose VIP or some packet and receive ingame rewards.</p>
        </div>
    </div>

    <!-- Payment Status Messages -->
    <?php if (isset($payment_success_message)): ?>
        <div class="container">
            <div class="alert success" style="margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($payment_success_message) ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($payment_error_message)): ?>
        <div class="container">
            <div class="alert error" style="margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($payment_error_message) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Server Selection -->
    <div class="container">
        <div class="server-selection">
            <h2>Choose server:</h2>
            <div class="server-tabs">
                <?php foreach ($servers_config as $server_id => $server): ?>
                    <?php if ($server['shop_enabled'] && !empty($server['vip_items'])): ?>
                        <a href="shop.php?server=<?= $server_id ?>" 
                           class="server-tab <?= $selected_server === $server_id ? 'active' : '' ?>">
                            <?= htmlspecialchars($server['name']) ?>
                            <span class="server-ip"><?= htmlspecialchars($server['ip']) ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="current-server-info">
            <h2>Store for: <?= htmlspecialchars($current_server['name']) ?></h2>
            <p>Server: <?= htmlspecialchars($current_server['ip']) ?></p>
        </div>
    </div>

    <!-- Products -->
    <div class="container">
        <div class="products-grid">
            <?php foreach ($current_vip_items as $key => $item): ?>
                <div class="product-card">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="product-image">
                    <div class="product-content">
                        <div class="product-header">
                            <div>
                                <h3 class="product-name"><?= $item['name'] ?></h3>
                                <p class="product-description"><?= $item['description'] ?></p>
                            </div>
                            <div class="product-price"><?= number_format($item['price'], 2) ?> €</div>
                        </div>
                        
                        <div class="product-features">
                            <ul class="features-list">
                                <?php foreach ($item['features'] as $feature): ?>
                                    <li class="feature-item">
                                        <i class="fas fa-check"></i>
                                        <?= $feature ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <button class="buy-button" onclick="openPurchaseModal('<?= $key ?>', '<?= $item['name'] ?>', <?= $item['price'] ?>)">
                            <i class="fas fa-shopping-cart"></i> Purchase
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Purchase Modal -->
    <div class="modal" id="purchaseModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Product Details</h2>
                <button class="close-button" onclick="closePurchaseModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="alertContainer"></div>
            
            <form id="purchaseForm">
                <input type="hidden" name="action" value="purchase">
                <input type="hidden" name="token" value="<?= $_SESSION['purchase_token'] ?>">
                <input type="hidden" name="item" id="selectedItem">
                <input type="hidden" name="server" value="<?= $selected_server ?>">
                
                <div class="form-group">
                    <label class="form-label">Choosen:</label>
                    <div id="selectedItemDisplay" style="font-size: 1.2rem; font-weight: 600; color: var(--accent-color); padding: 16px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);"></div>
                </div>
                
                <?php if ($user && $user['steam_id']): ?>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fab fa-steam"></i> Steam ID:
                        </label>
                        <div style="font-size: 1.1rem; font-weight: 500; color: var(--accent-color); padding: 16px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);">
                            <?= htmlspecialchars($user['steam_id']) ?>
                            <small style="display: block; color: var(--text-secondary); margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Auto-filled from profile
                            </small>
                        </div>
                        <p style="margin-top: 10px; color: var(--text-secondary); font-size: 0.9rem;">
                            Want to change your Steam ID? <a href="settings.php" style="color: var(--primary-color);">Go to settings</a>
                        </p>
                    </div>
                <?php else: ?>
                    <?php if ($user): ?>
                        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger-color); color: var(--danger-color); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> You do not have a Steam ID assigned to your account.
                            <a href="settings.php" style="color: var(--danger-color); text-decoration: underline;">Add it to settings</a> for easier shopping.
                        </div>
                    <?php endif; ?>
                    
                    <div class="steamid-help">
                        <h4><i class="fas fa-info-circle"></i> How do I find my SteamID?</h4>
                        <ul>
                            <li>Join some of ours servers</li>
                            <li>Open the chat with your key (T)</li>
                            <li>Write <strong>!steamid</strong> then copy from (~) or chat</li>
                            <li>It should looks like: STEAM_0:1:12345678</li>
                        </ul>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="steamidInput">
                            <i class="fab fa-steam"></i> SteamID:
                        </label>
                        <input type="text" 
                               name="steamid" 
                               id="steamidInput" 
                               class="form-input" 
                               placeholder="STEAM_0:1:12345678" 
                               required
                               pattern="^STEAM_[01]:[01]:\d+$">
                    </div>
                <?php endif; ?>
                
                <?php if (!$payment_config['testpayments']): ?>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-credit-card"></i> Choose a payment method:
                        </label>
                        <div id="payment-element" style="padding: 16px; background: var(--surface-light); border: 1px solid var(--border); border-radius: 12px; min-height: 80px;">
                            <!-- Stripe Payment Elements ще се зареди тук (карти, PayPal и др.) -->
                        </div>
                        <div id="payment-errors" role="alert" style="color: var(--danger-color); margin-top: 8px; font-size: 0.9rem;"></div>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="submit-button" id="submitButton">
                    <i class="fas fa-credit-card"></i> 
                    <?php if ($payment_config['testpayments']): ?>
                        Complete the purchase (TEST)
                    <?php else: ?>
                        Pay
                    <?php endif; ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Key Modal -->
    <div class="key-modal" id="keyModal">
        <div class="key-modal-content">
            <h2><i class="fas fa-key"></i> Your key</h2>
            <div id="keyMessage"></div>
            <div class="key-code" id="keyCode"></div>
            <div class="key-instructions">
                <i class="fas fa-lightbulb"></i> 
                Copy the key and activate it in the game with the command shown above!
            </div>
            <div class="key-modal-buttons">
                <button type="button" class="copy-btn" onclick="copyKey()">
                    <i class="fas fa-copy"></i> Copy the key
                </button>
                <button type="button" class="close-btn" onclick="closeKeyModal()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentToken = '<?= $_SESSION['purchase_token'] ?>';
        const isTestMode = <?= $payment_config['testpayments'] ? 'true' : 'false' ?>;
        
        <?php if (!$payment_config['testpayments']): ?>
        // Stripe инициализация
        let stripe;
        const stripePublishableKey = '<?= $payment_config['stripe']['publishable_key'] ?>';
        
        function initializeStripe() {
            console.log('Initializing Stripe...');
            console.log('Using publishable key:', stripePublishableKey);
            
            // Проверка дали имаме валиден publishable key
            if (!stripePublishableKey || stripePublishableKey.includes('your_publishable_key_here')) {
                console.error('Invalid Stripe publishable key! Please add a real key in config.php');
                const paymentErrors = document.getElementById('payment-errors');
                if (paymentErrors) {
                    paymentErrors.textContent = 'Configuration error: Invalid Stripe key';
                }
                return;
            }
            
            if (!stripe) {
                try {
                    stripe = Stripe(stripePublishableKey);
                    console.log('Stripe instance created');
                } catch (e) {
                    console.error('Error creating Stripe instance:', e);
                    const paymentErrors = document.getElementById('payment-errors');
                    if (paymentErrors) {
                        paymentErrors.textContent = 'Stripe initialization error: ' + e.message;
                    }
                    return;
                }
            }
        }
        
        function createPaymentIntentForModal() {
            console.log('Creating payment intent for modal...');
            
            const formData = new FormData(document.getElementById('purchaseForm'));
            formData.set('action', 'create_payment_intent');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment intent response:', data);
                
                if (data.new_token) {
                    document.querySelector('input[name="token"]').value = data.new_token;
                    currentToken = data.new_token;
                }
                
                if (data.success) {
                    // Създаваме elements с client secret
                    const elements = stripe.elements({
                        clientSecret: data.client_secret
                    });
                    
                    // Създаваме payment element
                    const paymentElement = elements.create('payment', {
                        layout: 'tabs',
                        paymentMethodOrder: ['card', 'paypal']
                    });
                    
                    // Mount-ваме element-а
                    paymentElement.mount('#payment-element');
                    
                    // Event listener за грешки
                    paymentElement.on('change', function(event) {
                        const displayError = document.getElementById('payment-errors');
                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });
                    
                    // Запазваме за използване при submit
                    window.currentElements = elements;
                    window.currentPaymentElement = paymentElement;
                    window.currentClientSecret = data.client_secret;
                    
                    // Активираме бутона
                    const submitButton = document.getElementById('submitButton');
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Complete payment';
                    
                    console.log('Payment element ready');
                } else {
                    const alertContainer = document.getElementById('alertContainer');
                    alertContainer.innerHTML = `
                        <div class="alert error">
                            <i class="fas fa-exclamation-circle"></i> ${data.message}
                        </div>
                    `;
                    
                    const submitButton = document.getElementById('submitButton');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-exclamation-circle"></i> Грешка';
                }
            })
            .catch(error => {
                console.error('Error creating payment intent:', error);
                const alertContainer = document.getElementById('alertContainer');
                alertContainer.innerHTML = `
                    <div class="alert error">
                        <i class="fas fa-exclamation-triangle"></i> Error initializing payment
                    </div>
                `;
            });
        }
        <?php endif; ?>
        
        function openPurchaseModal(itemKey, itemName, itemPrice) {
            document.getElementById('selectedItem').value = itemKey;
            document.getElementById('selectedItemDisplay').innerHTML = `
                <i class="fas fa-crown"></i> ${itemName} - <span style="color: var(--accent-color)">${itemPrice.toFixed(2)} eur.</span>
            `;
            
            // Clear previous alerts and form
            document.getElementById('alertContainer').innerHTML = '';
            const steamidInput = document.getElementById('steamidInput');
            if (steamidInput) {
                steamidInput.value = '';
            }
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true; // Disable until ready
            if (isTestMode) {
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Complete the purchase (TEST)';
                submitButton.disabled = false;
            } else {
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay';
                submitButton.disabled = true; // Keep disabled until payment intent is ready
            }
            submitButton.classList.remove('success-state');
            
            document.getElementById('purchaseModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // За Stripe режим - инициализираме и проверяваме дали да създадем payment intent веднага
            <?php if (!$payment_config['testpayments']): ?>
            initializeStripe();
            
            // Ако потребителят е логнал и има валиден SteamID, създаваме payment intent веднага
            <?php if ($user && $user['steam_id']): ?>
            if (stripe) {
                const submitButton = document.getElementById('submitButton');
                submitButton.innerHTML = '<span class="loading"></span>Loading payment methods...';
                submitButton.disabled = true;
                createPaymentIntentForModal();
            }
            <?php endif; ?>
            <?php endif; ?>
        }
        
        function closePurchaseModal() {
            document.getElementById('purchaseModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Почистваме грешките при затваряне на modal-а
            <?php if (!$payment_config['testpayments']): ?>
            const paymentErrors = document.getElementById('payment-errors');
            if (paymentErrors) {
                paymentErrors.textContent = '';
            }
            
            // Почистваме payment element container
            const paymentElementContainer = document.getElementById('payment-element');
            if (paymentElementContainer) {
                paymentElementContainer.innerHTML = '';
            }
            
            // Изчистваме запазените references
            window.currentElements = null;
            window.currentPaymentElement = null;
            window.currentClientSecret = null;
            
            console.log('Payment elements cleaned up');
            <?php endif; ?>
        }
        
        // Close modal on outside click
        document.getElementById('purchaseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePurchaseModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePurchaseModal();
            }
        });
        
        // SteamID validation with visual feedback (only if input exists)
        const steamidInput = document.getElementById('steamidInput');
        if (steamidInput) {
            steamidInput.addEventListener('input', function(e) {
                const steamid = e.target.value;
                const isValid = /^STEAM_[01]:[01]:\d+$/.test(steamid);
                
                if (steamid && !isValid) {
                    e.target.style.borderColor = 'var(--danger-color)';
                    e.target.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                } else if (steamid && isValid) {
                    e.target.style.borderColor = 'var(--accent-color)';
                    e.target.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
                    
                    // Ако сме в Stripe режим и все още няма payment intent, създаваме го
                    <?php if (!$payment_config['testpayments']): ?>
                    if (stripe && !window.currentElements) {
                        const submitButton = document.getElementById('submitButton');
                        submitButton.innerHTML = '<span class="loading"></span>Loading payment methods...';
                        submitButton.disabled = true;
                        createPaymentIntentForModal();
                    }
                    <?php endif; ?>
                } else {
                    e.target.style.borderColor = 'var(--border)';
                    e.target.style.boxShadow = 'none';
                    
                    // Ако сме в Stripe режим и има създадени payment elements, изчистваме ги
                    <?php if (!$payment_config['testpayments']): ?>
                    if (window.currentPaymentElement) {
                        window.currentPaymentElement.unmount();
                        window.currentElements = null;
                        window.currentPaymentElement = null;
                        window.currentClientSecret = null;
                        
                        const submitButton = document.getElementById('submitButton');
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay';
                        
                        // Изчистваме payment element контейнера
                        const paymentElementContainer = document.getElementById('payment-element');
                        if (paymentElementContainer) {
                            paymentElementContainer.innerHTML = '';
                        }
                    }
                    <?php endif; ?>
                }
            });
        }
        
        // Form submission with AJAX
        document.getElementById('purchaseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = document.getElementById('submitButton');
            const alertContainer = document.getElementById('alertContainer');
            
            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="loading"></span>Processing...';
            
            // Clear previous alerts
            alertContainer.innerHTML = '';
            
            if (isTestMode) {
                // Test mode - директна покупка
                processTestPurchase();
            } else {
                // Stripe mode - създаване на payment intent и обработка
                processStripePurchase();
            }
        });
        
        function processTestPurchase() {
            const formData = new FormData(document.getElementById('purchaseForm'));
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                handlePurchaseResponse(data, true);
            })
            .catch(error => {
                handlePurchaseError(error);
            });
        }
        
        function processStripePurchase() {
            console.log('Starting Stripe purchase process...');
            
            // Проверяваме дали имаме готови elements
            if (!window.currentElements || !window.currentPaymentElement) {
                handlePurchaseError(new Error('Payment elements not ready'));
                return;
            }
            
            // Потвърждаваме плащането
            stripe.confirmPayment({
                elements: window.currentElements,
                confirmParams: {
                    return_url: window.location.href + (window.location.href.includes('?') ? '&' : '?') + 'payment_success=1'
                },
                redirect: 'if_required'
            })
            .then(result => {
                if (result.error) {
                    // Грешка в плащането
                    document.getElementById('payment-errors').textContent = result.error.message;
                    resetSubmitButton();
                } else {
                    // Плащането е успешно - сега активираме VIP статуса
                    const formData = new FormData(document.getElementById('purchaseForm'));
                    formData.set('payment_intent_id', result.paymentIntent.id);
                    
                    return fetch(window.location.href, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                }
            })
            .then(response => {
                if (response) {
                    return response.json();
                }
            })
            .then(data => {
                if (data) {
                    handlePurchaseResponse(data, false);
                }
            })
            .catch(error => {
                handlePurchaseError(error);
            });
        }
        
        function handlePurchaseResponse(data, isTest) {
            const alertContainer = document.getElementById('alertContainer');
            const submitButton = document.getElementById('submitButton');
            
            if (data.success) {
                // Update token for next purchase
                if (data.new_token) {
                    document.querySelector('input[name="token"]').value = data.new_token;
                }
                
                if (data.show_key && data.key_message) {
                    // Показваме ключ модала
                    showKeyModal(data.key_message);
                    
                    // Затваряме purchase modal
                    closePurchaseModal();
                } else {
                    // Обикновен успех без ключ
                    alertContainer.innerHTML = `
                        <div class="alert success">
                            <i class="fas fa-check-circle"></i> ${data.message}
                        </div>
                    `;
                    
                    // Auto close modal after success
                    setTimeout(() => {
                        closePurchaseModal();
                    }, 3000);
                }
                
                // Clear form
                const steamidInput = document.getElementById('steamidInput');
                if (steamidInput) {
                    steamidInput.value = '';
                }
                
                // Keep button disabled on success to prevent spam
                submitButton.innerHTML = '<i class="fas fa-check"></i> Покупката е завършена';
                submitButton.classList.add('success-state');
            } else {
                alertContainer.innerHTML = `
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> ${data.message}
                    </div>
                `;
                
                // Update token even on error for next attempt
                if (data.new_token) {
                    document.querySelector('input[name="token"]').value = data.new_token;
                }
                
                resetSubmitButton();
            }
        }
        
        function handlePurchaseError(error) {
            console.error('Error:', error);
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `
                <div class="alert error">
                    <i class="fas fa-exclamation-triangle"></i> ${error.message || 'Error connecting to server'}
                </div>
            `;
            resetSubmitButton();
        }
        
        function resetSubmitButton() {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = false;
            if (isTestMode) {
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Complete the purchase (TEST)';
            } else {
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay';
            }
        }

        function showKeyModal(keyMessage) {
            const keyModal = document.getElementById('keyModal');
            const keyMessageDiv = document.getElementById('keyMessage');
            const keyCodeDiv = document.getElementById('keyCode');
            
            // Парсираме съобщението за да извадим ключа
            const keyMatch = keyMessage.match(/ключ:\s*([A-Za-z0-9]{20,})/);
            const keyCode = keyMatch ? keyMatch[1] : '';
            
            // Задаваме съобщението и ключа
            keyMessageDiv.innerHTML = keyMessage.replace(/ключ:\s*[A-Za-z0-9]{20,}/, 'ключ:');
            keyCodeDiv.textContent = keyCode;
            
            // Запазваме ключа глобално за копиране
            window.currentKey = keyCode;
            
            // Показваме модала
            keyModal.classList.add('show');
            
            // Затваряме модала при клик извън него
            keyModal.onclick = function(e) {
                if (e.target === keyModal) {
                    closeKeyModal();
                }
            };
        }

        function closeKeyModal() {
            const keyModal = document.getElementById('keyModal');
            keyModal.classList.remove('show');
        }

        async function copyKey() {
            if (!window.currentKey) return;
            
            const copyBtn = document.querySelector('.copy-btn');
            const originalText = copyBtn.innerHTML;
            
            try {
                await navigator.clipboard.writeText(window.currentKey);
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Копиран!';
                copyBtn.classList.add('copy-success');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.remove('copy-success');
                }, 2000);
            } catch (err) {
                // Fallback за браузъри без clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = window.currentKey;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Копиран!';
                    copyBtn.classList.add('copy-success');
                    
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.classList.remove('copy-success');
                    }, 2000);
                } catch (err2) {
                    copyBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Грешка';
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                    }, 2000);
                }
                document.body.removeChild(textArea);
            }
        }
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.product-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // User menu dropdown
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.user-menu a')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        });
    </script>
    
    <style>
        .product-card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        /* Key Modal Styles */
        .key-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .key-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .key-modal-content {
            background: var(--surface);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .key-modal.show .key-modal-content {
            transform: scale(1);
        }

        .key-modal h2 {
            color: var(--accent-color);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .key-code {
            background: var(--background);
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 20px 0;
            border: 2px solid var(--primary-color);
            word-break: break-all;
        }

        .key-instructions {
            background: var(--surface-light);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid var(--accent-color);
        }

        .key-modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .copy-btn, .close-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .copy-btn {
            background: var(--primary-color);
            color: white;
        }

        .copy-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .close-btn {
            background: var(--surface-light);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .close-btn:hover {
            background: var(--border);
        }

        .copy-success {
            background: var(--accent-color) !important;
        }

        @media (max-width: 768px) {
            .key-modal-content {
                padding: 20px;
                margin: 20px;
            }
            
            .key-modal-buttons {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>