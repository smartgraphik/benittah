<?php
require __DIR__.'/../auth.php';
require_admin();
require __DIR__.'/../_layout.php';

try {
  $pages = db_fetch_all('SELECT * FROM seo_pages ORDER BY page_path ASC');
  $dbError = '';
} catch (Throwable $e) {
  app_log('SEO list failed: ' . $e->getMessage());
  $pages = array();
  $dbError = 'Base de données indisponible.';
}

admin_header('Admin — SEO','seo');
?>
<div class="admin-top">
  <div><h1>SEO</h1><p>Gestion V1 des metas et paramètres sitemap des pages principales.</p></div>
  <div class="cta-row">
    <a class="btn btn-primary" href="/admin/seo/edit.php">Ajouter une page SEO</a>
    <form method="post" action="/admin/sitemap/generate.php"><?= csrf_field() ?><button class="btn btn-outline" type="submit">Régénérer le sitemap</button></form>
  </div>
</div>
<?php if($dbError): ?><div class="notice error"><?= e($dbError) ?></div><?php endif; ?>
<?php if(isset($_GET['saved'])): ?><div class="notice">Entrée SEO enregistrée.</div><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><div class="notice">Entrée SEO supprimée.</div><?php endif; ?>
<?php if(isset($_GET['sitemap'])): ?><div class="notice">Sitemap régénéré.</div><?php endif; ?>
<div class="admin-panel">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Chemin</th><th>Libellé</th><th>Meta title</th><th>Robots</th><th>Sitemap</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($pages as $page): ?>
        <tr>
          <td><?= e($page['page_path']) ?></td>
          <td><?= e($page['page_label']) ?></td>
          <td><?= e($page['meta_title']) ?></td>
          <td><?= e($page['robots']) ?></td>
          <td><?= !empty($page['sitemap_include']) ? e($page['sitemap_priority'] . ' · ' . $page['sitemap_changefreq']) : 'Non' ?></td>
          <td class="admin-actions">
            <a class="mini-btn" href="/admin/seo/edit.php?id=<?= (int)$page['id'] ?>">Modifier</a>
            <form method="post" action="/admin/seo/delete.php" style="margin:0">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$page['id'] ?>">
              <button class="mini-btn danger" data-confirm="Supprimer cette entrée SEO ?" type="submit">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(!$pages): ?><tr><td colspan="6">Aucune entrée SEO.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_footer(); ?>

