<?php
require __DIR__.'/auth.php';
require_admin();
require __DIR__.'/_layout.php';

function dashboard_icon($name) {
  $icons = array(
    'user-plus'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M15 19a6 6 0 0 0-12 0"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/></svg>',
    'target'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/><path d="M12 2v3"/><path d="M12 19v3"/><path d="M2 12h3"/><path d="M19 12h3"/></svg>',
    'calendar'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M3 11h18"/></svg>',
    'check'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 6 9 17l-5-5"/></svg>',
    'download'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>',
    'users'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'arrow'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>',
    'alert'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m12 3 10 18H2L12 3Z"/><path d="M12 9v5"/><path d="M12 18h.01"/></svg>',
    'file'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/></svg>',
    'search'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>',
    'map'=>'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3Z"/><path d="M9 3v15"/><path d="M15 6v15"/></svg>',
  );
  return $icons[$name] ?? $icons['file'];
}

function dashboard_status_configs($statuses) {
  $known = array(
    'Nouveau'=>array('class'=>'new', 'icon'=>'user-plus'),
    'À qualifier'=>array('class'=>'qualify', 'icon'=>'target'),
    'RDV proposé'=>array('class'=>'meeting', 'icon'=>'calendar'),
    'RDV planifié'=>array('class'=>'meeting-planned', 'icon'=>'calendar'),
    'Proposition envoyée'=>array('class'=>'proposal', 'icon'=>'file'),
    'Gagné'=>array('class'=>'success', 'icon'=>'check'),
    'Perdu'=>array('class'=>'danger', 'icon'=>'alert'),
    'À relancer'=>array('class'=>'warning', 'icon'=>'alert'),
  );
  $configs = array();
  foreach ($statuses as $status) {
    $configs[$status] = array(
      'label'=>$status,
      'class'=>$known[$status]['class'] ?? 'neutral',
      'icon'=>$known[$status]['icon'] ?? 'file',
      'url'=>'/admin/leads/?' . http_build_query(array('statut'=>$status)),
    );
  }
  return $configs;
}

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

function dashboard_lead_secondary($lead) {
  if (!empty($lead['email'])) { return $lead['email']; }
  if (!empty($lead['role_contact'])) { return $lead['role_contact']; }
  return 'Diagnostic Transformation 360°';
}

function dashboard_offer($lead) {
  foreach (array('recommandation_principale', 'offre_recommandee', 'source_offre') as $field) {
    if (!empty($lead[$field])) { return $lead[$field]; }
  }
  return 'Non renseignée';
}

function dashboard_score_value($score) {
  if (!is_numeric($score)) { return null; }
  return max(0, min(100, (int)$score));
}

function dashboard_score_label($score) {
  $value = dashboard_score_value($score);
  return $value === null ? '—' : $value . '/100';
}

function dashboard_urgency_class($level, $score) {
  $score = dashboard_score_value($score) ?? 0;
  if ($score >= 75 || $level === 'Urgent') { return 'risk-high'; }
  if ($score >= 50 || $level === 'Prioritaire') { return 'risk-medium'; }
  if ($score > 0 || $level !== '') { return 'risk-low'; }
  return 'neutral';
}

function dashboard_priority_title($lead) {
  $status = $lead['statut'] ?? '';
  if ($status === 'À relancer') { return 'Relancer ' . dashboard_lead_name($lead); }
  if ($status === 'Nouveau') { return 'Qualifier ' . dashboard_lead_name($lead); }
  if (($lead['niveau_urgence'] ?? '') === 'Urgent' || (int)($lead['score_urgence'] ?? 0) >= 75) { return 'Traiter le diagnostic urgent'; }
  return 'Suivre ' . dashboard_lead_name($lead);
}

function dashboard_priority_badge($lead) {
  $status = $lead['statut'] ?? '';
  if ($status === 'À relancer') { return 'À relancer'; }
  if ($status === 'Nouveau') { return 'Nouveau lead'; }
  if (($lead['niveau_urgence'] ?? '') === 'Urgent' || (int)($lead['score_urgence'] ?? 0) >= 75) { return 'Urgence élevée'; }
  return ($lead['niveau_urgence'] ?? '') ?: 'Prioritaire';
}

function dashboard_plural($count, $singular, $plural) {
  return (int)$count === 1 ? $singular : $plural;
}

$statuses = allowed_lead_statuses();
$statusConfigs = dashboard_status_configs($statuses);
$stats = array_fill_keys($statuses, 0);
$latest = array();
$priorities = array();
$contentStatus = array();
$dbError = '';

try {
  $pdo = db_required();

  $rows = $pdo->query("SELECT statut, COUNT(*) AS total FROM leads_diagnostic_ia GROUP BY statut")->fetchAll();
  foreach ($rows as $row) {
    if (isset($stats[$row['statut']])) { $stats[$row['statut']] = (int)$row['total']; }
  }

  $latest = $pdo->query("SELECT id, created_at, prenom, nom, entreprise, email, role_contact, source_offre, offre_recommandee, recommandation_principale, score_transformation_global, niveau_transformation, score_urgence, niveau_urgence, statut FROM leads_diagnostic_ia ORDER BY created_at DESC LIMIT 8")->fetchAll();

  $priorities = $pdo->query("SELECT id, created_at, prenom, nom, entreprise, email, role_contact, source_offre, offre_recommandee, recommandation_principale, score_urgence, niveau_urgence, statut, date_relance FROM leads_diagnostic_ia WHERE statut IN ('Nouveau', 'À qualifier', 'À relancer') OR COALESCE(score_urgence, 0) >= 75 OR niveau_urgence IN ('Urgent', 'Prioritaire') ORDER BY CASE WHEN statut = 'À relancer' THEN 0 WHEN COALESCE(score_urgence, 0) >= 75 OR niveau_urgence = 'Urgent' THEN 1 WHEN statut = 'Nouveau' THEN 2 ELSE 3 END, COALESCE(date_relance, DATE(created_at)) ASC, created_at DESC LIMIT 6")->fetchAll();

  try {
    $contentStatus[] = array('label'=>'Articles publiés', 'value'=>(int)$pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'")->fetchColumn(), 'meta'=>'en ligne', 'icon'=>'file', 'url'=>'/admin/articles/');
    $contentStatus[] = array('label'=>'Pages SEO configurées', 'value'=>(int)$pdo->query("SELECT COUNT(*) FROM seo_pages")->fetchColumn(), 'meta'=>'suivies', 'icon'=>'search', 'url'=>'/admin/seo/');
  } catch (Throwable $e) {
    app_log('Admin dashboard content counters failed: ' . $e->getMessage());
    $contentStatus = array();
  }
} catch (Throwable $e) {
  $dbError = 'Base de données indisponible. Vérifiez config.local.php et la migration SQL.';
  app_log('Admin dashboard failed: ' . $e->getMessage());
}

$sitemapPath = __DIR__ . '/../sitemap.xml';
if (is_file($sitemapPath)) {
  $sitemapXml = @file_get_contents($sitemapPath);
  $urlCount = is_string($sitemapXml) ? substr_count($sitemapXml, '<url>') : 0;
  $sitemapModifiedAt = filemtime($sitemapPath);
  $sitemapMeta = 'URLs';
  if ($sitemapModifiedAt !== false) {
    $sitemapMeta .= ' · ' . dashboard_format_date(date('Y-m-d H:i:s', $sitemapModifiedAt), true);
  }
  $contentStatus[] = array('label'=>'Sitemap', 'value'=>$urlCount, 'meta'=>$sitemapMeta, 'icon'=>'map', 'url'=>'/sitemap.xml');
}

$primaryKpis = array(
  array('label'=>'Nouveaux leads', 'status'=>'Nouveau', 'footer'=>'À traiter rapidement', 'class'=>'new', 'icon'=>'user-plus'),
  array('label'=>'À qualifier', 'status'=>'À qualifier', 'footer'=>'À prioriser côté CRM', 'class'=>'qualify', 'icon'=>'target'),
  array('label'=>'RDV planifiés', 'status'=>'RDV planifié', 'footer'=>'Dans le tunnel commercial', 'class'=>'meeting-planned', 'icon'=>'calendar'),
  array('label'=>'Opportunités gagnées', 'status'=>'Gagné', 'footer'=>'Conversions confirmées', 'class'=>'success', 'icon'=>'check'),
);

admin_header('Admin — Tableau de bord CRM','dashboard', array('/admin/assets/admin-dashboard.css'), 'admin-dashboard');
?>
  <header class="dashboard-header">
    <div class="dashboard-header__content">
      <p class="dashboard-header__eyebrow">Administration</p>
      <h1 class="dashboard-header__title">Tableau de bord CRM</h1>
      <p class="dashboard-header__subtitle">Suivi des leads, du Diagnostic Transformation 360°, des contenus et du SEO.</p>
    </div>

    <div class="dashboard-header__actions">
      <a class="button button--secondary" href="/admin/leads/?export=csv">
        <?= dashboard_icon('download') ?>
        <span>Exporter CSV</span>
      </a>
      <a class="button button--primary" href="/admin/leads/">
        <?= dashboard_icon('users') ?>
        <span>Voir les leads</span>
      </a>
    </div>
  </header>

  <?php if($dbError): ?><div class="notice error"><?= e($dbError) ?></div><?php endif; ?>

  <section class="dashboard-kpis" aria-labelledby="dashboard-kpis-title">
    <div class="dashboard-section__header dashboard-section__header--compact">
      <div>
        <h2 id="dashboard-kpis-title">KPI principaux</h2>
        <p>Les signaux commerciaux à lire en premier.</p>
      </div>
    </div>

    <div class="kpi-grid">
      <?php foreach($primaryKpis as $kpi): ?>
        <a class="kpi-card kpi-card--<?= e($kpi['class']) ?>" href="<?= e($statusConfigs[$kpi['status']]['url']) ?>">
          <span class="kpi-card__top">
            <span class="kpi-card__icon"><?= dashboard_icon($kpi['icon']) ?></span>
            <span class="kpi-card__label"><?= e($kpi['label']) ?></span>
          </span>
          <strong class="kpi-card__value"><?= dashboard_count($stats, $kpi['status']) ?></strong>
          <span class="kpi-card__footer"><?= e($kpi['footer']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="dashboard-section dashboard-pipeline" aria-labelledby="dashboard-pipeline-title">
    <div class="dashboard-section__header">
      <div>
        <h2 id="dashboard-pipeline-title">Pipeline commercial</h2>
        <p>Répartition actuelle des opportunités selon leur avancement commercial.</p>
      </div>
      <a class="dashboard-section__link" href="/admin/leads/">Afficher tous les leads</a>
    </div>

    <div class="pipeline-grid">
      <?php foreach($statusConfigs as $status=>$config): ?>
        <a class="pipeline-card pipeline-card--<?= e($config['class']) ?>" href="<?= e($config['url']) ?>">
          <span class="pipeline-card__status">
            <span class="pipeline-card__dot" aria-hidden="true"></span>
            <?= e($config['label']) ?>
          </span>
          <strong class="pipeline-card__count"><?= dashboard_count($stats, $status) ?></strong>
          <span class="pipeline-card__label"><?= e(dashboard_plural(dashboard_count($stats, $status), 'lead', 'leads')) ?></span>
          <span class="pipeline-card__arrow"><?= dashboard_icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <div class="dashboard-main-grid">
    <section class="dashboard-card dashboard-latest-leads" aria-labelledby="dashboard-latest-title">
      <div class="dashboard-card__header">
        <div>
          <h2 id="dashboard-latest-title">Derniers leads reçus</h2>
          <p>Les diagnostics et prises de contact les plus récents.</p>
        </div>
        <a class="button button--secondary button--small" href="/admin/leads/">Voir tous les leads</a>
      </div>

      <?php if($latest): ?>
        <div class="table-responsive" aria-label="Derniers leads reçus, tableau défilable horizontalement">
          <table class="leads-table">
            <thead>
              <tr>
                <th scope="col">Date</th>
                <th scope="col">Prospect</th>
                <th scope="col">Entreprise</th>
                <th scope="col">Recommandation</th>
                <th scope="col">Score</th>
                <th scope="col">Urgence</th>
                <th scope="col">Statut</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($latest as $lead): ?>
              <?php
                $status = $lead['statut'] ?? 'Nouveau';
                $config = $statusConfigs[$status] ?? array('class'=>'neutral', 'label'=>$status ?: 'Non qualifié');
                $score = dashboard_score_value($lead['score_transformation_global'] ?? null);
                $urgencyClass = dashboard_urgency_class($lead['niveau_urgence'] ?? '', $lead['score_urgence'] ?? null);
                $recommendation = dashboard_offer($lead);
              ?>
              <tr>
                <td><span class="leads-table__date"><?= e(dashboard_format_date($lead['created_at'] ?? '', true)) ?></span></td>
                <td>
                  <span class="leads-table__person">
                    <strong class="leads-table__primary"><?= e(dashboard_lead_name($lead)) ?></strong>
                    <span class="leads-table__secondary"><?= e(dashboard_lead_secondary($lead)) ?></span>
                  </span>
                </td>
                <td><?= e(($lead['entreprise'] ?? '') ?: '—') ?></td>
                <td>
                  <span class="leads-table__recommendation" title="<?= e($recommendation) ?>" aria-label="<?= e($recommendation) ?>">
                    <?= e($recommendation) ?>
                  </span>
                </td>
                <td>
                  <span class="score-inline">
                    <strong class="score-inline__value"><?= e(dashboard_score_label($lead['score_transformation_global'] ?? null)) ?></strong>
                    <span class="score-inline__bar" aria-hidden="true"><span class="score-inline__fill" style="--score-width:<?= (int)($score ?? 0) ?>%"></span></span>
                  </span>
                </td>
                <td><span class="badge badge--<?= e($urgencyClass) ?>"><?= e(($lead['niveau_urgence'] ?? '') ?: 'Non qualifiée') ?></span></td>
                <td><span class="badge badge--<?= e($config['class']) ?>"><?= e($config['label']) ?></span></td>
                <td><a class="button button--secondary button--small" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>">Voir</a></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <span class="empty-state__icon"><?= dashboard_icon('users') ?></span>
          <strong>Aucun lead reçu pour le moment</strong>
          <p>Les prochains diagnostics apparaîtront ici.</p>
          <a class="button button--secondary button--small" href="/evaluer-mon-besoin-ia/">Voir le formulaire public</a>
        </div>
      <?php endif; ?>
    </section>

    <aside class="dashboard-card dashboard-priorities" aria-labelledby="dashboard-priorities-title">
      <div class="dashboard-card__header">
        <div>
          <h2 id="dashboard-priorities-title">Priorités du moment</h2>
          <p>Les actions commerciales à traiter en premier.</p>
        </div>
      </div>

      <?php if($priorities): ?>
        <div class="priority-list">
          <?php foreach($priorities as $lead): ?>
            <?php
              $status = $lead['statut'] ?? 'Nouveau';
              $config = $statusConfigs[$status] ?? array('class'=>'neutral', 'label'=>$status ?: 'Non qualifié');
              $badgeClass = $status === 'À relancer' ? 'warning' : ($status === 'Nouveau' ? 'new' : dashboard_urgency_class($lead['niveau_urgence'] ?? '', $lead['score_urgence'] ?? null));
            ?>
            <a class="priority-item" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>">
              <span class="priority-item__icon"><?= dashboard_icon($config['icon'] ?? 'alert') ?></span>
              <span class="priority-item__content">
                <strong class="priority-item__title"><?= e(dashboard_priority_title($lead)) ?></strong>
                <span class="priority-item__meta"><?= e(($lead['entreprise'] ?? '') ?: 'Sans entreprise') ?> · <?= e(dashboard_format_date($lead['date_relance'] ?? ($lead['created_at'] ?? ''), false)) ?></span>
                <span class="priority-item__offer"><?= e(dashboard_offer($lead)) ?></span>
              </span>
              <span class="priority-item__action badge badge--<?= e($badgeClass) ?>"><?= e(dashboard_priority_badge($lead)) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state empty-state--compact">
          <span class="empty-state__icon"><?= dashboard_icon('check') ?></span>
          <strong>Tout est à jour</strong>
          <p>Aucune action prioritaire n’est actuellement nécessaire.</p>
        </div>
      <?php endif; ?>
    </aside>
  </div>

  <?php if($contentStatus): ?>
    <section class="dashboard-content-status" aria-labelledby="dashboard-content-title">
      <div class="dashboard-section__header">
        <div>
          <h2 id="dashboard-content-title">Contenus et visibilité</h2>
          <p>Quelques repères fiables sur les contenus, le SEO et le sitemap.</p>
        </div>
      </div>
      <div class="content-status-grid">
        <?php foreach($contentStatus as $item): ?>
          <a class="content-status-card" href="<?= e($item['url']) ?>">
            <span class="content-status-card__icon"><?= dashboard_icon($item['icon']) ?></span>
            <span>
              <strong><?= e($item['value']) ?></strong>
              <span><?= e($item['label']) ?></span>
              <em><?= e($item['meta']) ?></em>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
<?php admin_footer(); ?>
