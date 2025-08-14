# Система за ключове в магазина

Тази система позволява да се използват ръчно добавени ключове за предметите в магазина, които клиентите получават след покупка вместо директна активация чрез RCON.

## Как работи

1. **Конфигурация на предметите** - В `config.php` можете да добавите специални опции към предметите
2. **Ръчно добавяне на ключове** - Вие добавяте ключове директно в базата данни чрез phpMyAdmin
3. **Покупка** - При покупка на предмет с ключ, системата автоматично взема наличен ключ и го показва на клиента
4. **Активация в играта** - Клиентът копира ключа и го активира в играта с подходящата команда

## Типове ключове

Системата поддържа 4 типа ключове:

- `ingame_vipkey` - VIP ключ (активира се с `!vipkey КЛЮЧ`)
- `ingame_trialvipkey` - Trial VIP ключ (активира се с `!trialvip КЛЮЧ`)
- `ingame_shopkey1` - Shop ключ 1 (активира се с `!shopkey1 КЛЮЧ`)
- `ingame_shopkey2` - Shop ключ 2 (активира се с `!shopkey2 КЛЮЧ`)

## Настройка

### 1. Конфигурация на предметите

В `config.php`, в секцията `vip_items` за всеки сървър, можете да добавите опции за ключове към предметите:

```php
'credits2' => [
    'name' => 'Credits 2',
    'price' => 13.00,
    'description' => 'Get 500000 credits in shop',
    'image' => 'images/package4.png',
    'features' => ['Ingame credits'],
    'rcon_command' => 'sm_givecredits "{steamid}" 500000',
    'ingame_shopkey1' => true  // <- Добавете тази линия
]
```

**Важно:** Ако предмет има активирана някоя от опциите за ключове, RCON командата няма да се изпълни. Вместо това клиентът ще получи ключ за активация в играта.

### 2. База данни

Системата автоматично създава таблица `store_keys` при първо зареждане на `keys_management.php`.

## Добавяне на ключове

За да добавите ключове в системата, използвайте phpMyAdmin и добавете записи в таблицата `store_keys`:

### Структура на таблицата:

```sql
INSERT INTO store_keys (key_code, key_type, server_id, item_key) VALUES 
('TSndH9xrvJl2xT3Q5W5U659bFK9FE1kE', 'ingame_shopkey1', 'main_server', 'credits2'),
('ABC123XYZ789DEF456GHI', 'ingame_vipkey', 'main_server', 'vip'),
('TRIAL123VIP456KEY789', 'ingame_trialvipkey', 'zombie_escape', 'vip');
```

### Параметри:
- `key_code` - Самият ключ (генерирайте уникални стрингове)
- `key_type` - Тип ключ (ingame_vipkey, ingame_trialvipkey, ingame_shopkey1, ingame_shopkey2)
- `server_id` - ID на сървъра (main_server, zombie_escape, test_server)
- `item_key` - Ключ на предмета (vip, vip2, credits1, credits2)

### Пример за добавяне на ключове:

```sql
-- VIP ключове за главния сървър
INSERT INTO store_keys (key_code, key_type, server_id, item_key) VALUES 
('VIP123ABC456DEF789', 'ingame_vipkey', 'main_server', 'vip'),
('VIP789XYZ123ABC456', 'ingame_vipkey', 'main_server', 'vip2');

-- Shop ключове за credits
INSERT INTO store_keys (key_code, key_type, server_id, item_key) VALUES 
('SHOP1_KEY_123456789', 'ingame_shopkey1', 'main_server', 'credits2'),
('SHOP2_KEY_987654321', 'ingame_shopkey2', 'main_server', 'credits1');

-- Trial VIP ключове за zombie escape сървъра
INSERT INTO store_keys (key_code, key_type, server_id, item_key) VALUES 
('TRIAL_VIP_ZE_123', 'ingame_trialvipkey', 'zombie_escape', 'vip');
```

## Използване

### За клиенти

1. **Покупка:**
   - Изберете предмет с ключ от магазина
   - Завършете плащането
   - След успешно плащане ще се появи специален модал с ключа

2. **Активация:**
   - Копирайте ключа от модала
   - Влезте в играта
   - Използвайте подходящата команда (показана в модала)

## Файлове

### Нови файлове:
- `keys_management.php` - Основни функции за работа с ключове

### Модифицирани файлове:
- `config.php` - Добавени примерни ключове към предметите
- `shop.php` - Модифицирана логика за покупка и показване на ключ модал

## База данни

### Таблица `store_keys`

```sql
CREATE TABLE store_keys (
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
);
```

## Логиране

Всички покупки с ключове се логират в:
- `purchases.log` - с информация за ключа
- Таблица `purchases` в базата данни

Формат на лога:
```
2025-01-14 10:30:15 - STEAM_0:1:12345 (user: username) purchase credits2 for 13.00 eur in server Competitive CSGO Mod [KEY: ABC123XYZ789]
```

## Поддръжка

### Често срещани проблеми:

1. **"Няма налични ключове за този предмет"**
   - Добавете ключове в базата данни чрез phpMyAdmin
   - Проверете дали предметът е конфигуриран правилно
   - Уверете се че има неизползвани ключове (is_used = FALSE)

2. **Ключ модалът не се показва**
   - Проверете console-а за JavaScript грешки
   - Уверете се че предметът има активирана опция за ключ

3. **Ключ се използва повторно**
   - Системата автоматично отбелязва използваните ключове
   - Проверете таблицата за грешки в базата данни

### Мониториране на ключовете:

За да проверите състоянието на ключовете:

```sql
-- Преглед на всички ключове
SELECT * FROM store_keys ORDER BY created_at DESC;

-- Преглед само на неизползваните ключове
SELECT * FROM store_keys WHERE is_used = FALSE;

-- Статистика по тип ключ
SELECT 
    key_type, 
    server_id, 
    item_key,
    COUNT(*) as total_keys,
    SUM(CASE WHEN is_used = FALSE THEN 1 ELSE 0 END) as available_keys,
    SUM(CASE WHEN is_used = TRUE THEN 1 ELSE 0 END) as used_keys
FROM store_keys 
GROUP BY key_type, server_id, item_key;
```

## Безопасност

- Ключовете трябва да бъдат уникални (препоръчително поне 20+ символа)
- Всеки ключ може да се използва само веднъж
- Системата проследява кой е използвал всеки ключ
- Няма уеб интерфейс за добавяне на ключове (само чрез база данни)