<?php
// Sécurité de compatibilité : le site est prévu pour PHP 8.0 ou supérieur.
if (PHP_SAPI !== 'cli') {
  ini_set('display_errors', '0');
  ini_set('display_startup_errors', '0');
  ini_set('log_errors', '1');
  ini_set('html_errors', '0');
}

if (version_compare(PHP_VERSION, '8.0.0', '<')) {
  http_response_code(500);
  header('Content-Type: text/html; charset=utf-8');
  echo '<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>PHP 8 requis</title>';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1"></head><body style="font-family:Arial,sans-serif;padding:40px;line-height:1.5">';
  echo '<h1>PHP 8 requis</h1>';
  echo '<p>Ce site nécessite PHP 8.0 ou supérieur. Version actuellement détectée : <strong>' . htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8') . '</strong>.</p>';
  echo '<p>Active PHP 8 depuis le panneau d’administration de ton hébergement IONOS, puis recharge la page.</p>';
  echo '</body></html>';
  exit;
}
?>
