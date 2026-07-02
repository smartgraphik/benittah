<?php
header('Content-Type: text/html; charset=utf-8');
$phpVersion = PHP_VERSION;
$ok = version_compare($phpVersion, '8.0.0', '>=');
?><!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Diagnostic PHP 8 — benittah.com</title>
  <style>
    body{font-family:Arial,sans-serif;background:#f6f2ed;color:#17120f;margin:0;padding:40px;line-height:1.5}
    .card{max-width:760px;margin:auto;background:#fff;border:1px solid #eadfd3;border-radius:24px;padding:32px;box-shadow:0 20px 60px rgba(0,0,0,.08)}
    .ok{color:#116329}.ko{color:#a32020}.pill{display:inline-block;padding:8px 12px;border-radius:999px;background:#f2e6da;font-weight:700}
    code{background:#f5eee7;padding:2px 6px;border-radius:6px}
  </style>
</head>
<body>
  <div class="card">
    <p class="pill">Diagnostic serveur</p>
    <h1>Version PHP active</h1>
    <p>Version détectée : <strong><?php echo htmlspecialchars($phpVersion, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <?php if ($ok): ?>
      <p class="ok"><strong>OK :</strong> le serveur exécute PHP 8 ou supérieur.</p>
      <p>Tu peux maintenant tester <code>/</code>, <code>/articles/</code> et <code>/admin/login.php</code>.</p>
    <?php else: ?>
      <p class="ko"><strong>À corriger :</strong> le serveur n’exécute pas PHP 8. Active PHP 8 depuis le panneau IONOS.</p>
    <?php endif; ?>
    <p><strong>Sécurité :</strong> supprime ce fichier après diagnostic : <code>php8-check.php</code>.</p>
  </div>
</body>
</html>
