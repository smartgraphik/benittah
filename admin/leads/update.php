<?php
require __DIR__.'/../auth.php';
require_admin();
require_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  exit('Lead invalide.');
}
$statut = require_valid_lead_status(clean_text($_POST['statut'] ?? 'Nouveau', 80));
$note = clean_textarea($_POST['note_interne'] ?? '', 5000);
$dateRelance = clean_text($_POST['date_relance'] ?? '', 10);
if ($dateRelance !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateRelance)) { $dateRelance = ''; }

try {
  db_execute('UPDATE leads_diagnostic_ia SET statut = :statut, note_interne = :note_interne, date_relance = :date_relance, updated_at = NOW() WHERE id = :id', array(
    ':statut'=>$statut,
    ':note_interne'=>$note ?: null,
    ':date_relance'=>$dateRelance ?: null,
    ':id'=>$id,
  ));
} catch (Throwable $e) {
  app_log('Lead update failed: ' . $e->getMessage());
}
header('Location: /admin/leads/view.php?id=' . $id . '&updated=1');
exit;

