<?php
require __DIR__.'/../auth.php';
require_admin();
require __DIR__.'/../_layout.php';

$id = (int)($_GET['id'] ?? 0);
try {
  $lead = db_fetch_one('SELECT * FROM leads_diagnostic_ia WHERE id = :id LIMIT 1', array(':id'=>$id));
} catch (Throwable $e) {
  app_log('Lead view failed: ' . $e->getMessage());
  $lead = null;
}
if (!$lead) {
  admin_header('Lead introuvable','leads');
  ?><div class="admin-top"><div><h1>Lead introuvable</h1><p>La fiche demandée n’existe pas.</p></div><a class="btn btn-outline" href="/admin/leads/">Retour</a></div><?php
  admin_footer();
  exit;
}
admin_header('Admin — Lead','leads');
$fields = array(
  'created_at'=>'Date',
  'source_page'=>'Page source',
  'source_offre'=>'Offre',
  'nom'=>'Nom',
  'entreprise'=>'Entreprise',
  'email'=>'Email',
  'telephone'=>'Téléphone',
  'role_contact'=>'Rôle',
  'niveau_ia'=>'Niveau IA',
  'besoin_principal'=>'Besoin principal',
  'perimetre'=>'Périmètre',
  'horizon'=>'Horizon',
  'budget'=>'Budget',
  'message'=>'Message',
  'user_agent'=>'User agent',
);
?>
<div class="admin-top">
  <div><h1><?= e($lead['nom']) ?></h1><p><?= e($lead['entreprise'] ?: 'Sans entreprise') ?> · <?= e($lead['statut']) ?></p></div>
  <a class="btn btn-outline" href="/admin/leads/">Retour aux leads</a>
</div>
<div class="admin-panel">
  <h2>Réponses du formulaire</h2>
  <div class="lead-detail-grid">
    <?php foreach($fields as $key=>$label): ?>
      <div><strong><?= e($label) ?></strong><span><?= nl2br(e($lead[$key] ?? '')) ?></span></div>
    <?php endforeach; ?>
  </div>
</div>
<form class="admin-panel form" method="post" action="/admin/leads/update.php">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$lead['id'] ?>">
  <div class="admin-form-grid">
    <label>Statut<select name="statut"><?php foreach(allowed_lead_statuses() as $status): ?><option value="<?= e($status) ?>" <?= $lead['statut']===$status?'selected':'' ?>><?= e($status) ?></option><?php endforeach; ?></select></label>
    <label>Date de relance<input type="date" name="date_relance" value="<?= e($lead['date_relance'] ?? '') ?>"></label>
    <label class="full">Note interne<textarea name="note_interne"><?= e($lead['note_interne'] ?? '') ?></textarea></label>
  </div>
  <div class="cta-row" style="margin-top:18px"><button class="btn btn-primary" type="submit">Enregistrer</button></div>
</form>
<form class="admin-panel" method="post" action="/admin/leads/delete.php">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$lead['id'] ?>">
  <p>Suppression définitive du lead pour conformité RGPD.</p>
  <button class="mini-btn danger" data-confirm="Supprimer définitivement ce lead ?" type="submit">Supprimer le lead</button>
</form>
<?php admin_footer(); ?>

