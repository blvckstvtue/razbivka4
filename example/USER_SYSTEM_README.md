# User Registration System

## Overview
The VIP Store now includes a complete user registration and authentication system that allows users to:
- Register with username, email, and password
- Login to their accounts
- Manage their Steam ID in settings
- Automatically use saved Steam ID for purchases

## Features

### 1. User Registration (`register.php`)
- Username (3-50 characters)
- Email address (validated)
- Password (minimum 6 characters)
- Password confirmation
- Form validation and error handling

### 2. User Login (`login.php`)
- Login with username or email
- Password authentication
- Session management
- Redirect to previous page after login

### 3. User Settings (`settings.php`)
- View account information
- Add/update Steam ID
- Steam ID validation
- Account management options
- Logout functionality

### 4. Enhanced Shop Experience
- Automatic Steam ID population for logged-in users
- Optional Steam ID entry for users without saved Steam ID
- Warning for logged-in users without Steam ID
- Enhanced purchase logging with username tracking

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    steam_id VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);
```

### Purchases Table
```sql
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    steam_id VARCHAR(50) NOT NULL,
    item_key VARCHAR(50) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    status ENUM('completed', 'failed', 'pending') DEFAULT 'completed'
);
```

## Setup Instructions

1. **Database Configuration**: Update `config.php` with your MySQL credentials
2. **Initialize Database**: Run `setup_database.php` to create tables
3. **Test Registration**: Visit `register.php` to create a test account
4. **Test Login**: Visit `login.php` to authenticate
5. **Configure Steam ID**: Go to `settings.php` to add your Steam ID
6. **Test Purchase Flow**: Visit `shop.php` to test the enhanced purchase experience

## Navigation Updates

The navigation now dynamically shows:
- **For guests**: Login and Register links
- **For logged-in users**: Username dropdown with Settings and Logout options

## Security Features

- Password hashing using PHP's `password_hash()`
- CSRF protection with session tokens
- Input validation and sanitization
- SQL injection prevention with prepared statements
- Session management with proper cleanup

## User Experience Improvements

1. **Seamless Shopping**: Users with saved Steam ID don't need to enter it repeatedly
2. **Flexible Options**: Users can still shop without registration (manual Steam ID entry)
3. **Clear Guidance**: Helpful messages guide users to add Steam ID for better experience
4. **Account Management**: Easy-to-use settings page for profile management

## New Widgets (Added)

### 5. Top Donors Widget
- Shows top donors based on total purchase amounts
- Configurable display count and amount visibility
- Beautiful ranking system with gold/silver/bronze styling
- Can be enabled/disabled in config.php

### 6. Community Chat Widget
- Real-time chat for registered users
- AJAX-based messaging system
- Auto-refresh every 3 seconds
- Message length validation
- Login prompt for guests
- Can be enabled/disabled in config.php

## Widget Configuration

In `config.php`, you can control the widgets:

```php
$widget_config = [
    'top_donors' => [
        'enabled' => true,        // Enable/disable widget
        'title' => 'Топ Донатори',
        'show_count' => 5,        // Number of donors to show
        'show_amounts' => true    // Show donation amounts
    ],
    'chat' => [
        'enabled' => true,        // Enable/disable widget
        'title' => 'Чат на общността',
        'max_messages' => 50,     // Max messages to display
        'refresh_interval' => 3000, // Refresh interval in ms
        'max_message_length' => 500 // Max message length
    ]
];
```

## Additional Database Tables

### Chat Messages Table
```sql
CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## File Structure

- `database.php` - Database connection and user management functions
- `register.php` - User registration page
- `login.php` - User authentication page  
- `settings.php` - User account settings and Steam ID management
- `setup_database.php` - Database initialization script
- `chat_api.php` - AJAX API for chat functionality
- Updated `index.php` and `shop.php` with user system integration