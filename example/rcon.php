<?php
// rcon.php - RCON функционалност

class RconClient {
    private $host;
    private $port;
    private $password;
    private $socket;
    private $requestId;
    private $debug = true;
    
    const SERVERDATA_AUTH = 3;
    const SERVERDATA_EXECCOMMAND = 2;
    const SERVERDATA_AUTH_RESPONSE = 2;
    const SERVERDATA_RESPONSE_VALUE = 0;
    
    public function __construct($host, $port, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->requestId = 1;
    }
    
    private function debug($message) {
        if ($this->debug) {
            $log = date('Y-m-d H:i:s') . " - DEBUG: {$message}\n";
            file_put_contents('rcon_debug.log', $log, FILE_APPEND);
        }
    }
    
    public function connect() {
        $this->debug("Опит за свързване с {$this->host}:{$this->port}");
        
        $this->socket = @fsockopen($this->host, $this->port, $errno, $errstr, 10);
        
        if ($this->socket === false) {
            $this->debug("Грешка при свързване: {$errno} - {$errstr}");
            return false;
        }
        
        $this->debug("Успешно свързване");
        stream_set_timeout($this->socket, 5);
        return true;
    }
    
    public function authenticate() {
        if (!$this->socket) {
            $this->debug("Няма socket връзка за автентификация");
            return false;
        }
        
        $this->debug("Изпращане на автентификация...");
        
        $packet = $this->createPacket(self::SERVERDATA_AUTH, $this->password);
        $written = fwrite($this->socket, $packet);
        
        if ($written === false) {
            $this->debug("Грешка при писане на автентификация");
            return false;
        }
        
        $this->debug("Автентификация изпратена ({$written} bytes)");
        
        $response = $this->readPacket();
        
        if (!$response) {
            $this->debug("Няма отговор за автентификация");
            return false;
        }
        
        $this->debug("Автентификация отговор: ID={$response['id']}, Type={$response['type']}");
        
        return $response['id'] != -1;
    }
    
    public function sendCommand($command) {
        if (!$this->socket) {
            $this->debug("Няма socket връзка за команда");
            return false;
        }
        
        $this->debug("Изпращане на команда: {$command}");
        
        $log_entry = date('Y-m-d H:i:s') . " - RCON Command: {$command} (Server: {$this->host}:{$this->port})\n";
        file_put_contents('rcon_commands.log', $log_entry, FILE_APPEND);
        
        $packet = $this->createPacket(self::SERVERDATA_EXECCOMMAND, $command);
        $written = fwrite($this->socket, $packet);
        
        if ($written === false) {
            $this->debug("Грешка при писане на команда");
            return false;
        }
        
        $this->debug("Команда изпратена ({$written} bytes)");
        
        $oldTimeout = stream_get_meta_data($this->socket)['timeout_sec'] ?? 5;
        stream_set_timeout($this->socket, 2);
        
        $response = $this->readPacket();
        stream_set_timeout($this->socket, $oldTimeout);
        
        if ($response) {
            $this->debug("Получен отговор: " . substr($response['body'], 0, 100));
        } else {
            $this->debug("Няма отговор (което може да е нормално за някои команди)");
        }
        
        return true;
    }
    
    private function createPacket($type, $body) {
        $id = $this->requestId++;
        $packet = pack('VV', $id, $type) . $body . "\x00\x00";
        return pack('V', strlen($packet)) . $packet;
    }
    
    private function readPacket() {
        if (!$this->socket) return false;
        
        $sizeData = @fread($this->socket, 4);
        if (strlen($sizeData) < 4) {
            $this->debug("Не може да прочете размер на пакет");
            return false;
        }
        
        $size = unpack('V', $sizeData)[1];
        $this->debug("Очакван размер на пакет: {$size}");
        
        if ($size > 4096 || $size < 10) {
            $this->debug("Невалиден размер на пакет: {$size}");
            return false;
        }
        
        $packet = @fread($this->socket, $size);
        
        if (strlen($packet) < $size) {
            $this->debug("Получен непълен пакет: " . strlen($packet) . "/{$size}");
            return false;
        }
        
        $data = unpack('Vid/Vtype', substr($packet, 0, 8));
        $body = substr($packet, 8, -2);
        
        return [
            'id' => $data['id'],
            'type' => $data['type'],
            'body' => $body
        ];
    }
    
    public function close() {
        if ($this->socket) {
            $this->debug("Затваряне на връзката");
            fclose($this->socket);
            $this->socket = null;
        }
    }
}

// Функция за изпращане на RCON команда
function sendRconCommand($command, $server_rcon_config = null) {
    // Проверка дали е подадена конфигурация
    if (!$server_rcon_config) {
        return ['success' => false, 'error' => 'RCON конфигурация не е подадена'];
    }
    
    $config = $server_rcon_config;
    
    $rcon = new RconClient($config['host'], $config['port'], $config['password']);
    
    if (!$rcon->connect()) {
        return ['success' => false, 'error' => 'Не може да се свърже със сървъра'];
    }
    
    if (!$rcon->authenticate()) {
        $rcon->close();
        return ['success' => false, 'error' => 'Грешна RCON парола или RCON не е активирано'];
    }
    
    $result = $rcon->sendCommand($command);
    $rcon->close();
    
    return ['success' => $result, 'error' => $result ? null : 'Грешка при изпращане на командата'];
}


?>