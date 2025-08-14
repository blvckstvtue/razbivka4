<?php
$pageTitle = isset($page_title) && $page_title ? $page_title : 'LionDevs — Portfolio';
$accent = isset($config['ui']['accent']) ? $config['ui']['accent'] : '#ffbf00';
$company = isset($config['company']) ? $config['company'] : ['name' => 'LionDevs'];
?>
<!DOCTYPE html>
<html lang="bg">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="<?php echo htmlspecialchars($accent, ENT_QUOTES); ?>">
  <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($company['tagline'] ?? 'LionDevs portfolio', ENT_QUOTES); ?>">
  <style>:root{--accent: <?php echo htmlspecialchars($accent, ENT_QUOTES); ?>;}</style>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E%F0%9F%90%AF%3C/text%3E%3C/svg%3E">
</head>
<body>
  <header class="site-header">
    <div class="site-header-inner">
      <a href="index.php" class="logo"><?php echo htmlspecialchars($company['name'] ?? 'LionDevs', ENT_QUOTES); ?></a>
      <nav class="nav">
        <a href="index.php">Начало</a>
        <a href="projects.php">Проекти</a>
        <a href="mailto:<?php echo htmlspecialchars($company['email'] ?? 'contact@liondevs.dev', ENT_QUOTES); ?>">Контакт</a>
      </nav>
    </div>
  </header>
  <main class="container">