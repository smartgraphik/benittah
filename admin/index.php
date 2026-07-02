<?php
require __DIR__.'/auth.php';
require_admin();
require __DIR__.'/_layout.php';

$stats = array_fill_keys(allowed_lead_statuses(), 0);
$latest = array();
$dbError = '';
try {
  $pdo = db_required();
  $rows = $pdo->query("SELECT statut, COUNT(*) AS total FROM leads_diagnostic_ia GROUP BY statut")->fetchAll();
  foreach ($rows as $row) {
    if (isset($stats[$row['statut']])) { $stats[$row['statut']] = (int)$row['total']; }
  }
  $latest = $pdo->query("SELECT id, created_at, prenom, nom, entreprise, source_offre, offre_recommandee, statut FROM leads_diagnostic_ia ORDER BY created_at DESC LIMIT 8")->fetchAll();
} catch (Throwable $e) {
  $dbError = 'Base de données indisponible. Vérifiez config.local.php et la migration SQL.';
  app_log('Admin dashboard failed: ' . $e->getMessage());
}

admin_header('Admin — Tableau de bord','dashboard');
?>
<div class="admin-top">
  <div><h1>Tableau de bord</h1><p>CRM pré-diagnostic IA, articles, SEO et sitemap.</p></div>
  <a class="btn btn-primary" href="/admin/leads/">Voir les leads</a>
</div>
<?php if($dbError): ?><div class="notice error"><?= e($dbError) ?></div><?php endif; ?>
<div class="cards admin-stats">
  <?php foreach($stats as $label=>$total): ?>
    <div class="card"><div class="card-icon"><?= e(substr($label,0,1)) ?></div><h3><?= (int)$total ?></h3><p><?= e($label) ?></p></div>
  <?php endforeach; ?>
</div>
<div class="admin-panel">
  <div class="admin-panel-head"><h2>Derniers leads reçus</h2><a class="mini-btn" href="/admin/leads/?export=csv">Export CSV</a></div>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Date</th><th>Contact</th><th>Entreprise</th><th>Offre recommandée</th><th>Statut</th><th></th></tr></thead>
      <tbody>
      <?php foreach($latest as $lead): ?>
        <tr>
          <td><?= e($lead['created_at']) ?></td>
          <td><?= e(trim(($lead['prenom'] ?? '') . ' ' . ($lead['nom'] ?? ''))) ?></td>
          <td><?= e($lead['entreprise'] ?? '') ?></td>
          <td><?= e($lead['offre_recommandee'] ?? ($lead['source_offre'] ?? '')) ?></td>
          <td><?= e($lead['statut'] ?? '') ?></td>
          <td><a class="mini-btn" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>">Voir</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if(!$latest): ?><tr><td colspan="6">Aucun lead pour le moment.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_footer(); ?>
