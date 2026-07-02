<?php
require __DIR__.'/auth.php';
require_admin();
require_csrf();
require_once __DIR__.'/../includes/functions.php';
$slug = isset($_POST['slug']) ? $_POST['slug'] : '';
$arts = read_json('articles', array());
$kept = array();
foreach ($arts as $a) {
  if ((isset($a['slug']) ? $a['slug'] : '') !== $slug) {
    $kept[] = $a;
  }
}
write_json('articles', $kept);
header('Location: /admin/articles.php?deleted=1');
exit;
?>
