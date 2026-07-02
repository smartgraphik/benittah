<?php
require __DIR__.'/auth.php';
require_admin();
require_csrf();
require_once __DIR__.'/../includes/functions.php';

$pages = array_values($_POST['pages'] ?? array());
if (write_json('pages_seo', $pages) === false) {
  http_response_code(500);
  exit('Impossible d’enregistrer le SEO.');
}

header('Location: /admin/seo.php?saved=1');
exit;