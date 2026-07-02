<?php
$current='services';
require __DIR__.'/includes/functions.php';
if (current_path() === '/accompagnements.php') {
  header('Location: /accompagnements/', true, 301);
  exit;
}
$meta = page_meta('services');
$meta['canonical'] = 'https://benittah.com/accompagnements/';
$frontOffers=[
  [
    'icon'=>'01',
    'kicker'=>'Cadrage',
    'title'=>'Diagnostic Adoption IA & Transformation',
    'benefit'=>'Clarifier les cas d’usage IA, les risques, les priorités et la feuille de route 30 / 60 / 90 jours.',
    'points'=>[
      'Entretiens ciblés avec les parties prenantes.',
      'Cartographie des opportunités, risques et irritants.',
      'Matrice valeur / faisabilité / risque.',
      'Roadmap 30 / 60 / 90 jours exploitable.'
    ],
    'cta'=>'Demander un diagnostic IA',
    'url'=>'/diagnostic-adoption-ia-transformation/'
  ],
  [
    'icon'=>'02',
    'kicker'=>'Responsable & conforme',
    'title'=>'Gouvernance IA & IA Act',
    'benefit'=>'Structurer un cadre d’usage responsable de l’IA : sensibilisation, AI literacy, cartographie, risques, règles internes et bonnes pratiques d’adoption.',
    'points'=>[
      'Cartographie des usages IA existants ou envisagés.',
      'Identification des risques métiers, humains, données et conformité.',
      'Règles internes d’usage de l’IA.',
      'Plan d’action et support de restitution.'
    ],
    'cta'=>'Structurer votre gouvernance IA',
    'url'=>'/gouvernance-ia-ia-act/'
  ],
  [
    'icon'=>'03',
    'kicker'=>'Adoption terrain',
    'title'=>'Accompagnement Adoption IA',
    'benefit'=>'Passer du diagnostic à l’expérimentation terrain : ateliers métiers, priorisation des cas d’usage, conduite du changement et adoption opérationnelle.',
    'points'=>[
      'Ateliers métiers orientés problèmes réels.',
      'Priorisation des cas d’usage à tester.',
      'Accompagnement des managers et équipes.',
      'Boucles de feedback pour ancrer les apprentissages.'
    ],
    'cta'=>'Construire l’adoption terrain',
    'url'=>'/accompagnements/acculturation-ia/'
  ],
];
require __DIR__.'/includes/header.php';
?>
<section class="page-hero offers-hero">
  <div class="container">
    <div class="eyebrow">Offres</div>
    <h1>Des interventions pour cadrer, gouverner et adopter l’IA.</h1>
    <p>Une offre resserrée autour de l’adoption IA, de la transformation et de la gouvernance responsable, sans disperser le message en catalogue d’expertises.</p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="/diagnostic-adoption-ia-transformation/">Demander un diagnostic IA</a>
      <a class="btn btn-outline" href="/gouvernance-ia-ia-act/">Gouvernance IA & IA Act</a>
    </div>
  </div>
</section>

<section class="section offers-feature-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Hiérarchie des accompagnements</div>
      <h2>Trois offres pour répondre à un problème clair.</h2>
      <p>Chaque carte part d’un besoin concret : savoir quoi faire, poser un cadre responsable ou accompagner l’adoption opérationnelle.</p>
    </div>
    <div class="service-list offers-grid">
      <?php foreach($frontOffers as $s): ?>
        <article class="premium-box offer-card">
          <div class="card-icon"><?= e($s['icon']) ?></div>
          <div class="eyebrow"><?= e($s['kicker']??'Accompagnement') ?></div>
          <h2><?= e($s['title']) ?></h2>
          <p><?= e($s['benefit']) ?></p>
          <div class="offer-card-columns">
            <div>
              <strong>Concret</strong>
              <ul class="bullets">
                <?php foreach($s['points'] as $point): ?><li><?= e($point) ?></li><?php endforeach; ?>
              </ul>
            </div>
          </div>
          <a class="btn btn-outline" href="<?= e($s['url']) ?>"><?= e($s['cta']) ?></a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section offers-method">
  <div class="container method-shell">
    <div class="method-copy">
      <div class="eyebrow">Principes d’intervention</div>
      <h2>Une adoption IA utile doit être lisible, appropriée et mesurable.</h2>
      <p>Mon expérience en transformation, agilité, DevOps et coaching sert ici un objectif unique : aider les organisations à passer de l’intention IA à des usages maîtrisés.</p>
    </div>
    <div class="method-steps">
      <div><strong>Clarifier</strong><span>Les problèmes métiers, les cas d’usage IA et les arbitrages à rendre.</span></div>
      <div><strong>Cadrer</strong><span>Les risques, données, responsabilités et règles d’usage nécessaires.</span></div>
      <div><strong>Prioriser</strong><span>Les expérimentations utiles selon valeur, faisabilité et risque.</span></div>
      <div><strong>Ancrer</strong><span>Les apprentissages, pratiques et décisions dans le quotidien des équipes.</span></div>
    </div>
  </div>
</section>

<section class="final-cta">
  <div class="container final-cta-card">
    <div>
      <div class="eyebrow">Premier cadrage</div>
      <h2>Vous voulez clarifier vos priorités IA sans multiplier les chantiers ?</h2>
    </div>
    <a class="btn btn-primary" href="/diagnostic-adoption-ia-transformation/">Demander un diagnostic IA</a>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
