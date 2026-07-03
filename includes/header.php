<?php require_once __DIR__.'/functions.php'; $current=$current??''; $meta=$meta??page_meta($current); $cfg=site(); $title=$title??($meta['meta_title']??($cfg['brand']??'Cédrick Benittah')); $description=$description??($meta['meta_description']??'Transformation, IA et performance.'); $canonical=$canonical??($meta['canonical']??'https://benittah.com/'); $og=$og??($meta['og_image']??($cfg['default_og_image']??'/assets/img/ui/og-image.svg')); $og_url=preg_match('#^https?://#',(string)$og)?$og:absolute_url($og); ?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<?php if(!empty($meta['robots'])):?><meta name="robots" content="<?= e($meta['robots']) ?>"><?php endif; ?>
<?php if(!empty($meta['keywords'])):?><meta name="keywords" content="<?= e($meta['keywords']) ?>"><?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= e($og_url) ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="icon" href="/assets/img/ui/favicon.svg">
<link rel="stylesheet" href="/assets/css/style.css">
<script defer src="/assets/js/app.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="/" aria-label="Cédrick Benittah — accueil">
      <img src="<?= e($cfg['logo_url']??'/assets/img/logo-full.png') ?>" alt="Cédrick Benittah — Adoption IA & Transformation">
    </a>
    <nav class="main-nav" aria-label="Navigation principale">
      <a class="<?=active('home',$current)?>" href="/">Accueil</a>
      <a class="<?=active('diagnostic',$current)?>" href="/evaluer-mon-besoin-ia/">Votre diagnostic 360°</a>
      <a class="<?=active('services',$current)?>" href="/accompagnements/">Offres</a>
      <a class="<?=active('about',$current)?>" href="/cedrick-benittah/">Cédrick Benittah</a>
      <a class="<?=active('resources',$current)?>" href="/articles/">Articles</a>
      <a class="<?=active('contact',$current)?>" href="/contact/">Contact</a>
    </nav>
    <a class="btn btn-primary header-btn" href="/contact/">Planifier un RDV</a>
  </div>
</header>
<main>
