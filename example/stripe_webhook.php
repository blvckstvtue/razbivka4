<?php
// stripe_webhook.php - Webhook обработчик за Stripe плащания

require_once 'config.php';
require_once 'database.php';
require_once 'rcon.php';
require_once 'stripe_payments.php';

// Проверка дали плащанията са в live режим
if ($payment_config['testpayments']) {
    http_response_code(200);
    exit('Webhook не е активен в test режим');
}

try {
    $webhook_result = handleStripeWebhook($payment_config);
    
    if ($webhook_result['success']) {
        $steamid = $webhook_result['steamid'];
        $item = $webhook_result['item'];
        $server_id = $webhook_result['server_id'];
        $amount = $webhook_result['amount'];
        
        // Проверка дали сървъра и артикула са валидни
        if (!isset($servers_config[$server_id]) || !isset($servers_config[$server_id]['vip_items'][$item])) {
            error_log("Webhook error: Invalid server or item - Server: $server_id, Item: $item");
            http_response_code(400);
            exit('Невалиден сървър или артикул');
        }
        
        $server_vip_items = $servers_config[$server_id]['vip_items'];
        $server_rcon_config = $servers_config[$server_id]['rcon'];
        $vip_item = $server_vip_items[$item];
        
        // Активиране на VIP статуса чрез RCON
        $command = str_replace('{steamid}', $steamid, $vip_item['rcon_command']);
        $rcon_result = sendRconCommand($command, $server_rcon_config);
        
        if ($rcon_result['success']) {
            // Лог на транзакцията
            $log = date('Y-m-d H:i:s') . " - [STRIPE] {$steamid} закупи {$item} за {$amount} лв. на сървър {$servers_config[$server_id]['name']}\n";
            file_put_contents('purchases.log', $log, FILE_APPEND);
            
            // Запис в базата данни
            try {
                $pdo = getDatabase();
                $stmt = $pdo->prepare("INSERT INTO purchases (user_id, steam_id, item_key, item_name, price, ip_address, server_id, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $webhook_result['user_id'] ?? null,
                    $steamid,
                    $item,
                    $vip_item['name'],
                    $amount,
                    $_SERVER['REMOTE_ADDR'] ?? 'webhook',
                    $server_id,
                    'stripe'
                ]);
            } catch (Exception $e) {
                error_log("Database error in webhook: " . $e->getMessage());
            }
            
            http_response_code(200);
            echo 'VIP статус активиран успешно';
        } else {
            error_log("RCON error in webhook: " . $rcon_result['error']);
            http_response_code(500);
            echo 'Грешка при активиране на VIP статуса';
        }
    } else {
        error_log("Webhook processing failed: " . $webhook_result['error']);
        http_response_code(400);
        echo $webhook_result['error'];
    }
} catch (Exception $e) {
    error_log("Webhook exception: " . $e->getMessage());
    http_response_code(500);
    echo 'Вътрешна грешка на сървъра';
}
?>