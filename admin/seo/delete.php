<?php
require __DIR__.'/../auth.php';
require_admin();
require_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  exit('Entrée SEO invalide.');
}
try {
  db_execute('DELETE FROM seo_pages WHERE id = :id', array(':id'=>$id));
} catch (Throwable $e) {
  app_log('SEO delete failed: ' . $e->getMessage());
}
header('Location: /admin/seo/?deleted=1');
exit;

