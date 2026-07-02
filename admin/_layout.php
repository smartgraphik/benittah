<?php
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/auth.php';

function admin_header($title, $active='dashboard') {
  $cfg=site();
  $admin=current_admin();
  ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/admin/assets/admin.css">
  <script defer src="/assets/js/app.js"></script>
</head>
<body>
<div class="admin-shell">
  <aside class="admin-side">
    <a class="brand" href="/"><img src="<?= e($cfg['logo_url']??'/assets/img/logo-full.png') ?>" alt=""></a>
    <div class="admin-badge"><i>CRM</i><div><strong>Administration</strong><br><span><?= e($admin['email'] ?? 'benittah.com') ?></span></div></div>
    <nav class="admin-menu">
      <a class="<?= $active==='dashboard'?'active':'' ?>" href="/admin/index.php">Tableau de bord</a>
      <a class="<?= $active==='leads'?'active':'' ?>" href="/admin/leads/">Leads CRM</a>
      <a class="<?= $active==='carrousel'?'active':'' ?>" href="/admin/carrousel/">Carroussel</a>
      <a class="<?= $active==='articles'?'active':'' ?>" href="/admin/articles/">Articles</a>
      <a class="<?= $active==='seo'?'active':'' ?>" href="/admin/seo/">SEO</a>
      <a class="<?= $active==='sitemap'?'active':'' ?>" href="/admin/sitemap/generate.php">Sitemap</a>
      <a href="/admin/logout.php">Déconnexion</a>
    </nav>
    <div class="admin-quote">V1 simple, robuste et maintenable.</div>
  </aside>
  <main class="admin-main">
  <?php
}

function admin_footer() {
  ?></main></div></body></html><?php
}
