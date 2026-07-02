<?php
require __DIR__.'/auth.php';
require_admin();
require_csrf();
require_once __DIR__.'/../includes/functions.php';
$arts = read_json('articles', []);
$original = $_POST['original_slug'] ?? '';
$slug = trim($_POST['slug'] ?? '');
if ($slug === '') { exit('Slug obligatoire'); }

$item = [
  'slug' => $slug,
  'title' => trim($_POST['title'] ?? ''),
  'category' => trim($_POST['category'] ?? ''),
  'excerpt' => trim($_POST['excerpt'] ?? ''),
  'date' => trim($_POST['date'] ?? ''),
  'read_time' => trim($_POST['read_time'] ?? '7 min'),
  'type' => 'article',
  'priority' => (int)($_POST['priority'] ?? 1),
  'featured' => !empty($_POST['featured']),
  'status' => $_POST['status'] ?? 'published',
  'author' => 'Cédrick Benittah',
  'image' => trim($_POST['image'] ?? ('/assets/img/articles/'.$slug.'.svg')),
  'seo_title' => trim($_POST['seo_title'] ?? ''),
  'seo_description' => trim($_POST['seo_description'] ?? ''),
  'content' => trim($_POST['content'] ?? '')
];

$preserveKeys = ['pillar','geo_intent','keywords','takeaways','cta','updated'];
$found = false;
foreach ($arts as $i => $a) {
  if (($a['slug'] ?? '') === $original && $original !== '') {
    foreach ($preserveKeys as $key) {
      if (isset($a[$key])) { $item[$key] = $a[$key]; }
    }
    $arts[$i] = $item;
    $found = true;
    break;
  }
}
if (!$found) { $arts[] = $item; }
write_json('articles', $arts);
header('Location: /admin/articles.php?saved=1');
?>
