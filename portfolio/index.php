<?php
require __DIR__ . '/lib/functions.php';
$config = load_config();
$projects = load_projects();
$featured = get_featured_project($projects, $config);
$page_title = 'Начало — LionDevs';
include __DIR__ . '/partials/header.php';
?>
<section class="hero card">
  <div class="kicker">LionDevs</div>
  <h1>Брутални решения за вашите идеи</h1>
  <p><?php echo e($config['company']['tagline'] ?? 'Софтуер, дизайн, сървъри и плъгини — правим всичко.'); ?></p>
  <div class="hero-actions">
    <a class="btn primary" href="projects.php">Виж проектите</a>
    <a class="btn" href="mailto:<?php echo e($config['company']['email'] ?? 'contact@liondevs.dev'); ?>">Пиши ни</a>
  </div>
</section>

<?php if ($featured): ?>
  <div class="section-title"><span class="bar"></span>Избран проект</div>
  <section class="featured">
    <div class="media">
      <img src="<?php echo e($featured['image'] ?? ''); ?>" alt="<?php echo e($featured['image_alt'] ?? ($featured['title'] ?? 'Проект')); ?>">
    </div>
    <div class="content">
      <h2 class="title"><?php echo e($featured['title'] ?? 'Без заглавие'); ?></h2>
      <p class="desc"><?php echo e($featured['description'] ?? ''); ?></p>
      <?php if (!empty($featured['tags'])): ?>
        <div class="tags">
          <?php foreach ($featured['tags'] as $tag): ?>
            <span class="tag"><?php echo e($tag); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="hero-actions">
        <a class="btn" href="project.php?id=<?php echo e($featured['id']); ?>">Детайли</a>
        <?php if (!empty($featured['links']['demo'])): ?>
          <a class="btn" target="_blank" rel="noreferrer" href="<?php echo e($featured['links']['demo']); ?>">Демо ↗</a>
        <?php endif; ?>
        <?php if (!empty($featured['links']['repo'])): ?>
          <a class="btn" target="_blank" rel="noreferrer" href="<?php echo e($featured['links']['repo']); ?>">Код ↗</a>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php else: ?>
  <div class="card pad">Все още няма добавени проекти. Редактирай файла <code>config/projects.php</code>.</div>
<?php endif; ?>

<hr class="rule"/>
<div class="small">Настрой: <code>config/config.php</code> за акцентен цвят и избран проект (last или id).</div>
<?php include __DIR__ . '/partials/footer.php'; ?>