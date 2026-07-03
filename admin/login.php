<?php
require_once __DIR__.'/../includes/functions.php';
require __DIR__.'/auth.php';

admin_no_store_headers();

if (is_admin()) {
  if (admin_session_expired_reason() !== '') {
    admin_destroy_session();
    header('Location: /admin/login.php?expired=1');
    exit;
  }
  header('Location: /admin/index.php');
  exit;
}

$error = '';
$notice = isset($_GET['expired']) ? 'Votre session a expiré. Merci de vous reconnecter.' : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_is_valid($_POST['csrf_token'] ?? '')) {
    $error = 'Identifiant ou mot de passe incorrect.';
  } elseif (!empty($_POST['website'] ?? '')) {
    $error = 'Identifiant ou mot de passe incorrect.';
  } else {
    $email = clean_text($_POST['email'] ?? '', 190);
    $password = (string)($_POST['password'] ?? '');
    $loginStatus = '';
    if (admin_login($email, $password, $loginStatus)) {
      header('Location: /admin/index.php');
      exit;
    }
    $error = $loginStatus === 'blocked'
      ? 'Trop de tentatives ont été détectées. Veuillez réessayer ultérieurement.'
      : 'Identifiant ou mot de passe incorrect.';
  }
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
    <?php if($notice): ?><div class="notice"><?= e($notice) ?></div><?php endif; ?>
    <?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
    <label class="hp-field" aria-hidden="true">Site web<input name="website" tabindex="-1" autocomplete="off"></label>
    <input name="email" type="email" placeholder="Email admin" required>
    <input name="password" type="password" placeholder="Mot de passe" required>
    <button class="btn btn-primary" type="submit">Se connecter</button>
  </form>
</body>
</html>
