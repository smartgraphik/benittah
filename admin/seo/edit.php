<?php
require __DIR__.'/../auth.php';
require_admin();

$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
$page = array(
  'id'=>0,
  'page_path'=>'/',
  'page_label'=>'',
  'meta_title'=>'',
  'meta_description'=>'',
  'canonical_url'=>'',
  'robots'=>'index, follow',
  'og_title'=>'',
  'og_description'=>'',
  'og_image'=>'',
  'sitemap_include'=>1,
  'sitemap_priority'=>'0.8',
  'sitemap_changefreq'=>'monthly',
);
$error = '';

if ($id) {
  try {
    $found = db_fetch_one('SELECT * FROM seo_pages WHERE id = :id LIMIT 1', array(':id'=>$id));
    if ($found) { $page = array_merge($page, $found); }
  } catch (Throwable $e) {
    $error = 'Entrée SEO introuvable ou base indisponible.';
    app_log('SEO edit load failed: ' . $e->getMessage());
  }
}

if (request_method_is_post()) {
  require_csrf();
  $path = '/' . trim(clean_text($_POST['page_path'] ?? '/', 255), '/') . '/';
  if ($path === '//') { $path = '/'; }
  $robots = ($_POST['robots'] ?? 'index, follow') === 'noindex, follow' ? 'noindex, follow' : 'index, follow';
  $changefreq = clean_text($_POST['sitemap_changefreq'] ?? 'monthly', 50);
  $allowedFreq = array('always','hourly','daily','weekly','monthly','yearly','never');
  if (!in_array($changefreq, $allowedFreq, true)) { $changefreq = 'monthly'; }
  $priority = (float)($_POST['sitemap_priority'] ?? 0.8);
  if ($priority < 0.1) { $priority = 0.1; }
  if ($priority > 1.0) { $priority = 1.0; }
  $payload = array(
    ':page_path'=>$path,
    ':page_label'=>clean_text($_POST['page_label'] ?? '', 255),
    ':meta_title'=>clean_text($_POST['meta_title'] ?? '', 255),
    ':meta_description'=>clean_text($_POST['meta_description'] ?? '', 320),
    ':canonical_url'=>clean_text($_POST['canonical_url'] ?? '', 255),
    ':robots'=>$robots,
    ':og_title'=>clean_text($_POST['og_title'] ?? '', 255),
    ':og_description'=>clean_text($_POST['og_description'] ?? '', 320),
    ':og_image'=>clean_text($_POST['og_image'] ?? '', 255),
    ':sitemap_include'=>!empty($_POST['sitemap_include']) ? 1 : 0,
    ':sitemap_priority'=>number_format($priority, 1, '.', ''),
    ':sitemap_changefreq'=>$changefreq,
  );
  if ($path === '') {
    $error = 'Le chemin de page est obligatoire.';
  } else {
    try {
      if ($id) {
        $payload[':id'] = $id;
        db_execute('UPDATE seo_pages SET page_path=:page_path, page_label=:page_label, meta_title=:meta_title, meta_description=:meta_description, canonical_url=:canonical_url, robots=:robots, og_title=:og_title, og_description=:og_description, og_image=:og_image, sitemap_include=:sitemap_include, sitemap_priority=:sitemap_priority, sitemap_changefreq=:sitemap_changefreq, updated_at=NOW() WHERE id=:id', $payload);
      } else {
        db_execute('INSERT INTO seo_pages (page_path, page_label, meta_title, meta_description, canonical_url, robots, og_title, og_description, og_image, sitemap_include, sitemap_priority, sitemap_changefreq) VALUES (:page_path, :page_label, :meta_title, :meta_description, :canonical_url, :robots, :og_title, :og_description, :og_image, :sitemap_include, :sitemap_priority, :sitemap_changefreq)', $payload);
      }
      header('Location: /admin/seo/?saved=1');
      exit;
    } catch (Throwable $e) {
      app_log('SEO save failed: ' . $e->getMessage());
      $error = 'Impossible d’enregistrer cette entrée SEO. Vérifiez que le chemin est unique.';
    }
  }
  $page = array_merge($page, array(
    'page_path'=>$path,
    'page_label'=>$payload[':page_label'],
    'meta_title'=>$payload[':meta_title'],
    'meta_description'=>$payload[':meta_description'],
    'canonical_url'=>$payload[':canonical_url'],
    'robots'=>$payload[':robots'],
    'og_title'=>$payload[':og_title'],
    'og_description'=>$payload[':og_description'],
    'og_image'=>$payload[':og_image'],
    'sitemap_include'=>$payload[':sitemap_include'],
    'sitemap_priority'=>$payload[':sitemap_priority'],
    'sitemap_changefreq'=>$payload[':sitemap_changefreq'],
  ));
}

require __DIR__.'/../_layout.php';
admin_header(($id ? 'Modifier' : 'Ajouter') . ' une page SEO','seo');
?>
<div class="admin-top">
  <div><h1><?= $id ? 'Modifier' : 'Ajouter' ?> une page SEO</h1><p>Ces metas sont utilisées par les nouvelles pages PHP et le générateur sitemap.</p></div>
  <button form="seoFormDb" class="btn btn-primary" type="submit">Enregistrer</button>
</div>
<?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<form id="seoFormDb" class="admin-panel form" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$id ?>">
  <div class="admin-form-grid">
    <label>Chemin de page<input name="page_path" value="<?= e($page['page_path']) ?>" placeholder="/"></label>
    <label>Libellé interne<input name="page_label" value="<?= e($page['page_label']) ?>"></label>
    <label class="full">Meta title<input name="meta_title" value="<?= e($page['meta_title']) ?>"></label>
    <label class="full">Meta description<textarea name="meta_description"><?= e($page['meta_description']) ?></textarea></label>
    <label class="full">Canonical URL<input name="canonical_url" value="<?= e($page['canonical_url']) ?>"></label>
    <label>Robots<select name="robots"><option value="index, follow" <?= $page['robots']==='index, follow'?'selected':'' ?>>index, follow</option><option value="noindex, follow" <?= $page['robots']==='noindex, follow'?'selected':'' ?>>noindex, follow</option></select></label>
    <label>OG image<input name="og_image" value="<?= e($page['og_image']) ?>"></label>
    <label>OG title<input name="og_title" value="<?= e($page['og_title']) ?>"></label>
    <label>OG description<textarea name="og_description"><?= e($page['og_description']) ?></textarea></label>
    <label>Priority<input type="number" step="0.1" min="0.1" max="1" name="sitemap_priority" value="<?= e($page['sitemap_priority']) ?>"></label>
    <label>Changefreq<select name="sitemap_changefreq"><?php foreach(array('always','hourly','daily','weekly','monthly','yearly','never') as $freq): ?><option value="<?= e($freq) ?>" <?= $page['sitemap_changefreq']===$freq?'selected':'' ?>><?= e($freq) ?></option><?php endforeach; ?></select></label>
    <label class="full consent-field"><input type="checkbox" name="sitemap_include" value="1" <?= !empty($page['sitemap_include'])?'checked':'' ?>> <span>Inclure dans le sitemap</span></label>
  </div>
</form>
<?php admin_footer(); ?>

