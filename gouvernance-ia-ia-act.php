<?php
$current='services';
require __DIR__.'/includes/functions.php';

if (current_path() === '/gouvernance-ia-ia-act.php') {
  header('Location: /gouvernance-ia-ia-act/', true, 301);
  exit;
}

$meta=page_meta('governance');
$canonical=absolute_url('/gouvernance-ia-ia-act/');
$diagnosticUrl='/evaluer-mon-besoin-ia/';
$ctaUrl='/contact/#calendly-widget';

$issues=array(
  array('title'=>'Usages', 'text'=>'Savoir quels usages IA sont utiles, acceptables, autorisés et suffisamment maîtrisés.'),
  array('title'=>'Données', 'text'=>'Clarifier les informations manipulées, les niveaux de sensibilité et les précautions à poser.'),
  array('title'=>'Risques', 'text'=>'Identifier les risques métiers, humains, organisationnels, conformité et réputation.'),
  array('title'=>'Adoption', 'text'=>'Donner aux équipes un cadre simple pour utiliser l’IA sans freiner l’apprentissage terrain.')
);

$buildItems=array(
  'Un langage commun entre direction, DSI, métiers, transformation, data et conformité.',
  'Une cartographie des usages IA existants ou envisagés.',
  'Des règles internes compréhensibles par les équipes.',
  'Une matrice de priorisation reliant valeur, faisabilité, risque et adoption.',
  'Un plan d’action 30 / 60 / 90 jours pour passer du cadre à la mise en mouvement.'
);

$deliverables=array(
  'Cartographie des usages IA existants ou envisagés.',
  'Identification des risques métiers, humains, données et conformité.',
  'Règles internes d’usage de l’IA.',
  'Sensibilisation des équipes à l’AI literacy.',
  'Matrice de priorisation des cas d’usage.',
  'Plan d’action 30 / 60 / 90 jours.',
  'Support de restitution pour direction ou comité projet.'
);

$audiences=array(
  'Dirigeants et sponsors transformation qui veulent décider sans subir la pression IA.',
  'DSI, responsables data, innovation ou sécurité qui doivent cadrer les usages.',
  'Directions métiers qui veulent expérimenter avec un cadre clair.',
  'Managers et équipes transformation chargés de rendre l’adoption IA opérationnelle.'
);

$serviceSchema=array(
  '@context'=>'https://schema.org',
  '@type'=>'Service',
  'name'=>'Gouvernance IA & IA Act',
  'description'=>'Accompagnement pour structurer une gouvernance IA responsable : IA Act, AI literacy, cartographie des usages, risques, règles internes et adoption terrain.',
  'provider'=>array('@type'=>'Person','name'=>'Cédrick Benittah','url'=>absolute_url('/cedrick-benittah/')),
  'areaServed'=>'France',
  'serviceType'=>'Conseil en gouvernance IA, adoption IA et transformation',
  'url'=>$canonical
);

require __DIR__.'/includes/header.php';
?>
<script type="application/ld+json"><?= json_encode($serviceSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>

<section class="page-hero service-seo-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Fil d’Ariane"><a href="/">Accueil</a><span>›</span><a href="/accompagnements/">Offres</a><span>›</span><span>Gouvernance IA & IA Act</span></nav>
    <div class="eyebrow">Gouvernance IA</div>
    <h1>Gouvernance IA & IA Act</h1>
    <p>L’adoption de l’IA ne se limite pas au choix des outils. Elle nécessite un cadre clair : usages autorisés, risques, données, responsabilités, sensibilisation des équipes et gouvernance opérationnelle.</p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="<?= e($ctaUrl) ?>">Structurer votre gouvernance IA</a>
      <a class="btn btn-outline" href="<?= e($diagnosticUrl) ?>">Faire mon diagnostic 360°</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container split-large">
    <div class="section-heading">
      <div class="eyebrow">Pourquoi ?</div>
      <h2>Pourquoi structurer une gouvernance IA ?</h2>
    </div>
    <div class="premium-box">
      <p>Quand les équipes testent l’IA chacune de leur côté, les usages avancent plus vite que les règles, les responsabilités et la capacité de décision.</p>
      <p>Une gouvernance IA utile ne doit pas devenir un dispositif lourd. Elle doit rendre les bons usages plus simples, les risques plus visibles et les décisions plus partageables.</p>
    </div>
  </div>
</section>

<section class="section soft-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Enjeux</div>
      <h2>Usages, données, risques, conformité et adoption.</h2>
      <p>Le cadre doit aider les équipes à agir, pas seulement produire un document.</p>
    </div>
    <div class="cards diagnostic-steps">
      <?php foreach($issues as $item): ?>
        <article class="card">
          <h3><?= e($item['title']) ?></h3>
          <p><?= e($item['text']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container diagnostic-feature">
    <div>
      <div class="eyebrow">IA Act & AI literacy</div>
      <h2>Sensibiliser les équipes aux bons usages.</h2>
      <p>L’enjeu n’est pas seulement de connaître le cadre réglementaire. Les équipes doivent comprendre ce qu’elles peuvent faire, ce qu’elles doivent éviter, quand demander de l’aide et comment documenter les usages importants.</p>
      <p>La sensibilisation à l’AI literacy permet d’installer un langage commun et de réduire les zones grises avant de généraliser les usages.</p>
    </div>
    <div class="premium-box diagnostic-list-card">
      <ul class="bullets">
        <li>Repères simples sur les usages autorisés et les usages à cadrer.</li>
        <li>Bonnes pratiques sur les données, les prompts, les résultats et la validation humaine.</li>
        <li>Règles de responsabilité pour les métiers, managers, sponsors et fonctions support.</li>
        <li>Premiers rituels de gouvernance pour suivre les cas d’usage IA.</li>
      </ul>
    </div>
  </div>
</section>

<section class="section soft-section">
  <div class="container method-shell diagnostic-deliverables-shell">
    <div class="method-copy">
      <div class="eyebrow">Construction</div>
      <h2>Ce que je vous aide à construire</h2>
      <p>Un cadre pragmatique, adapté à votre maturité, pour relier conformité, priorisation et adoption terrain.</p>
    </div>
    <div class="method-steps diagnostic-deliverables-list">
      <?php foreach($buildItems as $item): ?>
        <div><strong><?= e($item) ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container method-shell diagnostic-deliverables-shell">
    <div class="method-copy">
      <div class="eyebrow">Livrables possibles</div>
      <h2>Des supports utilisables en interne.</h2>
      <p>Les livrables sont ajustés au contexte : taille de l’organisation, maturité IA, contraintes métiers et niveau de gouvernance déjà en place.</p>
    </div>
    <div class="method-steps diagnostic-deliverables-list">
      <?php foreach($deliverables as $item): ?>
        <div><strong><?= e($item) ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section soft-section">
  <div class="container diagnostic-feature">
    <div>
      <div class="eyebrow">Pour qui ?</div>
      <h2>Les organisations qui veulent avancer sans laisser les usages IA dériver.</h2>
      <p>Cet accompagnement s’adresse aux équipes qui veulent clarifier le cadre avant d’industrialiser, généraliser ou multiplier les expérimentations IA.</p>
    </div>
    <div class="premium-box diagnostic-list-card">
      <ul class="bullets">
        <?php foreach($audiences as $item): ?>
          <li><?= e($item) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<section class="final-cta">
  <div class="container final-cta-card">
    <div>
      <div class="eyebrow">Gouvernance IA</div>
      <h2>Vous voulez poser un cadre IA responsable, clair et adopté par les équipes ?</h2>
    </div>
    <div class="hero-actions">
      <a class="btn btn-primary" href="<?= e($ctaUrl) ?>">Structurer votre gouvernance IA</a>
      <a class="btn btn-outline" href="<?= e($diagnosticUrl) ?>">Commencer par un diagnostic</a>
    </div>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
