<?php
require __DIR__.'/includes/functions.php';
$requestedSlug = isset($slug) ? $slug : (isset($_GET['slug']) ? $_GET['slug'] : '');
$legacyServiceRedirects = array(
  'conseil-transformation' => '/diagnostic-adoption-ia-transformation/',
  'coaching-performance' => '/cedrick-benittah/',
  'conferences-ateliers' => '/accompagnements/',
  'devops-performance' => '/accompagnements/',
  'ateliers-formations' => '/accompagnements/acculturation-ia/'
);
if (isset($legacyServiceRedirects[$requestedSlug])) {
  header('Location: ' . $legacyServiceRedirects[$requestedSlug], true, 301);
  exit;
}
$s = find_service($requestedSlug);
if(!$s){
  http_response_code(404);
  $current='services';
  $title='Accompagnement introuvable — Cédrick Benittah';
  require __DIR__.'/includes/header.php';
  ?><section class="page-hero"><div class="container"><h1>Accompagnement introuvable</h1><p>La page demandée n’existe pas ou a été déplacée.</p></div></section><?php
  require __DIR__.'/includes/footer.php';
  exit;
}
if (!isset($slug) && isset($_GET['slug']) && $_GET['slug'] !== '') {
  header('Location: ' . service_url($s), true, 301);
  exit;
}
$current='services';
$title = (isset($s['seo_title']) && $s['seo_title']) ? $s['seo_title'] : $s['title'].' — Accompagnement adoption IA & transformation';
$description = (isset($s['seo_description']) && $s['seo_description']) ? $s['seo_description'] : $s['excerpt'];
$canonical = absolute_url(service_url($s));
$og = isset($s['image']) ? $s['image'] : '/assets/img/ui/og-image.svg';
require __DIR__.'/includes/header.php';
?>
<section class="page-hero service-seo-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Fil d’Ariane"><a href="/">Accueil</a><span>›</span><a href="/accompagnements/">Offres</a><span>›</span><span><?= e($s['title']) ?></span></nav>
    <div class="eyebrow"><?= e($s['kicker']??'Accompagnement') ?></div>
    <h1><?= e($s['title']) ?></h1>
    <p><?= e($s['excerpt']) ?></p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="/contact/">Planifier un rendez-vous</a>
      <a class="btn btn-outline" href="/diagnostic-adoption-ia-transformation/">Voir le diagnostic IA</a>
    </div>
  </div>
</section>
<section class="section">
  <div class="container service-detail">
    <aside class="premium-box service-sticky">
      <div class="card-icon"><?= e($s['icon']??'✦') ?></div>
      <h2 style="font-size:30px">Formats</h2>
      <ul class="bullets"><?php foreach(($s['formats']??[]) as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul>
      <a class="btn btn-primary" href="/contact/">Prendre rendez-vous</a>
      <a class="link-orange service-side-link" href="/diagnostic-adoption-ia-transformation/">Commencer par un diagnostic IA →</a>
    </aside>
    <div class="service-main premium-box">
      <h2>Pourquoi cet accompagnement</h2>
      <p>Cet accompagnement traite un enjeu précis de transformation avec une logique de clarté stratégique, d’alignement humain, d’adoption terrain et d’impact mesurable.</p>
      <h2>Problèmes traités</h2>
      <ul class="bullets"><?php foreach(($s['problems']??[]) as $p): ?><li><?= e($p) ?></li><?php endforeach; ?></ul>
      <h2>Résultats recherchés</h2>
      <ul class="bullets"><?php foreach(($s['outcomes']??[]) as $o): ?><li><?= e($o) ?></li><?php endforeach; ?></ul>
      <h2>Axes de travail</h2>
      <ul class="bullets"><?php foreach(($s['bullets']??[]) as $b): ?><li><?= e($b) ?></li><?php endforeach; ?></ul>
      <div class="service-diagnostic-link">
        <div>
          <strong>Vous ne savez pas encore quel levier prioriser ?</strong>
          <span>Le Diagnostic Adoption IA & Transformation permet de clarifier les irritants, les usages IA possibles, les risques et la feuille de route 30 / 60 / 90 jours.</span>
        </div>
        <a class="btn btn-outline" href="/diagnostic-adoption-ia-transformation/">Découvrir le diagnostic</a>
      </div>
      <div class="service-cta-inline">
        <strong>Besoin de cadrer ce sujet dans votre organisation ?</strong>
        <span>Un premier échange permet de clarifier le contexte, les objectifs et le niveau d’urgence.</span>
        <a class="link-orange" href="/contact/">Prendre rendez-vous →</a>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
