<?php
require __DIR__.'/auth.php';
require_admin();
require_csrf();

$allowed = array('site', 'services');
$name = clean_text($_POST['name'] ?? '', 80);
if (!in_array($name, $allowed, true)) {
  http_response_code(400);
  exit('Fichier non autorisé');
}

$json = (string)($_POST['json'] ?? '');
$decoded = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
  exit('JSON invalide : ' . json_last_error_msg());
}

if (write_json($name, $decoded) === false) {
  http_response_code(500);
  exit('Impossible d’enregistrer le fichier.');
}

header('Location: /admin/' . $name . '.php?saved=1');
exit;