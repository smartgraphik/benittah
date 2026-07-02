<?php
require __DIR__.'/../auth.php';
require_admin();

$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
$article = array(
  'id'=>0,
  'status'=>'draft',
  'title'=>'',
  'slug'=>'',
  'excerpt'=>'',
  'content'=>'',
  'category'=>'',
  'cover_image'=>'',
  'meta_title'=>'',
  'meta_description'=>'',
  'canonical_url'=>'',
  'noindex'=>0,
  'published_at'=>'',
);
$error = '';

if ($id) {
  try {
    $found = db_fetch_one('SELECT * FROM articles WHERE id = :id LIMIT 1', array(':id'=>$id));
    if ($found) { $article = array_merge($article, $found); }
  } catch (Throwable $e) {
    $error = 'Article introuvable ou base indisponible.';
    app_log('Admin article edit load failed: ' . $e->getMessage());
  }
}

if (request_method_is_post()) {
  require_csrf();
  $title = clean_text($_POST['title'] ?? '', 255);
  $slug = slugify_text($_POST['slug'] ?? $title);
  $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
  $payload = array(
    ':status'=>$status,
    ':title'=>$title,
    ':slug'=>$slug,
    ':excerpt'=>clean_textarea($_POST['excerpt'] ?? '', 3000),
    ':content'=>clean_textarea($_POST['content'] ?? '', 65000),
    ':category'=>clean_text($_POST['category'] ?? '', 120),
    ':cover_image'=>clean_text($_POST['cover_image'] ?? '', 255),
    ':meta_title'=>clean_text($_POST['meta_title'] ?? '', 255),
    ':meta_description'=>clean_text($_POST['meta_description'] ?? '', 320),
    ':canonical_url'=>clean_text($_POST['canonical_url'] ?? '', 255),
    ':noindex'=>!empty($_POST['noindex']) ? 1 : 0,
  );
  if ($title === '' || $payload[':content'] === '') {
    $error = 'Titre et contenu sont obligatoires.';
  } else {
    try {
      if ($id) {
        $payload[':id'] = $id;
        $publishedSql = 'published_at = published_at';
        if ($status === 'published' && empty($article['published_at'])) { $publishedSql = 'published_at = NOW()'; }
        if ($status === 'draft') { $publishedSql = 'published_at = NULL'; }
        db_execute('UPDATE articles SET status=:status, title=:title, slug=:slug, excerpt=:excerpt, content=:content, category=:category, cover_image=:cover_image, meta_title=:meta_title, meta_description=:meta_description, canonical_url=:canonical_url, noindex=:noindex, ' . $publishedSql . ', updated_at=NOW() WHERE id=:id', $payload);
      } else {
        $publishedAtSql = $status === 'published' ? 'NOW()' : 'NULL';
        db_execute('INSERT INTO articles (status, title, slug, excerpt, content, category, cover_image, meta_title, meta_description, canonical_url, noindex, published_at) VALUES (:status, :title, :slug, :excerpt, :content, :category, :cover_image, :meta_title, :meta_description, :canonical_url, :noindex, ' . $publishedAtSql . ')', $payload);
      }
      header('Location: /admin/articles/?saved=1');
      exit;
    } catch (Throwable $e) {
      app_log('Admin article save failed: ' . $e->getMessage());
      $error = 'Impossible d’enregistrer cet article. Vérifiez que le slug est unique.';
    }
  }
  $article = array_merge($article, array(
    'status'=>$status,
    'title'=>$title,
    'slug'=>$slug,
    'excerpt'=>$payload[':excerpt'],
    'content'=>$payload[':content'],
    'category'=>$payload[':category'],
    'cover_image'=>$payload[':cover_image'],
    'meta_title'=>$payload[':meta_title'],
    'meta_description'=>$payload[':meta_description'],
    'canonical_url'=>$payload[':canonical_url'],
    'noindex'=>$payload[':noindex'],
  ));
}

require __DIR__.'/../_layout.php';
admin_header(($id ? 'Modifier' : 'Créer') . ' un article','articles');
?>
<div class="admin-top">
  <div><h1><?= $id ? 'Modifier' : 'Créer' ?> un article</h1><p>Textarea simple, HTML léger autorisé côté contenu.</p></div>
  <button form="articleForm" class="btn btn-primary" type="submit">Enregistrer</button>
</div>
<?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<form id="articleForm" class="admin-panel form" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$id ?>">
  <div class="admin-form-grid">
    <label>Titre<input data-slug-source name="title" value="<?= e($article['title']) ?>" required></label>
    <label>Slug<input data-slug-target name="slug" value="<?= e($article['slug']) ?>" required></label>
    <label>Statut<select name="status"><option value="draft" <?= $article['status']==='draft'?'selected':'' ?>>Brouillon</option><option value="published" <?= $article['status']==='published'?'selected':'' ?>>Publié</option></select></label>
    <label>Catégorie<input name="category" value="<?= e($article['category']) ?>"></label>
    <label class="full">Extrait<textarea name="excerpt"><?= e($article['excerpt']) ?></textarea></label>
    <label class="full">Contenu<textarea class="tall" name="content"><?= e($article['content']) ?></textarea></label>
    <label>Image de couverture<input name="cover_image" value="<?= e($article['cover_image']) ?>"></label>
    <label>Canonical URL<input name="canonical_url" value="<?= e($article['canonical_url']) ?>"></label>
    <label>Meta title<input name="meta_title" value="<?= e($article['meta_title']) ?>"></label>
    <label>Meta description<textarea name="meta_description"><?= e($article['meta_description']) ?></textarea></label>
    <label class="full consent-field"><input type="checkbox" name="noindex" value="1" <?= !empty($article['noindex'])?'checked':'' ?>> <span>Noindex</span></label>
  </div>
</form>
<?php admin_footer(); ?>
