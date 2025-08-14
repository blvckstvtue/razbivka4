<?php
// index.php - Начална страница
session_start();
require_once 'config.php';
require_once 'database.php';

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_config['site_name'] ?></title>
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
            padding-top: 70px; /* Компенсация за fixed navbar */
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
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(15, 23, 42, 0.98);
            box-shadow: var(--shadow);
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

        .nav-links a:hover {
            color: var(--primary-color);
        }

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



        /* Hero Section */
        .hero {
            height: calc(100vh - 70px); /* Отчитане на navbar височината */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= $homepage_sections['hero']['background_image'] ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            margin-top: -70px; /* Негативен margin за компенсация на body padding */
            padding-top: 70px; /* Вътрешен padding за правилно центриране */
        }

        .hero-content {
            max-width: 800px;
            padding: 0 20px;
            animation: heroFadeIn 1s ease-out;
        }

        @keyframes heroFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 800;
            margin-bottom: 24px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            background: linear-gradient(135deg, #ffffff 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 40px;
            opacity: 0.9;
            font-weight: 400;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            text-decoration: none;
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }

        /* Server Stats */
        .server-stats {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 40px;
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Sections */
        .section {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 60px;
            color: var(--text-primary);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--surface);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            border: 1px solid var(--border);
            group: feature;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .feature-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            padding-top: 5px; /* леко отстояние */
            transition: transform 0.4s ease;
        }

        .feature-card:hover .feature-image {
            transform: scale(1.1);
        }

        .feature-content {
            padding: 30px;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 16px;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Server Info Section */
        .server-info {
            /*background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= $homepage_sections['server_info']['background_image'] ?>');*/
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .server-info-grid {
            display: flex;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .info-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.9);
        }

        .info-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 16px;
        }

        .info-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .info-label {
            color: var(--text-secondary);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* News Section */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .news-card {
            background: var(--surface);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            border: 1px solid var(--border);
        }

        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-content {
            padding: 24px;
        }

        .news-date {
            color: var(--primary-color);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .news-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .news-description {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Widgets Section */
        .widgets-section {
            background: var(--surface);
            padding: 80px 0;
        }

        .widgets-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .widget {
            background: var(--background);
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .widget:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .widget-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Top Donors Widget */
        .donor-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: var(--surface);
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .donor-item:hover {
            background: var(--surface-light);
            transform: translateX(5px);
        }

        .donor-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .donor-rank {
            background: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .donor-rank.first {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #1f2937;
        }

        .donor-rank.second {
            background: linear-gradient(135deg, #c0c0c0, #e5e5e5);
            color: #1f2937;
        }

        .donor-rank.third {
            background: linear-gradient(135deg, #cd7f32, #daa520);
            color: white;
        }

        .donor-name {
            font-weight: 500;
            color: var(--text-primary);
        }

        .donor-amount {
            font-weight: 600;
            color: var(--accent-color);
        }

        .donor-stats {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        /* Chat Widget */
        .chat-container {
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .chat-messages {
            flex: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 15px;
            overflow-y: auto;
            margin-bottom: 15px;
            max-height: 300px;
        }

        .chat-message {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(71, 85, 105, 0.3);
        }

        .chat-message:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .chat-username {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .chat-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-left: 8px;
        }

        .chat-text {
            color: var(--text-primary);
            margin-top: 4px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .chat-form {
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            padding: 10px 15px;
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .chat-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .chat-send {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .chat-send:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .chat-login-prompt {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .chat-login-prompt a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .chat-login-prompt a:hover {
            text-decoration: underline;
        }

        .no-donors {
            text-align: center;
            color: var(--text-secondary);
            padding: 40px 20px;
            font-style: italic;
        }

        /* Servers Widget */
        .server-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: var(--surface);
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .server-item:hover {
            background: var(--surface-light);
            transform: translateX(5px);
        }

        .server-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .server-status {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            position: relative;
            animation: pulse 2s infinite;
        }

        .server-status.online {
            background: var(--accent-color);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        .server-status.offline {
            background: var(--danger-color);
            animation: none;
        }

        .server-status.maintenance {
            background: var(--warning-color);
            animation: none;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .server-details h4 {
            color: var(--text-primary);
            font-weight: 500;
            margin: 0;
            font-size: 1rem;
        }

        .server-ip {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 2px;
            font-family: 'Courier New', monospace;
        }

        .server-status-text {
            font-weight: 500;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .server-status-text.online {
            color: var(--accent-color);
        }

        .server-status-text.offline {
            color: var(--danger-color);
        }

        .server-status-text.maintenance {
            color: var(--warning-color);
        }

        /* Stats Widgets */
        .stats-widgets-section {
            background: var(--background);
            padding: 60px 0;
        }

        .stats-widgets-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stats-widget {
            background: var(--surface);
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-widget:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .stats-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        .stats-widget-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .stats-widget-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 8px;
            display: block;
            text-shadow: 0 2px 4px rgba(99, 102, 241, 0.1);
        }

        .stats-widget-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-widgets-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .stats-widget-number {
                font-size: 2rem;
            }
        }

        /* Footer */
        .footer {
            background: var(--secondary-color);
            padding: 60px 0 30px;
            border-top: 1px solid var(--border);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-primary);
        }

        .footer-section p,
        .footer-section a {
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 8px;
            display: block;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--surface);
            border-radius: 10px;
            color: var(--text-secondary);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .server-stats {
                flex-direction: column;
                gap: 20px;
                position: static;
                transform: none;
                margin-top: 40px;
            }
            
            .features-grid,
            .news-grid {
                grid-template-columns: 1fr;
            }
            
            .widgets-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .chat-container {
                height: 350px;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.2rem;
            }
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: var(--text-secondary);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-crown"></i>
                <?= $site_config['server_name'] ?>
            </a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="shop.php">Store</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1><?= $homepage_sections['hero']['title'] ?></h1>
            <p><?= $homepage_sections['hero']['subtitle'] ?></p>
            <a href="<?= $homepage_sections['hero']['cta_link'] ?>" class="cta-button">
                <i class="fas fa-shopping-cart"></i>
                <?= $homepage_sections['hero']['cta_text'] ?>
            </a>
        </div>
        
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down fa-2x"></i>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title fade-in-up"><?= $homepage_sections['features']['title'] ?></h2>
            <div class="features-grid">
                <?php foreach($homepage_sections['features']['items'] as $feature): ?>
                    <div class="feature-card fade-in-up">
                        <img src="<?= $feature['image'] ?>" alt="<?= $feature['title'] ?>" class="feature-image">
                        <div class="feature-content">
                            <div class="feature-icon">
                                <i class="<?= $feature['icon'] ?>"></i>
                            </div>
                            <h3 class="feature-title"><?= $feature['title'] ?></h3>
                            <p class="feature-description"><?= $feature['description'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Server Info Section -->
    <section class="section server-info">
        <div class="container">
            <h2 class="section-title fade-in-up"><?= $homepage_sections['server_info']['title'] ?></h2>
            <div class="server-info-grid">
                <?php foreach($homepage_sections['server_info']['stats'] as $stat): ?>
                    <div class="info-card fade-in-up">
                        <div class="info-icon">
                            <i class="<?= $stat['icon'] ?>"></i>
                        </div>
                        <div class="info-value"><?= $stat['value'] ?></div>
                        <div class="info-label"><?= $stat['label'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title fade-in-up"><?= $homepage_sections['news']['title'] ?></h2>
            <div class="news-grid">
                <?php foreach($homepage_sections['news']['items'] as $news): ?>
                    <div class="news-card fade-in-up">
                        <img src="<?= $news['image'] ?>" alt="<?= $news['title'] ?>" class="news-image">
                        <div class="news-content">
                            <div class="news-date">
                                <i class="far fa-calendar"></i>
                                <?= date('d.m.Y', strtotime($news['date'])) ?>
                            </div>
                            <h3 class="news-title"><?= $news['title'] ?></h3>
                            <p class="news-description"><?= $news['description'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Widgets Section -->
    <?php if ($widget_config['top_donors']['enabled'] || $widget_config['chat']['enabled'] || $widget_config['servers']['enabled']): ?>
    <section class="widgets-section">
        <div class="widgets-container">
            
            <!-- Top Donors Widget -->
            <?php if ($widget_config['top_donors']['enabled']): ?>
                <div class="widget fade-in-up">
                    <h3 class="widget-title">
                        <i class="fas fa-trophy"></i>
                        <?= $widget_config['top_donors']['title'] ?>
                    </h3>
                    
                    <?php 
                    $top_donors = getTopDonors($widget_config['top_donors']['show_count']);
                    if (!empty($top_donors)): 
                    ?>
                        <?php foreach ($top_donors as $index => $donor): ?>
                            <div class="donor-item">
                                <div class="donor-info">
                                    <div class="donor-rank <?= $index === 0 ? 'first' : ($index === 1 ? 'second' : ($index === 2 ? 'third' : '')) ?>">
                                        <?= $index + 1 ?>
                                    </div>
                                    <div>
                                        <div class="donor-name"><?= htmlspecialchars($donor['username']) ?></div>
                                        <div class="donor-stats"><?= $donor['purchase_count'] ?> purchases</div>
                                    </div>
                                </div>
                                <?php if ($widget_config['top_donors']['show_amounts']): ?>
                                    <div class="donor-amount"><?= number_format($donor['total_donated'], 2) ?> eur.</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-donors">
                            <i class="fas fa-info-circle"></i>
                            No donators yet, be first!
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Chat Widget -->
            <?php if ($widget_config['chat']['enabled']): ?>
                <div class="widget fade-in-up">
                    <h3 class="widget-title">
                        <i class="fas fa-comments"></i>
                        <?= $widget_config['chat']['title'] ?>
                    </h3>
                    
                    <div class="chat-container">
                        <div class="chat-messages" id="chatMessages">
                            <!-- Messages will be loaded here -->
                        </div>
                        
                        <?php if ($user): ?>
                            <form class="chat-form" id="chatForm">
                                <input 
                                    type="text" 
                                    class="chat-input" 
                                    id="chatInput" 
                                    placeholder="Write message..."
                                    maxlength="<?= $widget_config['chat']['max_message_length'] ?>"
                                    required
                                >
                                <button type="submit" class="chat-send">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="chat-login-prompt">
                                <i class="fas fa-lock"></i>
                                <p>You need <a href="login.php">account</a> to participate in chat.</p>
                                <p><a href="register.php">Register</a> now.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Servers Widget -->
            <?php if ($widget_config['servers']['enabled']): ?>
                <div class="widget fade-in-up">
                    <h3 class="widget-title">
                        <i class="fas fa-server"></i>
                        <?= $widget_config['servers']['title'] ?>
                    </h3>
                    
                    <?php if (!empty($widget_config['servers']['servers'])): ?>
                        <?php foreach ($widget_config['servers']['servers'] as $server): ?>
                            <div class="server-item">
                                <div class="server-info">
                                    <div class="server-status <?= $server['status'] ?>"></div>
                                    <div class="server-details">
                                        <h4><?= htmlspecialchars($server['name']) ?></h4>
                                        <div class="server-ip"><?= htmlspecialchars($server['ip']) ?></div>
                                    </div>
                                </div>
                                <div class="server-status-text <?= $server['status'] ?>">
                                    <?php
                                    switch ($server['status']) {
                                        case 'online':
                                            echo 'Online';
                                            break;
                                        case 'offline':
                                            echo 'Offline';
                                            break;
                                        case 'maintenance':
                                            echo 'Maintenance';
                                            break;
                                        default:
                                            echo 'Unknown';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-donors">
                            <i class="fas fa-info-circle"></i>
                            There no online servers.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats Widgets Section -->
    <?php if ($widget_config['stats']['enabled'] && 
             ($widget_config['stats']['registered_users']['enabled'] || 
              $widget_config['stats']['total_banned']['enabled'] || 
              $widget_config['stats']['global_top_count']['enabled'])): ?>
    <section class="stats-widgets-section">
        <div class="stats-widgets-container">
            
            <!-- Registered Users Widget -->
            <?php if ($widget_config['stats']['registered_users']['enabled']): ?>
                <div class="stats-widget fade-in-up">
                    <div class="stats-widget-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="stats-widget-number">
                        <?php
                        if ($widget_config['stats']['registered_users']['show_live_count']) {
                            echo number_format(getRegisteredUsersCount());
                        } else {
                            echo number_format($widget_config['stats']['registered_users']['count']);
                        }
                        ?>
                    </span>
                    <div class="stats-widget-title"><?= htmlspecialchars($widget_config['stats']['registered_users']['title']) ?></div>
                </div>
            <?php endif; ?>
            
            <!-- Total Banned Widget -->
            <?php if ($widget_config['stats']['total_banned']['enabled']): ?>
                <div class="stats-widget fade-in-up">
                    <div class="stats-widget-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <span class="stats-widget-number"><?= number_format($widget_config['stats']['total_banned']['count']) ?></span>
                    <div class="stats-widget-title"><?= htmlspecialchars($widget_config['stats']['total_banned']['title']) ?></div>
                </div>
            <?php endif; ?>
            
            <!-- Global Top Count Widget -->
            <?php if ($widget_config['stats']['global_top_count']['enabled']): ?>
                <div class="stats-widget fade-in-up">
                    <div class="stats-widget-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <span class="stats-widget-number"><?= number_format($widget_config['stats']['global_top_count']['count']) ?></span>
                    <div class="stats-widget-title"><?= htmlspecialchars($widget_config['stats']['global_top_count']['title']) ?></div>
                </div>
            <?php endif; ?>
            
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Server Information</h3>
                    <p><i class="fas fa-server"></i> IP: <?= $site_config['server_ip'] ?></p>
                    <p><i class="fas fa-envelope"></i> <?= $site_config['admin_contact'] ?></p>
                </div>
                <div class="footer-section">
                    <h3>Navigatiton</h3>
                    <a href="shop.php">Store</a>
                    <a href="<?= $site_config['discord_url'] ?>" target="_blank">Discord Server</a>
                    <a href="<?= $site_config['steam_group'] ?>" target="_blank">Steam Group</a>
                </div>
                <div class="footer-section">
                    <h3>Social Accounts</h3>
                    <div class="social-links">
                        <a href="<?= $site_config['discord_url'] ?>" class="social-link" target="_blank">
                            <i class="fab fa-discord"></i>
                        </a>
                        <a href="<?= $site_config['steam_group'] ?>" class="social-link" target="_blank">
                            <i class="fab fa-steam"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 <?= $site_config['site_name'] ?>. all rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in-up');
            fadeElements.forEach(el => observer.observe(el));
        });

        // Smooth scrolling for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
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

        // Chat functionality
        <?php if ($widget_config['chat']['enabled'] && $user): ?>
        let chatRefreshInterval;
        
        function loadChatMessages() {
            fetch('chat_api.php?action=get_messages')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const chatMessages = document.getElementById('chatMessages');
                        if (chatMessages) {
                            chatMessages.innerHTML = '';
                            data.messages.forEach(message => {
                                const messageDiv = document.createElement('div');
                                messageDiv.className = 'chat-message';
                                
                                const date = new Date(message.created_at);
                                const timeString = date.toLocaleTimeString('bg-BG', {hour: '2-digit', minute: '2-digit'});
                                
                                messageDiv.innerHTML = `
                                    <div>
                                        <span class="chat-username">${escapeHtml(message.username)}</span>
                                        <span class="chat-time">${timeString}</span>
                                    </div>
                                    <div class="chat-text">${escapeHtml(message.message)}</div>
                                `;
                                chatMessages.appendChild(messageDiv);
                            });
                            
                            // Scroll to bottom
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    }
                })
                .catch(error => console.error('Chat load error:', error));
        }
        
        function sendChatMessage(message) {
            const formData = new FormData();
            formData.append('action', 'send_message');
            formData.append('message', message);
            
            fetch('chat_api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('chatInput').value = '';
                    loadChatMessages(); // Reload messages
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Chat send error:', error);
                alert('Грешка при изпращане на съобщението');
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Initialize chat
        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('chatForm');
            if (chatForm) {
                // Load initial messages
                loadChatMessages();
                
                // Set up auto-refresh
                chatRefreshInterval = setInterval(loadChatMessages, <?= $widget_config['chat']['refresh_interval'] ?>);
                
                // Handle form submission
                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const input = document.getElementById('chatInput');
                    const message = input.value.trim();
                    
                    if (message) {
                        sendChatMessage(message);
                    }
                });
                
                // Handle Enter key
                document.getElementById('chatInput').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        chatForm.dispatchEvent(new Event('submit'));
                    }
                });
            }
        });
        
        // Clean up interval when page unloads
        window.addEventListener('beforeunload', function() {
            if (chatRefreshInterval) {
                clearInterval(chatRefreshInterval);
            }
        });
        <?php endif; ?>

        // Премахнат parallax ефект за предотвратяване на застъпване на елементите
    </script>
</body>
</html>