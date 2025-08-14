# LionDevs Portfolio (PHP)

- Pages: `index.php` (home with featured project), `projects.php` (all projects), `project.php` (details)
- Config: `config/config.php` (company, UI, featured), `config/projects.php` (projects list)
- Styling: `assets/css/style.css`

## Configure

1. Edit `config/config.php`:
   - `company` info (name, email, links)
   - `ui.accent` color (hex)
   - `featured`: set `mode` to `last` or `id`. If `id`, set `id` to the project id you want featured on the homepage.
2. Edit `config/projects.php` and add/remove items. Each item:

```php
[
  'id' => 'unique-id',
  'title' => 'Име',
  'description' => 'Кратко описание',
  'image' => 'assets/images/your-image.jpg', // or full URL
  'image_alt' => 'Алтернативен текст',
  'tags' => ['PHP','Game','Design'],
  'links' => [ 'demo' => '...', 'repo' => '...' ]
]
```

## Run

- Serve with PHP built-in server from the `portfolio` directory:

```bash
php -S localhost:8000
```

Open `http://localhost:8000`.