<?php
// chat_api.php - Chat API for AJAX requests
session_start();
require_once 'config.php';
require_once 'database.php';

header('Content-Type: application/json');

// Check if chat is enabled
if (!$widget_config['chat']['enabled']) {
    echo json_encode(['success' => false, 'message' => 'Chat is disabled']);
    exit;
}

$user = getCurrentUser();

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'send_message':
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'You need to be logged to write in chat']);
            exit;
        }
        
        $message = $_POST['message'] ?? '';
        $result = addChatMessage($user['id'], $user['username'], $message);
        echo json_encode($result);
        break;
        
    case 'get_messages':
        $messages = getChatMessages($widget_config['chat']['max_messages']);
        echo json_encode(['success' => true, 'messages' => $messages]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
        break;
}
?>