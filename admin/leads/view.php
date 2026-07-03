<?php
require __DIR__.'/../auth.php';
require_admin();
require_once __DIR__.'/../../includes/transformation_assessment.php';
require __DIR__.'/../_layout.php';

$id = (int)($_GET['id'] ?? 0);
try { $lead = db_fetch_one('SELECT * FROM leads_diagnostic_ia WHERE id = :id LIMIT 1', array(':id'=>$id)); }
catch (Throwable $e) { app_log('Lead view failed: ' . $e->getMessage()); $lead = null; }
if (!$lead) {
  admin_header('Lead introuvable','leads');
  ?><div class="admin-top"><div><h1>Lead introuvable</h1><p>La fiche demandée n’existe pas.</p></div><a class="btn btn-outline" href="/admin/leads/">Retour</a></div><?php
  admin_footer(); exit;
}

$rawAnswers = array();
if (!empty($lead['raw_answers_json'])) { $decoded = json_decode($lead['raw_answers_json'], true); if (is_array($decoded)) { $rawAnswers = $decoded; } }
$secondaries = array();
if (!empty($lead['recommandations_secondaires_json'])) { $decoded = json_decode($lead['recommandations_secondaires_json'], true); if (is_array($decoded)) { $secondaries = $decoded; } }
$nextSteps = array();
if (!empty($lead['prochaines_etapes_json'])) { $decoded = json_decode($lead['prochaines_etapes_json'], true); if (is_array($decoded)) { $nextSteps = $decoded; } }
$fullName = trim(($lead['prenom'] ?? '') . ' ' . ($lead['nom'] ?? ''));
if ($fullName === '') { $fullName = $lead['nom'] ?? 'Lead'; }

admin_header('Admin — Lead','leads');
$profileFields = array('created_at'=>'Date','source_page'=>'Page source','source_offre'=>'Offre source','prenom'=>'Prénom','nom'=>'Nom','entreprise'=>'Entreprise','email'=>'Email','telephone'=>'Téléphone','role_contact'=>'Fonction','taille_entreprise'=>'Taille','secteur_activite'=>'Secteur','localisation'=>'Localisation','chiffre_affaires'=>'Chiffre d’affaires','budget'=>'Budget','message'=>'Enjeu exprimé');
$trackingFields = array('utm_source'=>'UTM source','utm_medium'=>'UTM medium','utm_campaign'=>'UTM campaign','utm_content'=>'UTM content','utm_term'=>'UTM term','referrer_url'=>'Referrer','temps_completion_secondes'=>'Temps de complétion (s)');
$dimensionCards = array();
foreach (transformation_assessment_dimensions() as $key=>$dimension) { $dimensionCards[] = array('label'=>$dimension['label'],'score'=>$lead['score_'.$key] ?? '', 'level'=>$lead['niveau_'.$key] ?? ''); }
$scoreCards = array(
  array('label'=>'Score global','score'=>$lead['score_transformation_global'] ?? '', 'level'=>$lead['niveau_transformation'] ?? ''),
  array('label'=>'Risque','score'=>$lead['score_risque'] ?? '', 'level'=>$lead['niveau_risque'] ?? ''),
  array('label'=>'Création de valeur','score'=>$lead['score_creation_valeur'] ?? '', 'level'=>$lead['niveau_creation_valeur'] ?? ''),
  array('label'=>'Urgence','score'=>$lead['score_urgence'] ?? '', 'level'=>$lead['niveau_urgence'] ?? ''),
);
?>
<div class="admin-top">
  <div><h1><?= e($fullName) ?></h1><p><?= e(($lead['entreprise'] ?? '') ?: 'Sans entreprise') ?> · <?= e($lead['statut'] ?? '') ?></p></div>
  <a class="btn btn-outline" href="/admin/leads/">Retour aux leads</a>
</div>

<div class="admin-panel lead-recommendation-panel">
  <div class="admin-panel-head"><h2>Diagnostic Transformation 360°</h2></div>
  <div class="lead-offer-box">
    <strong><?= e($lead['recommandation_principale'] ?? ($lead['offre_recommandee'] ?? 'Non calculée')) ?></strong>
    <p><?= e($lead['explication_recommandation'] ?? '') ?></p>
    <?php if(!empty($lead['priorite_principale'])): ?><p><b>Priorité :</b> <?= e($lead['priorite_principale']) ?></p><?php endif; ?>
  </div>
  <div class="lead-score-grid">
    <?php foreach($scoreCards as $card): ?><div class="lead-score-card"><span><?= e($card['score'] !== '' ? $card['score'].'/100' : '—') ?></span><strong><?= e($card['level']) ?></strong><small><?= e($card['label']) ?></small></div><?php endforeach; ?>
  </div>
</div>

<div class="admin-panel">
  <h2>Scores par dimension</h2>
  <div class="lead-score-grid six">
    <?php foreach($dimensionCards as $card): ?><div class="lead-score-card"><span><?= e($card['score'] !== '' ? $card['score'].'/100' : '—') ?></span><strong><?= e($card['level']) ?></strong><small><?= e($card['label']) ?></small></div><?php endforeach; ?>
  </div>
</div>

<?php if($secondaries || $nextSteps): ?>
<div class="admin-panel">
  <h2>Recommandations et prochaines étapes</h2>
  <div class="lead-detail-grid">
    <?php if($secondaries): ?><div class="full-detail"><strong>Accompagnements complémentaires</strong><?php foreach($secondaries as $item): ?><span><?= e($item) ?></span><?php endforeach; ?></div><?php endif; ?>
    <?php if($nextSteps): ?><div class="full-detail"><strong>Prochaines étapes</strong><?php foreach($nextSteps as $item): ?><span><?= e($item) ?></span><?php endforeach; ?></div><?php endif; ?>
  </div>
</div>
<?php endif; ?>

<div class="admin-panel">
  <h2>Profil et organisation</h2>
  <div class="lead-detail-grid"><?php foreach($profileFields as $key=>$label): ?><div><strong><?= e($label) ?></strong><span><?= nl2br(e($lead[$key] ?? '')) ?></span></div><?php endforeach; ?></div>
</div>

<div class="admin-panel">
  <h2>Tracking et complétion</h2>
  <div class="lead-detail-grid"><?php foreach($trackingFields as $key=>$label): ?><div><strong><?= e($label) ?></strong><span><?= nl2br(e($lead[$key] ?? '')) ?></span></div><?php endforeach; ?></div>
</div>

<?php if($rawAnswers): ?>
<div class="admin-panel">
  <h2>Réponses détaillées</h2>
  <div class="lead-detail-grid">
    <?php foreach($rawAnswers as $group=>$value): ?><div class="full-detail"><strong><?= e(str_replace('_', ' ', $group)) ?></strong><?php if(is_array($value)): ?><?php foreach($value as $k=>$v): ?><span><b><?= e(str_replace('_', ' ', $k)) ?> :</b> <?= e(is_array($v) ? implode(', ', $v) : $v) ?></span><?php endforeach; ?><?php else: ?><span><?= e($value) ?></span><?php endif; ?></div><?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<form class="admin-panel form" method="post" action="/admin/leads/update.php">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$lead['id'] ?>">
  <div class="admin-form-grid">
    <label>Statut<select name="statut"><?php foreach(allowed_lead_statuses() as $status): ?><option value="<?= e($status) ?>" <?= ($lead['statut'] ?? '')===$status?'selected':'' ?>><?= e($status) ?></option><?php endforeach; ?></select></label>
    <label>Date de relance<input type="date" name="date_relance" value="<?= e($lead['date_relance'] ?? '') ?>"></label>
    <label class="full">Note interne<textarea name="note_interne"><?= e($lead['note_interne'] ?? '') ?></textarea></label>
  </div>
  <div class="cta-row" style="margin-top:18px"><button class="btn btn-primary" type="submit">Enregistrer</button></div>
</form>
<form class="admin-panel" method="post" action="/admin/leads/delete.php">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$lead['id'] ?>">
  <p>Suppression définitive du lead pour conformité RGPD.</p>
  <button class="mini-btn danger" data-confirm="Supprimer définitivement ce lead ?" type="submit">Supprimer le lead</button>
</form>
<?php admin_footer(); ?>
