<?php
// admin_keys.php - Админ страница за управление на ключове
session_start();
require_once 'config.php';
require_once 'database.php';
require_once 'keys_management.php';

$user = getCurrentUser();

// Проста проверка за админ (можеш да я промениш според нуждите)
// Примерно проверка по имейл или специално поле в БД
$is_admin = $user && in_array($user['email'], ['liondevelopments1337@gmail.com']);

if (!$is_admin) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

// Обработка на AJAX заявки за добавяне на ключове
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'add_keys') {
        $key_type = $_POST['key_type'] ?? '';
        $server_id = $_POST['server_id'] ?? '';
        $item_key = $_POST['item_key'] ?? '';
        $count = (int)($_POST['count'] ?? 1);
        
        if (empty($key_type) || empty($server_id) || empty($item_key) || $count <= 0) {
            echo json_encode(['success' => false, 'message' => 'All fields are mandatory!']);
            exit;
        }
        
        if ($count > 100) {
            echo json_encode(['success' => false, 'message' => 'A maximum of 100 keys at a time!']);
            exit;
        }
        
        try {
            $added_keys = addKeys($key_type, $server_id, $item_key, $count);
            echo json_encode([
                'success' => true, 
                'message' => "Successfully added {$count} keys!",
                'keys' => $added_keys
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    if ($_POST['action'] === 'get_statistics') {
        $server_id = $_POST['server_id'] ?? null;
        $item_key = $_POST['item_key'] ?? null;
        
        $stats = getKeyStatistics($server_id, $item_key);
        echo json_encode(['success' => true, 'data' => $stats]);
        exit;
    }
}

// Получаване на статистики за показване
$all_stats = getKeyStatistics();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление на ключове - <?= $site_config['site_name'] ?></title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .nav-links {
            text-align: center;
            margin-bottom: 30px;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: var(--surface);
            color: var(--primary-color);
        }

        .section {
            background: var(--surface);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }

        .section h2 {
            color: var(--accent-color);
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-input, .form-select {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--background);
            color: var(--text-primary);
            font-size: 1rem;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: var(--surface-light);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid var(--border);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .stat-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-vip {
            background: rgba(99, 102, 241, 0.2);
            color: var(--primary-color);
        }

        .badge-trial {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .badge-shop {
            background: rgba(16, 185, 129, 0.2);
            color: var(--accent-color);
        }

        .stat-numbers {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .stat-number {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .available {
            color: var(--accent-color);
        }

        .used {
            color: var(--warning-color);
        }

        .total {
            color: var(--primary-color);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }

        .grid-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 10px;
            }

            .grid-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-key"></i> Key Management</h1>
            <p>Adding and managing store keys</p>
        </div>

        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="shop.php"><i class="fas fa-shopping-cart"></i> Store</a>
            <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        </div>

        <!-- Добавяне на ключове -->
        <div class="section">
            <h2><i class="fas fa-plus"></i> Adding keys</h2>
            
            <div id="alertContainer"></div>
            
            <form id="addKeysForm" class="grid-form">
                <div class="form-group">
                    <label class="form-label">Key type:</label>
                    <select name="key_type" class="form-select" required>
                        <option value="">Choose type...</option>
                        <option value="ingame_vipkey">VIP Key</option>
                        <option value="ingame_trialvipkey">Trial VIP Key</option>
                        <option value="ingame_shopkey1">Shop Key 1</option>
                        <option value="ingame_shopkey2">Shop Key 2</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Server:</label>
                    <select name="server_id" class="form-select" required onchange="updateItemOptions()">
                        <option value="">Choose server...</option>
                        <?php foreach ($servers_config as $server_id => $server): ?>
                            <?php if ($server['shop_enabled']): ?>
                                <option value="<?= $server_id ?>"><?= htmlspecialchars($server['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Item:</label>
                    <select name="item_key" class="form-select" required>
                        <option value="">First choose server...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Keys amount:</label>
                    <input type="number" name="count" class="form-input" min="1" max="100" value="1" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add keys
                    </button>
                </div>
            </form>
        </div>

        <!-- Статистики -->
        <div class="section">
            <h2><i class="fas fa-chart-bar"></i> Statistics of the keys</h2>
            
            <div class="stats-grid">
                <?php foreach ($all_stats as $stat): ?>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">
                                <?= htmlspecialchars($servers_config[$stat['server_id']]['name']) ?><br>
                                <small><?= htmlspecialchars($servers_config[$stat['server_id']]['vip_items'][$stat['item_key']]['name']) ?></small>
                            </div>
                            <div class="stat-badge badge-<?= 
                                strpos($stat['key_type'], 'vip') !== false ? 'vip' : 
                                (strpos($stat['key_type'], 'trial') !== false ? 'trial' : 'shop') 
                            ?>">
                                <?= str_replace(['ingame_', 'key'], ['', ''], $stat['key_type']) ?>
                            </div>
                        </div>
                        
                        <div class="stat-numbers">
                            <div class="stat-number">
                                <div class="stat-value available"><?= $stat['available_keys'] ?></div>
                                <div class="stat-label">Available</div>
                            </div>
                            <div class="stat-number">
                                <div class="stat-value used"><?= $stat['used_keys'] ?></div>
                                <div class="stat-label">Used</div>
                            </div>
                            <div class="stat-number">
                                <div class="stat-value total"><?= $stat['total_keys'] ?></div>
                                <div class="stat-label">Total</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($all_stats)): ?>
                    <div class="stat-card" style="grid-column: 1 / -1; text-align: center; color: var(--text-secondary);">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <p>No keys have been added yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const serversConfig = <?= json_encode($servers_config) ?>;

        function updateItemOptions() {
            const serverSelect = document.querySelector('select[name="server_id"]');
            const itemSelect = document.querySelector('select[name="item_key"]');
            const selectedServer = serverSelect.value;

            itemSelect.innerHTML = '<option value="">Choose an item...</option>';

            if (selectedServer && serversConfig[selectedServer]) {
                const items = serversConfig[selectedServer].vip_items;
                for (const [itemKey, item] of Object.entries(items)) {
                    const option = document.createElement('option');
                    option.value = itemKey;
                    option.textContent = item.name;
                    itemSelect.appendChild(option);
                }
            }
        }

        document.getElementById('addKeysForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.set('action', 'add_keys');

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            submitBtn.disabled = true;

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alertContainer = document.getElementById('alertContainer');
                
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> ${data.message}
                        </div>
                    `;
                    
                    // Изчистваме формата
                    this.reset();
                    updateItemOptions();
                    
                    // Обновяваме страницата след 2 секунди за да покажем новите статистики
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const alertContainer = document.getElementById('alertContainer');
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i> Error connecting to the server
                    </div>
                `;
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>