<?php
// stripe_payments.php - Опростена Stripe интеграция без composer

class StripePayments {
    private $secret_key;
    private $publishable_key;
    private $currency;
    private $api_base = 'https://api.stripe.com/v1/';
    
    public function __construct($secret_key, $publishable_key, $currency = 'bgn') {
        $this->secret_key = $secret_key;
        $this->publishable_key = $publishable_key;
        $this->currency = $currency;
    }
    
    /**
     * Създава Stripe Payment Intent
     */
    public function createPaymentIntent($amount, $description = '', $metadata = []) {
        $url = $this->api_base . 'payment_intents';
        
        $data = [
            'amount' => intval($amount * 100), // Stripe използва стотинки
            'currency' => $this->currency,
            'description' => $description,
            'metadata' => $metadata,
            'automatic_payment_methods' => [
                'enabled' => 'true',
                'allow_redirects' => 'never' // За да работи с Payment Element без redirect
            ]
        ];
        
        $response = $this->makeRequest($url, $data);
        
        if ($response && isset($response['client_secret'])) {
            return [
                'success' => true,
                'client_secret' => $response['client_secret'],
                'payment_intent_id' => $response['id']
            ];
        }
        
        return [
            'success' => false,
            'error' => $response['error']['message'] ?? 'Грешка при създаване на плащане'
        ];
    }
    
    /**
     * Проверява статуса на Payment Intent
     */
    public function getPaymentIntent($payment_intent_id) {
        $url = $this->api_base . 'payment_intents/' . $payment_intent_id;
        
        $response = $this->makeRequest($url, null, 'GET');
        
        if ($response && isset($response['id'])) {
            return [
                'success' => true,
                'status' => $response['status'],
                'amount' => $response['amount'] / 100, // Конвертиране обратно в лева
                'metadata' => $response['metadata'] ?? []
            ];
        }
        
        return [
            'success' => false,
            'error' => $response['error']['message'] ?? 'Грешка при проверка на плащане'
        ];
    }
    
    /**
     * Прави HTTP заявка към Stripe API
     */
    private function makeRequest($url, $data = null, $method = 'POST') {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->secret_key . ':',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);
        
        if ($method === 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false) {
            return ['error' => ['message' => 'Мрежова грешка при свързване със Stripe']];
        }
        
        $decoded = json_decode($response, true);
        
        if ($http_code >= 400) {
            return $decoded; // Stripe error response
        }
        
        return $decoded;
    }
    
    /**
     * Връща publishable key за frontend
     */
    public function getPublishableKey() {
        return $this->publishable_key;
    }
}

/**
 * Инициализира Stripe клиент с конфигурацията
 */
function initStripe($payment_config) {
    return new StripePayments(
        $payment_config['stripe']['secret_key'],
        $payment_config['stripe']['publishable_key'],
        $payment_config['stripe']['currency']
    );
}

/**
 * Обработва Stripe webhook за потвърждение на плащане
 */
function handleStripeWebhook($payment_config) {
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    
    // За опростяване, без webhook signature verification
    // В production среда трябва да се добави webhook secret и verification
    
    $event = json_decode($payload, true);
    
    if ($event && $event['type'] === 'payment_intent.succeeded') {
        $payment_intent = $event['data']['object'];
        $metadata = $payment_intent['metadata'];
        
        // Логика за активиране на VIP статуса
        if (isset($metadata['steamid']) && isset($metadata['item']) && isset($metadata['server_id'])) {
            return [
                'success' => true,
                'steamid' => $metadata['steamid'],
                'item' => $metadata['item'],
                'server_id' => $metadata['server_id'],
                'amount' => $payment_intent['amount'] / 100
            ];
        }
    }
    
    return ['success' => false, 'error' => 'Невалиден webhook'];
}
?>