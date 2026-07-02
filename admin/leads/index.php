<?php
require __DIR__.'/../auth.php';
require_admin();

$statuses = allowed_lead_statuses();
$status = clean_text($_GET['statut'] ?? '', 80);
$offre = clean_text($_GET['offre'] ?? '', 100);
$budget = clean_text($_GET['budget'] ?? '', 190);
$q = clean_text($_GET['q'] ?? '', 190);
$where = array();
$params = array();
if ($status !== '') { $where[] = 'statut = :statut'; $params[':statut'] = $status; }
if ($offre !== '') { $where[] = 'source_offre = :offre'; $params[':offre'] = $offre; }
if ($budget !== '') { $where[] = 'budget = :budget'; $params[':budget'] = $budget; }
if ($q !== '') {
  $where[] = '(nom LIKE :q OR entreprise LIKE :q OR email LIKE :q)';
  $params[':q'] = '%' . $q . '%';
}
$sqlWhere = $where ? ' WHERE ' . implode(' AND ', $where) : '';

try {
  $pdo = db_required();
  $stmt = $pdo->prepare('SELECT * FROM leads_diagnostic_ia' . $sqlWhere . ' ORDER BY created_at DESC');
  $stmt->execute($params);
  $leads = $stmt->fetchAll();
} catch (Throwable $e) {
  app_log('Leads list failed: ' . $e->getMessage());
  $leads = array();
  $dbError = 'Base de données indisponible.';
}

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="leads-diagnostic-ia.csv"');
  $out = fopen('php://output', 'w');
  fputcsv($out, array('Date','Nom','Entreprise','Email','Téléphone','Offre','Besoin','Budget','Statut'));
  foreach ($leads as $lead) {
    fputcsv($out, array($lead['created_at'],$lead['nom'],$lead['entreprise'],$lead['email'],$lead['telephone'],$lead['source_offre'],$lead['besoin_principal'],$lead['budget'],$lead['statut']));
  }
  fclose($out);
  exit;
}

require __DIR__.'/../_layout.php';
admin_header('Admin — Leads CRM','leads');
?>
<div class="admin-top">
  <div><h1>Leads CRM</h1><p>Demandes issues du formulaire de pré-diagnostic IA.</p></div>
  <a class="btn btn-primary" href="/admin/leads/?<?= e(http_build_query(array_merge($_GET, array('export'=>'csv')))) ?>">Export CSV</a>
</div>
<?php if(!empty($dbError)): ?><div class="notice error"><?= e($dbError) ?></div><?php endif; ?>
<?php if(isset($_GET['updated'])): ?><div class="notice">Lead mis à jour.</div><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><div class="notice">Lead supprimé.</div><?php endif; ?>
<form class="admin-panel filter-row" method="get">
  <select name="statut"><option value="">Tous les statuts</option><?php foreach($statuses as $s): ?><option value="<?= e($s) ?>" <?= $status===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select>
  <select name="offre"><option value="">Toutes les offres</option><?php foreach(array('Diagnostic Flash IA','Diagnostic Adoption IA','Accompagnement 90 jours') as $o): ?><option value="<?= e($o) ?>" <?= $offre===$o?'selected':'' ?>><?= e($o) ?></option><?php endforeach; ?></select>
  <input name="budget" value="<?= e($budget) ?>" placeholder="Budget">
  <input name="q" value="<?= e($q) ?>" placeholder="Nom, entreprise, email">
  <button class="mini-btn" type="submit">Filtrer</button>
</form>
<div class="admin-panel">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Date</th><th>Nom</th><th>Entreprise</th><th>Offre</th><th>Besoin</th><th>Budget</th><th>Statut</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach($leads as $lead): ?>
        <tr>
          <td><?= e($lead['created_at']) ?></td>
          <td><strong><?= e($lead['nom']) ?></strong><br><span><?= e($lead['email']) ?></span></td>
          <td><?= e($lead['entreprise']) ?></td>
          <td><?= e($lead['source_offre']) ?></td>
          <td><?= e($lead['besoin_principal']) ?></td>
          <td><?= e($lead['budget']) ?></td>
          <td><?= e($lead['statut']) ?></td>
          <td><a class="mini-btn" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>">Voir</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if(!$leads): ?><tr><td colspan="8">Aucun lead trouvé.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_footer(); ?>

