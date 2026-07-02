<?php
require __DIR__.'/../auth.php';
require_admin();
require __DIR__.'/../_layout.php';

try {
  $articles = db_fetch_all('SELECT * FROM articles ORDER BY COALESCE(published_at, created_at) DESC');
  $dbError = '';
} catch (Throwable $e) {
  app_log('Admin articles list failed: ' . $e->getMessage());
  $articles = array();
  $dbError = 'Base de données indisponible.';
}

admin_header('Admin — Articles','articles');
?>
<div class="admin-top">
  <div><h1>Articles</h1><p>Gestion V1 des articles stockés en base.</p></div>
  <a class="btn btn-primary" href="/admin/articles/edit.php">Créer un article</a>
</div>
<?php if($dbError): ?><div class="notice error"><?= e($dbError) ?></div><?php endif; ?>
<?php if(isset($_GET['saved'])): ?><div class="notice">Article enregistré.</div><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><div class="notice">Article supprimé.</div><?php endif; ?>
<div class="admin-panel">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Titre</th><th>Slug</th><th>Catégorie</th><th>Statut</th><th>Publication</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($articles as $article): ?>
        <tr>
          <td><strong><?= e($article['title']) ?></strong><br><span><?= e(excerpt($article['excerpt'] ?? '', 120)) ?></span></td>
          <td><?= e($article['slug']) ?></td>
          <td><?= e($article['category']) ?></td>
          <td><?= e($article['status']) ?><?= !empty($article['noindex']) ? ' · noindex' : '' ?></td>
          <td><?= e($article['published_at']) ?></td>
          <td class="admin-actions">
            <a class="mini-btn" href="/admin/articles/edit.php?id=<?= (int)$article['id'] ?>">Modifier</a>
            <form method="post" action="/admin/articles/delete.php" style="margin:0">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$article['id'] ?>">
              <button class="mini-btn danger" data-confirm="Supprimer cet article ?" type="submit">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(!$articles): ?><tr><td colspan="6">Aucun article en base pour le moment.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_footer(); ?>

