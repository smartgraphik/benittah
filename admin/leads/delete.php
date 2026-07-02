<?php
require __DIR__.'/../auth.php';
require_admin();
require_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  exit('Lead invalide.');
}
try {
  db_execute('DELETE FROM leads_diagnostic_ia WHERE id = :id', array(':id'=>$id));
} catch (Throwable $e) {
  app_log('Lead delete failed: ' . $e->getMessage());
}
header('Location: /admin/leads/?deleted=1');
exit;

