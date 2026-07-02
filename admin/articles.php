<?php
require __DIR__.'/auth.php';
require_admin();
require __DIR__.'/_layout.php';
$arts = read_json('articles', array());
usort($arts, 'sort_by_priority_desc');
admin_header('Admin — Articles', 'articles');
?>
<div class="admin-top"><div><h1>Articles</h1><p>Ajoutez, modifiez ou supprimez les articles repris depuis l’ancien site.</p></div><a class="btn btn-primary" href="/admin/article-edit.php">+ Ajouter un article</a></div>
<?php if(isset($_GET['saved'])): ?><div class="notice">Article enregistré.</div><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><div class="notice">Article supprimé.</div><?php endif; ?>
<div class="admin-panel"><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Image</th><th>Titre</th><th>Catégorie</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead><tbody>
<?php foreach($arts as $a): ?>
<tr><td><img class="thumb" src="<?= e(isset($a['image']) ? $a['image'] : '') ?>" alt=""></td><td><strong><?= e(isset($a['title']) ? $a['title'] : '') ?></strong><br><span class="article-meta"><?= e(excerpt(isset($a['excerpt']) ? $a['excerpt'] : '',115)) ?></span></td><td><span class="pill is-active"><?= e(isset($a['category']) ? $a['category'] : '') ?></span></td><td><?= (isset($a['status']) ? $a['status'] : 'published') === 'draft' ? 'Brouillon' : 'Publié' ?></td><td><?= e(isset($a['date']) ? $a['date'] : '') ?></td><td><div class="admin-actions"><a class="mini-btn" href="/admin/article-edit.php?slug=<?= rawurlencode(isset($a['slug']) ? $a['slug'] : '') ?>">Modifier</a><form method="post" action="/admin/article-delete.php" style="margin:0"><?= csrf_field() ?><input type="hidden" name="slug" value="<?= e(isset($a['slug']) ? $a['slug'] : '') ?>"><button class="mini-btn danger" data-confirm="Supprimer cet article ?" type="submit">Supprimer</button></form></div></td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php admin_footer(); ?>
