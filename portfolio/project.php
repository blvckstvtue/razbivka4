<?php
require __DIR__ . '/lib/functions.php';
$config = load_config();
$projects = load_projects();
$id = isset($_GET['id']) ? (string) $_GET['id'] : '';
$project = $id ? get_project_by_id($projects, $id) : null;
$page_title = $project ? ($project['title'] . ' — Проект — LionDevs') : 'Проект — LionDevs';
include __DIR__ . '/partials/header.php';
?>
<?php if (!$project): ?>
  <div class="card pad">Проектът не е намерен. <a class="btn" href="projects.php">← Към всички проекти</a></div>
<?php else: ?>
  <div class="section-title"><span class="bar"></span>Детайли за проект</div>
  <section class="card pad">
    <h1 style="margin-top:0"><?php echo e($project['title']); ?></h1>
    <div class="grid two" style="align-items:start">
      <div>
        <img class="project-image" src="<?php echo e($project['image'] ?? ''); ?>" alt="<?php echo e($project['image_alt'] ?? $project['title']); ?>">
      </div>
      <div>
        <p class="project-desc" style="font-size:16px"><?php echo e($project['description'] ?? ''); ?></p>
        <?php if (!empty($project['tags'])): ?>
          <div class="tags">
            <?php foreach ($project['tags'] as $tag): ?>
              <span class="tag"><?php echo e($tag); ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="hero-actions" style="margin-top:12px">
          <a class="btn" href="projects.php">← Всички проекти</a>
          <?php if (!empty($project['links']['demo'])): ?>
            <a class="btn" target="_blank" rel="noreferrer" href="<?php echo e($project['links']['demo']); ?>">Демо ↗</a>
          <?php endif; ?>
          <?php if (!empty($project['links']['repo'])): ?>
            <a class="btn" target="_blank" rel="noreferrer" href="<?php echo e($project['links']['repo']); ?>">Код ↗</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>