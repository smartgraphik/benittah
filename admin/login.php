<?php
require_once __DIR__.'/../includes/functions.php';
require __DIR__.'/auth.php';

if (is_admin()) {
  header('Location: /admin/index.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_csrf();
  $email = clean_text($_POST['email'] ?? '', 190);
  $password = (string)($_POST['password'] ?? '');
  if (admin_login($email, $password)) {
    header('Location: /admin/index.php');
    exit;
  }
  $error = 'Identifiants incorrects ou base de données non configurée.';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administration — Connexion</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="login-page">
  <form class="login-card form" method="post">
    <?= csrf_field() ?>
    <img src="/assets/img/logo-full.png" alt="Cédrick Benittah">
    <h1>Administration</h1>
    <p>CRM, articles, SEO et sitemap du site.</p>
    <?php if(!db_configured()): ?><div class="notice">Renseignez d’abord <strong>config/config.local.php</strong> puis exécutez la migration SQL.</div><?php endif; ?>
    <?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
    <input name="email" type="email" placeholder="Email admin" required>
    <input name="password" type="password" placeholder="Mot de passe" required>
    <button class="btn btn-primary" type="submit">Se connecter</button>
  </form>
</body>
</html>

