<?php
require __DIR__.'/includes/functions.php';
$requestedSlug = isset($slug) ? $slug : (isset($_GET['slug']) ? $_GET['slug'] : '');
$a = find_article($requestedSlug);
if(!$a || (($a['status']??'published')==='draft')){
  http_response_code(404);
  $current='resources';
  $title='Article introuvable — Cédrick Benittah';
  require __DIR__.'/includes/header.php';
  ?><section class="page-hero"><div class="container"><h1>Article introuvable</h1><p>L’article demandé n’existe pas ou n’est pas publié.</p></div></section><?php
  require __DIR__.'/includes/footer.php';
  exit;
}
if (!isset($slug) && isset($_GET['slug']) && $_GET['slug'] !== '') {
  header('Location: ' . article_url($a), true, 301);
  exit;
}
$current='resources';
$title=$a['seo_title']??$a['title'];
$description=$a['seo_description']??$a['excerpt'];
$canonical=!empty($a['canonical_url']) ? $a['canonical_url'] : absolute_url(article_url($a));
$og=$a['image'] ?? '/assets/img/ui/og-image.svg';
$meta=array('robots'=>!empty($a['noindex']) ? 'noindex, follow' : 'index, follow');
$pillar=article_pillar($a);
$takeaways=article_takeaways($a);
$cta=article_cta($a);
$related=related_articles($a,3);
require __DIR__.'/includes/header.php';
?>
<section class="page-hero article-seo-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Fil d’Ariane"><a href="/">Accueil</a><span>›</span><a href="/articles/">Articles</a><span>›</span><span><?= e($a['title']) ?></span></nav>
    <div class="eyebrow"><?= e($pillar) ?> · <?= e($a['category']) ?></div>
    <h1><?= e($a['title']) ?></h1>
    <p><?= e($a['excerpt']) ?></p>
    <div class="article-meta"><?= e($a['author']??'Cédrick Benittah') ?> · <?= e($a['read_time']??'7 min') ?> de lecture · <?= e($a['date']??'') ?></div>
  </div>
</section>

<section class="section article-layout-section">
  <div class="container article-layout">
    <aside class="article-sidebar" aria-label="Navigation article">
      <div class="article-side-card">
        <div class="side-title">À retenir</div>
        <ul>
          <?php foreach($takeaways as $point): ?><li><?= e($point) ?></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="article-side-card side-cta">
        <div class="side-title">Offre liée</div>
        <strong><?= e($cta['title'] ?? 'Échangeons sur vos enjeux') ?></strong>
        <p><?= e($cta['text'] ?? '') ?></p>
        <a class="btn btn-primary" href="<?= e($cta['url'] ?? '/contact/') ?>"><?= e($cta['label'] ?? 'Planifier un rendez-vous') ?></a>
      </div>
    </aside>

    <main class="article-main">
      <div class="article-hero-img"><img src="<?= e($a['image']) ?>" alt="<?= e($a['title']) ?>" width="1200" height="675" decoding="async" fetchpriority="high"></div>
      <article class="article-content article-content-geo">
        <div class="article-intro-box">
          <span>Lecture stratégique</span>
          <p>Cet article s’inscrit dans le pilier <strong><?= e($pillar) ?></strong>. Il vise à éclairer une décision, structurer une action ou renforcer une posture dans un contexte de transformation.</p>
        </div>
        <?= render_article_content($a) ?>

        <div class="article-cta-box">
          <div>
            <div class="eyebrow">Passer à l’action</div>
            <h2><?= e($cta['title'] ?? 'Échangeons sur vos enjeux') ?></h2>
            <p><?= e($cta['text'] ?? '') ?></p>
          </div>
          <a class="btn btn-primary" href="<?= e($cta['url'] ?? '/contact/') ?>"><?= e($cta['label'] ?? 'Planifier un rendez-vous') ?></a>
        </div>
      </article>

      <section class="related-section" aria-label="Articles liés">
        <div class="section-heading">
          <div class="eyebrow">Maillage interne</div>
          <h2>Pour aller plus loin</h2>
          <p>Une sélection d’articles liés pour approfondir le sujet et relier les enjeux IA, transformation, agilité, DevOps et leadership.</p>
        </div>
        <div class="related-grid">
          <?php foreach($related as $r): ?>
            <a class="related-card" href="<?= e(article_url($r)) ?>">
              <img src="<?= e($r['image'] ?? '/assets/img/ui/og-image.svg') ?>" alt="<?= e($r['title'] ?? '') ?>" loading="lazy" decoding="async">
              <div><span><?= e(article_pillar($r)) ?></span><strong><?= e($r['title'] ?? '') ?></strong></div>
            </a>
          <?php endforeach; ?>
        </div>
      </section>

      <p class="article-back"><a class="btn btn-outline" href="/articles/">← Retour aux articles</a></p>
    </main>
  </div>
</section>
<script type="application/ld+json"><?= json_encode(article_schema_json($a), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode(article_breadcrumb_json($a), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>
<?php require __DIR__.'/includes/footer.php'; ?>
