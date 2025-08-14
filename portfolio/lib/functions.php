<?php
function load_config(): array {
  $path = __DIR__ . '/../config/config.php';
  $config = file_exists($path) ? include $path : [];
  if (!is_array($config)) { $config = []; }
  return $config;
}

function load_projects(): array {
  $path = __DIR__ . '/../config/projects.php';
  $projects = file_exists($path) ? include $path : [];
  if (!is_array($projects)) { $projects = []; }
  // Ensure sequential array
  $projects = array_values($projects);
  return $projects;
}

function get_project_by_id(array $projects, string $id): ?array {
  foreach ($projects as $project) {
    if (!empty($project['id']) && $project['id'] === $id) {
      return $project;
    }
  }
  return null;
}

function get_featured_project(array $projects, array $config): ?array {
  if (empty($projects)) { return null; }
  $mode = $config['featured']['mode'] ?? 'last';
  if ($mode === 'id') {
    $desiredId = $config['featured']['id'] ?? null;
    if ($desiredId) {
      $match = get_project_by_id($projects, $desiredId);
      if ($match) { return $match; }
    }
  }
  // Default: last project in config order
  return $projects[count($projects) - 1];
}

function e(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }