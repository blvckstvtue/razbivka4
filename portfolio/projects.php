<?php
require __DIR__ . '/lib/functions.php';
$config = load_config();
$projects = load_projects();
$page_title = 'Проекти — LionDevs';
include __DIR__ . '/partials/header.php';
?>
<div class="section-title"><span class="bar"></span>Проекти</div>
<?php if (empty($projects)): ?>
  <div class="card pad">Няма проекти. Добави в <code>config/projects.php</code>.</div>
<?php else: ?>
  <div class="grid three">
    <?php foreach ($projects as $p): ?>
      <a class="project-card card" href="project.php?id=<?php echo e($p['id']); ?>">
        <img class="project-image" src="<?php echo e($p['image'] ?? ''); ?>" alt="<?php echo e($p['image_alt'] ?? ($p['title'] ?? 'Проект')); ?>">
        <div class="project-body">
          <h3 class="project-title"><?php echo e($p['title'] ?? 'Без заглавие'); ?></h3>
          <p class="project-desc"><?php echo e($p['description'] ?? ''); ?></p>
          <?php if (!empty($p['tags'])): ?>
            <div class="tags">
              <?php foreach ($p['tags'] as $tag): ?>
                <span class="tag"><?php echo e($tag); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>