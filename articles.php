<?php
$current = 'resources';
require __DIR__.'/includes/functions.php';
if (current_path() === '/articles.php') {
  $target = '/articles/';
  if (!empty($_SERVER['QUERY_STRING'])) { $target .= '?' . $_SERVER['QUERY_STRING']; }
  header('Location: ' . $target, true, 301);
  exit;
}
$meta = page_meta('resources');
$meta['canonical'] = 'https://benittah.com/articles/';
require __DIR__.'/includes/header.php';
$all = articles();
$cat = isset($_GET['category']) ? $_GET['category'] : '';
$pillarFilter = isset($_GET['pillar']) ? $_GET['pillar'] : '';
if ($cat) {
  $filtered = array();
  foreach ($all as $a) { if ((isset($a['category']) ? $a['category'] : '') === $cat) { $filtered[] = $a; } }
  $all = $filtered;
}
if ($pillarFilter) {
  $filtered = array();
  foreach ($all as $a) { if (article_pillar($a) === $pillarFilter) { $filtered[] = $a; } }
  $all = $filtered;
}
$categories = array();
$pillars = array();
foreach (articles() as $a) {
  $categories[] = isset($a['category']) ? $a['category'] : 'Autres';
  $pillars[] = article_pillar($a);
}
$categories = array_values(array_unique($categories)); sort($categories);
$pillars = array_values(array_unique($pillars)); sort($pillars);
$diagnosticUrl = '/evaluer-mon-besoin-ia/';
$diagnosticOfferUrl = '/diagnostic-adoption-ia-transformation/';
?>
<section class="page-hero articles-hero">
  <div class="container">
    <div class="eyebrow">Articles & insights</div>
    <h1>Une bibliothèque d’expertise pour transformer avec exigence.</h1>
    <p>Des contenus structurés pour éclairer l’adoption IA, la transformation, l’agilité, le DevOps, le leadership et la performance collective.</p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="<?= e($diagnosticUrl) ?>">Faire mon diagnostic 360°</a>
      <a class="btn btn-outline" href="/accompagnements/">Explorer les offres</a>
    </div>
  </div>
</section>
<section class="section"><div class="container">
  <div class="article-index-intro">
    <h2>Articles classés par piliers éditoriaux</h2>
    <p>Chaque article renforce un axe d’expertise et intègre un maillage interne vers les offres, le diagnostic 360° et les contenus associés.</p>
  </div>
  <div class="article-index-cta">
    <div>
      <div class="eyebrow">Besoin de passer à l’action ?</div>
      <h2>Le Diagnostic Transformation 360° transforme ces réflexions en feuille de route 30 / 60 / 90 jours.</h2>
    </div>
    <a class="btn btn-primary" href="<?= e($diagnosticOfferUrl) ?>">Découvrir l’offre</a>
  </div>
  <div class="filters pillar-filters">
    <a class="pill <?= $pillarFilter===''?'is-active':'' ?>" href="/articles/">Tous</a>
    <?php foreach($pillars as $p): ?><a class="pill <?= $pillarFilter===$p?'is-active':'' ?>" href="/articles/?pillar=<?= rawurlencode($p) ?>"><?= e($p) ?></a><?php endforeach; ?>
  </div>
  <div class="filters category-filters">
    <?php foreach($categories as $c): ?><a class="pill <?= $cat===$c?'is-active':'' ?>" href="/articles/?category=<?= rawurlencode($c) ?>"><?= e($c) ?></a><?php endforeach; ?>
  </div>
  <div class="article-list">
    <?php foreach($all as $a): $cardCta=article_cta($a); ?>
      <a class="article-card" href="<?= e(article_url($a)) ?>">
        <img src="<?= e(isset($a['image']) ? $a['image'] : '') ?>" alt="<?= e(isset($a['title']) ? $a['title'] : '') ?>" width="1200" height="675" loading="lazy" decoding="async">
        <div class="article-body">
          <div class="category"><?= e(article_pillar($a)) ?> · <?= e(isset($a['category']) ? $a['category'] : '') ?></div>
          <h3><?= e(isset($a['title']) ? $a['title'] : '') ?></h3>
          <p><?= e(isset($a['excerpt']) ? $a['excerpt'] : '') ?></p>
          <div class="article-meta"><?= e(isset($a['read_time']) ? $a['read_time'] : '7 min') ?> de lecture · <?= e(isset($a['date']) ? $a['date'] : '') ?></div>
          <div class="article-card-footer">
            <span>Lire l’analyse →</span>
            <small>Offre liée : <?= e($cardCta['label'] ?? 'Planifier un rendez-vous') ?></small>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <div class="article-bottom-cta">
    <div>
      <strong>Vous cherchez à prioriser vos cas d’usage IA ou vos chantiers de transformation ?</strong>
      <span>Un premier échange permet de clarifier le bon angle : diagnostic 360°, transformation, agilité, DevOps ou leadership.</span>
    </div>
    <a class="btn btn-outline" href="/contact/">Poser une question</a>
  </div>
</div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
