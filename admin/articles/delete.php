<?php
require __DIR__.'/../auth.php';
require_admin();
require_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  exit('Article invalide.');
}
try {
  db_execute('DELETE FROM articles WHERE id = :id', array(':id'=>$id));
} catch (Throwable $e) {
  app_log('Admin article delete failed: ' . $e->getMessage());
}
header('Location: /admin/articles/?deleted=1');
exit;

