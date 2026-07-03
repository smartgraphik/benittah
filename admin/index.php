<?php
require __DIR__.'/auth.php';
require_admin();
require __DIR__.'/_layout.php';

function dashboard_count($stats, $status) {
  return isset($stats[$status]) ? (int)$stats[$status] : 0;
}

function dashboard_format_date($date, $withTime = false) {
  if (empty($date)) { return '—'; }
  $timestamp = strtotime($date);
  if ($timestamp === false) { return $date; }
  return date($withTime ? 'd/m/Y H:i' : 'd/m/Y', $timestamp);
}

function dashboard_lead_name($lead) {
  $name = trim(($lead['prenom'] ?? '') . ' ' . ($lead['nom'] ?? ''));
  return $name !== '' ? $name : 'Lead sans nom';
}

function dashboard_offer($lead) {
  foreach (array('recommandation_principale', 'offre_recommandee', 'source_offre') as $field) {
    if (!empty($lead[$field])) { return $lead[$field]; }
  }
  return 'Non renseignée';
}

function dashboard_status_key($status) {
  $map = array(
    'Nouveau'=>'new',
    'À qualifier'=>'qualify',
    'RDV proposé'=>'meeting-proposed',
    'RDV planifié'=>'meeting-planned',
    'Proposition envoyée'=>'proposal',
    'Gagné'=>'won',
    'Perdu'=>'lost',
    'À relancer'=>'followup',
  );
  return $map[$status] ?? 'default';
}

function dashboard_urgency_key($level, $score) {
  $score = is_numeric($score) ? (int)$score : 0;
  if ($score >= 75 || $level === 'Urgent') { return 'high'; }
  if ($score >= 50 || $level === 'Prioritaire') { return 'medium'; }
  if ($score > 0 || $level !== '') { return 'low'; }
  return 'default';
}

function dashboard_score_label($score) {
  return is_numeric($score) ? (int)$score . '/100' : '—';
}

function dashboard_priority_reason($lead) {
  if (($lead['statut'] ?? '') === 'À relancer') { return 'À relancer'; }
  if (($lead['niveau_urgence'] ?? '') === 'Urgent' || (int)($lead['score_urgence'] ?? 0) >= 75) { return 'Urgence élevée'; }
  if (($lead['niveau_urgence'] ?? '') === 'Prioritaire') { return 'Prioritaire'; }
  return 'À traiter';
}

$statuses = allowed_lead_statuses();
$stats = array_fill_keys($statuses, 0);
$latest = array();
$priorities = array();
$dbError = '';

try {
  $pdo = db_required();
  $rows = $pdo->query("SELECT statut, COUNT(*) AS total FROM leads_diagnostic_ia GROUP BY statut")->fetchAll();
  foreach ($rows as $row) {
    if (isset($stats[$row['statut']])) { $stats[$row['statut']] = (int)$row['total']; }
  }
  $latest = $pdo->query("SELECT id, created_at, prenom, nom, entreprise, source_offre, offre_recommandee, recommandation_principale, score_transformation_global, niveau_transformation, score_urgence, niveau_urgence, statut FROM leads_diagnostic_ia ORDER BY created_at DESC LIMIT 8")->fetchAll();
  $priorities = $pdo->query("SELECT id, created_at, prenom, nom, entreprise, source_offre, offre_recommandee, recommandation_principale, score_urgence, niveau_urgence, statut, date_relance FROM leads_diagnostic_ia WHERE statut = 'À relancer' OR COALESCE(score_urgence, 0) >= 75 OR niveau_urgence IN ('Urgent', 'Prioritaire') ORDER BY CASE WHEN statut = 'À relancer' THEN 0 WHEN COALESCE(score_urgence, 0) >= 75 THEN 1 ELSE 2 END, COALESCE(date_relance, DATE(created_at)) ASC, COALESCE(score_urgence, 0) DESC, created_at DESC LIMIT 6")->fetchAll();
} catch (Throwable $e) {
  $dbError = 'Base de données indisponible. Vérifiez config.local.php et la migration SQL.';
  app_log('Admin dashboard failed: ' . $e->getMessage());
}

$primaryKpis = array(
  array('label'=>'Nouveaux leads', 'status'=>'Nouveau', 'note'=>'à traiter rapidement', 'icon'=>'+'),
  array('label'=>'À qualifier', 'status'=>'À qualifier', 'note'=>'à prioriser côté CRM', 'icon'=>'?'),
  array('label'=>'RDV planifiés', 'status'=>'RDV planifié', 'note'=>'dans le tunnel commercial', 'icon'=>'RDV'),
  array('label'=>'Gagnés', 'status'=>'Gagné', 'note'=>'opportunités converties', 'icon'=>'OK'),
);

$secondaryKpis = array(
  array('label'=>'RDV proposés', 'status'=>'RDV proposé'),
  array('label'=>'Propositions envoyées', 'status'=>'Proposition envoyée'),
  array('label'=>'À relancer', 'status'=>'À relancer'),
  array('label'=>'Perdus', 'status'=>'Perdu'),
);

admin_header('Admin — Tableau de bord CRM','dashboard');
?>
<section class="dashboard-hero">
  <div>
    <span class="dashboard-eyebrow">Pilotage commercial</span>
    <h1>Tableau de bord CRM</h1>
    <p>Suivi des leads, du Diagnostic Transformation 360°, des contenus et du SEO.</p>
  </div>
  <div class="dashboard-actions">
    <a class="btn btn-primary" href="/admin/leads/">Voir les leads</a>
    <a class="btn btn-outline" href="/admin/leads/?export=csv">Exporter CSV</a>
  </div>
</section>

<?php if($dbError): ?><div class="notice error"><?= e($dbError) ?></div><?php endif; ?>

<section class="dashboard-section">
  <div class="dashboard-section-head">
    <div>
      <span class="dashboard-section-kicker">Vue immédiate</span>
      <h2>KPI principaux</h2>
    </div>
  </div>
  <div class="dashboard-kpi-grid">
    <?php foreach($primaryKpis as $kpi): ?>
      <?php $key = dashboard_status_key($kpi['status']); ?>
      <a class="dashboard-kpi-card dashboard-tone-<?= e($key) ?>" href="/admin/leads/?<?= e(http_build_query(array('statut'=>$kpi['status']))) ?>">
        <span class="dashboard-kpi-icon"><?= e($kpi['icon']) ?></span>
        <span class="dashboard-kpi-content">
          <span class="dashboard-kpi-label"><?= e($kpi['label']) ?></span>
          <strong><?= dashboard_count($stats, $kpi['status']) ?></strong>
          <small><?= e($kpi['note']) ?></small>
        </span>
      </a>
    <?php endforeach; ?>
  </div>
  <div class="dashboard-secondary-grid">
    <?php foreach($secondaryKpis as $kpi): ?>
      <?php $key = dashboard_status_key($kpi['status']); ?>
      <a class="dashboard-secondary-card dashboard-tone-<?= e($key) ?>" href="/admin/leads/?<?= e(http_build_query(array('statut'=>$kpi['status']))) ?>">
        <span><?= e($kpi['label']) ?></span>
        <strong><?= dashboard_count($stats, $kpi['status']) ?></strong>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="dashboard-section">
  <div class="dashboard-section-head">
    <div>
      <span class="dashboard-section-kicker">Tunnel</span>
      <h2>Pipeline commercial</h2>
    </div>
    <a class="mini-btn" href="/admin/leads/">Tous les leads</a>
  </div>
  <div class="dashboard-pipeline">
    <?php foreach($statuses as $status): ?>
      <?php $key = dashboard_status_key($status); ?>
      <a class="dashboard-pipeline-step dashboard-tone-<?= e($key) ?>" href="/admin/leads/?<?= e(http_build_query(array('statut'=>$status))) ?>">
        <span class="dashboard-pipeline-label"><i></i><?= e($status) ?></span>
        <strong><?= dashboard_count($stats, $status) ?></strong>
        <small>Voir la liste</small>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="dashboard-workgrid">
  <div class="admin-panel dashboard-latest-panel">
    <div class="admin-panel-head">
      <div>
        <span class="dashboard-section-kicker">Activité récente</span>
        <h2>Derniers leads reçus</h2>
      </div>
      <a class="mini-btn" href="/admin/leads/?export=csv">Export CSV</a>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table dashboard-table">
        <thead><tr><th>Date</th><th>Nom</th><th>Entreprise</th><th>Offre recommandée</th><th>Score global</th><th>Urgence</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($latest as $lead): ?>
          <?php
            $status = $lead['statut'] ?? 'Nouveau';
            $statusKey = dashboard_status_key($status);
            $urgencyKey = dashboard_urgency_key($lead['niveau_urgence'] ?? '', $lead['score_urgence'] ?? '');
          ?>
          <tr>
            <td><span class="dashboard-date"><?= e(dashboard_format_date($lead['created_at'] ?? '', true)) ?></span></td>
            <td><strong class="dashboard-lead-name"><?= e(dashboard_lead_name($lead)) ?></strong></td>
            <td><span class="dashboard-company"><?= e(($lead['entreprise'] ?? '') ?: '—') ?></span></td>
            <td><span class="dashboard-offer"><?= e(dashboard_offer($lead)) ?></span></td>
            <td><span class="dashboard-score-chip"><strong><?= e(dashboard_score_label($lead['score_transformation_global'] ?? null)) ?></strong><small><?= e($lead['niveau_transformation'] ?? '') ?></small></span></td>
            <td><span class="dashboard-urgency-pill dashboard-urgency-<?= e($urgencyKey) ?>"><?= e(($lead['niveau_urgence'] ?? '') ?: 'Non qualifiée') ?><small><?= e(dashboard_score_label($lead['score_urgence'] ?? null)) ?></small></span></td>
            <td><span class="dashboard-status-pill dashboard-tone-<?= e($statusKey) ?>"><?= e($status) ?></span></td>
            <td><a class="mini-btn" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>">Voir</a></td>
          </tr>
        <?php endforeach; ?>
        <?php if(!$latest): ?><tr><td colspan="8">Aucun lead pour le moment.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <aside class="admin-panel dashboard-priority-panel">
    <div class="admin-panel-head">
      <div>
        <span class="dashboard-section-kicker">Actions rapides</span>
        <h2>Priorités du moment</h2>
      </div>
    </div>
    <?php if($priorities): ?>
      <div class="dashboard-priority-list">
        <?php foreach($priorities as $lead): ?>
          <?php
            $reason = dashboard_priority_reason($lead);
            $urgencyKey = dashboard_urgency_key($lead['niveau_urgence'] ?? '', $lead['score_urgence'] ?? '');
          ?>
          <a class="dashboard-priority-item" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>">
            <span class="dashboard-priority-top">
              <strong><?= e(dashboard_lead_name($lead)) ?></strong>
              <em class="dashboard-urgency-pill dashboard-urgency-<?= e($urgencyKey) ?>"><?= e($reason) ?></em>
            </span>
            <span class="dashboard-priority-meta"><?= e(($lead['entreprise'] ?? '') ?: 'Sans entreprise') ?> · <?= e(dashboard_format_date($lead['date_relance'] ?? ($lead['created_at'] ?? ''), false)) ?></span>
            <span class="dashboard-priority-offer"><?= e(dashboard_offer($lead)) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="dashboard-empty">Aucune priorité détectée pour le moment.</p>
    <?php endif; ?>
  </aside>
</section>
<?php admin_footer(); ?>
