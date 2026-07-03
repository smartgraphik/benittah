<?php
$current='diagnostic';
require __DIR__.'/includes/functions.php';

if (current_path() === '/diagnostic-adoption-ia-transformation.php') {
  header('Location: /diagnostic-adoption-ia-transformation/', true, 301);
  exit;
}

$meta=page_meta('diagnostic');
$cfg=site();
$canonical=absolute_url('/diagnostic-adoption-ia-transformation/');
$diagnosticCta='/evaluer-mon-besoin-ia/';
$calendlyCta='/contact/#calendly-widget';

$audienceItems=array(
  'Clarifier les priorités de transformation.',
  'Identifier les bons cas d’usage IA et automatisation.',
  'Sécuriser les risques data, humains, organisationnels et réglementaires.',
  'Embarquer les équipes et les managers.',
  'Construire une roadmap claire avant d’investir.'
);

$methodSteps=array(
  array(
    'num'=>'1',
    'title'=>'Diagnostiquer',
    'text'=>'Comprendre les irritants métier, la vision, les usages actuels, les contraintes IT, les risques et les attentes des parties prenantes.'
  ),
  array(
    'num'=>'2',
    'title'=>'Prioriser',
    'text'=>'Identifier les leviers prioritaires selon leur valeur métier, leur faisabilité, leur niveau de risque et leur capacité d’adoption.'
  ),
  array(
    'num'=>'3',
    'title'=>'Structurer',
    'text'=>'Transformer les idées en actions concrètes : quick wins, expérimentations, sujets à sécuriser et chantiers à reporter.'
  ),
  array(
    'num'=>'4',
    'title'=>'Ancrer',
    'text'=>'Construire une roadmap 30 / 60 / 90 jours et préparer les conditions d’adoption par les équipes.'
  )
);

$deliverables=array(
  'Une synthèse des irritants métier.',
  'Une cartographie des priorités, risques, irritants et cas d’usage IA.',
  'Une matrice de priorisation.',
  'Une analyse des risques.',
  'Une roadmap 30 / 60 / 90 jours.',
  'Des recommandations opérationnelles pour lancer les premières actions.'
);

$formatItems=array(
  '2 à 3 entretiens ciblés avec les parties prenantes.',
  '1 atelier de priorisation des leviers de transformation.',
  '1 cartographie des opportunités, risques et irritants.',
  '1 matrice valeur / faisabilité / risque.',
  '1 restitution dirigeant, sponsor ou comité projet.',
  '1 roadmap 30 / 60 / 90 jours.',
  '1 livrable synthétique exploitable en interne.'
);

$fitItems=array(
  'Vous avez plusieurs chantiers de transformation sans feuille de route claire.',
  'Vous sentez une pression autour de l’IA, de l’automatisation ou de la performance mais ne savez pas quoi prioriser.',
  'Vos équipes sont curieuses, sollicitées, mais dispersées.',
  'Vous voulez cadrer les risques avant de généraliser.',
  'Vous cherchez une feuille de route concrète, pas une conférence inspirante.'
);

$notFitItems=array(
  'Vous cherchez uniquement une formation outil.',
  'Vous voulez automatiser sans cadrer les données et les usages.',
  'Vous attendez une solution magique sans implication métier.',
  'Vous ne souhaitez pas embarquer les équipes dans la démarche.'
);

$fieldCases=array(
  array(
    'title'=>'Direction métier',
    'problem'=>'Des initiatives IA dispersées, sans priorisation ni cadre commun.',
    'intervention'=>'Atelier de cadrage, cartographie des cas d’usage, matrice valeur / risque.',
    'result'=>'Une liste priorisée de cas d’usage, deux expérimentations identifiées et une roadmap 90 jours.'
  ),
  array(
    'title'=>'DSI / transformation',
    'problem'=>'Des équipes intéressées par l’IA, mais des interrogations sur la sécurité, les données et les usages autorisés.',
    'intervention'=>'Sensibilisation, règles d’usage, clarification des risques et gouvernance opérationnelle.',
    'result'=>'Un cadre d’usage plus clair, des risques mieux identifiés et une adoption plus maîtrisée.'
  ),
  array(
    'title'=>'Équipe projet / organisation',
    'problem'=>'Des outils IA testés individuellement, mais peu intégrés aux processus métiers.',
    'intervention'=>'Analyse des irritants, priorisation des gains rapides et accompagnement terrain.',
    'result'=>'Des cas d’usage concrets, reliés aux activités quotidiennes des équipes.'
  )
);

$formats=array(
  array(
    'code'=>'flash',
    'title'=>'Évaluation en ligne 360°',
    'price'=>'Gratuite',
    'text'=>'Une première photographie indicative pour mesurer votre maturité, repérer les vigilances et orienter le bon accompagnement.'
  ),
  array(
    'code'=>'adoption',
    'title'=>'Diagnostic Transformation 360°',
    'price'=>'Sur devis',
    'text'=>'Une offre complète avec entretiens ciblés, analyse des irritants, cartographie des risques et opportunités, restitution et roadmap priorisée.'
  ),
  array(
    'code'=>'90jours',
    'title'=>'Accompagnement 90 jours',
    'price'=>'Sur devis',
    'text'=>'Un accompagnement pour passer du diagnostic à l’action : cadrage des expérimentations, animation des ateliers, suivi des décisions et ancrage auprès des équipes.'
  )
);

$serviceSchema=array(
  '@context'=>'https://schema.org',
  '@type'=>'Service',
  'name'=>'Diagnostic Transformation 360°',
  'description'=>'Diagnostic pour évaluer la maturité de transformation, clarifier les priorités, sécuriser les risques et construire une roadmap opérationnelle 30 / 60 / 90 jours.',
  'provider'=>array('@type'=>'Person','name'=>'Cédrick Benittah','url'=>absolute_url('/cedrick-benittah/')),
  'areaServed'=>'France',
  'serviceType'=>'Conseil en transformation, gouvernance, adoption IA, automatisation et conduite du changement',
  'offers'=>array('@type'=>'Offer','priceSpecification'=>array('@type'=>'PriceSpecification','priceCurrency'=>'EUR','minPrice'=>'1500'),'url'=>$canonical),
  'url'=>$canonical
);

require __DIR__.'/includes/header.php';
?>
<script type="application/ld+json"><?= json_encode($serviceSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>

<section class="page-hero diagnostic-hero">
  <div class="container">
    <div class="eyebrow">Diagnostic Transformation 360°</div>
    <h1>Diagnostic Transformation 360°</h1>
    <p>Évaluez votre maturité de transformation, clarifiez vos priorités, sécurisez les risques et repartez avec une roadmap opérationnelle 30 / 60 / 90 jours.</p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="<?= e($diagnosticCta) ?>">Faire mon diagnostic 360°</a>
      <a class="btn btn-outline" href="<?= e($calendlyCta) ?>">Échanger sur mes enjeux</a>
    </div>
    <div class="diagnostic-hero-points" aria-label="Points clés du diagnostic">
      <span>Vision et priorités</span>
      <span>Organisation et exécution</span>
      <span>Risques clarifiés</span>
      <span>Roadmap 30 / 60 / 90</span>
    </div>
  </div>
</section>

<section class="section diagnostic-problem-section">
  <div class="container split-large">
    <div class="section-heading">
      <div class="eyebrow">Problème client</div>
      <h2>Votre transformation avance, mais les priorités restent difficiles à arbitrer ?</h2>
    </div>
    <div class="premium-box diagnostic-problem-card">
      <p>Les métiers identifient des opportunités, l’IT voit les risques, les managers doivent embarquer les équipes, mais les priorités ne sont pas toujours claires.</p>
      <p>Le Diagnostic Transformation 360° permet de remettre de la clarté, d’identifier les vrais leviers, de mesurer les risques et de construire une trajectoire réaliste.</p>
      <p>Lorsque le sujet porte surtout sur les règles d’usage, les responsabilités et l’IA Act, l’accompagnement <a class="link-orange" href="/gouvernance-ia-ia-act/">Gouvernance IA & IA Act</a> permet d’aller plus loin sur le cadre responsable.</p>
    </div>
  </div>
</section>

<section class="section soft-section diagnostic-audience-section">
  <div class="container diagnostic-feature">
    <div>
      <div class="eyebrow">Pour qui ?</div>
      <h2>Passer d’initiatives dispersées à une démarche structurée.</h2>
      <p>Ce diagnostic s’adresse aux dirigeants, DSI, responsables transformation, directions métiers et managers qui veulent relier stratégie, organisation, IA, gouvernance, adoption et performance opérationnelle.</p>
    </div>
    <div class="premium-box diagnostic-list-card">
      <ul class="bullets">
        <?php foreach($audienceItems as $item): ?>
          <li><?= e($item) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<section class="section diagnostic-format-section">
  <div class="container method-shell diagnostic-deliverables-shell">
    <div class="method-copy">
      <div class="eyebrow">Format du diagnostic</div>
      <h2>Un format court, concret et orienté décision</h2>
      <p>Le diagnostic est conçu pour produire une lecture claire de la situation et une trajectoire exploitable, sans mobiliser inutilement les équipes.</p>
    </div>
    <div class="method-steps diagnostic-deliverables-list">
      <?php foreach($formatItems as $item): ?>
        <div><strong><?= e($item) ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section soft-section diagnostic-fit-section">
  <div class="container split-large">
    <div class="premium-box diagnostic-list-card">
      <div class="eyebrow">Ce diagnostic est fait pour vous si...</div>
      <h2>Vous avez besoin de clarté avant d’investir.</h2>
      <ul class="bullets">
        <?php foreach($fitItems as $item): ?>
          <li><?= e($item) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="premium-box diagnostic-list-card">
      <div class="eyebrow">Ce diagnostic n’est pas fait pour vous si...</div>
      <h2>Vous cherchez une réponse purement outil ou magique.</h2>
      <ul class="bullets">
        <?php foreach($notFitItems as $item): ?>
          <li><?= e($item) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<section class="section diagnostic-method-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Ma méthode d’intervention</div>
      <h2>Une méthode en 4 temps</h2>
    </div>
    <div class="cards diagnostic-steps">
      <?php foreach($methodSteps as $step): ?>
        <article class="card">
          <div class="card-icon"><?= e($step['num']) ?></div>
          <h3><?= e($step['title']) ?></h3>
          <p><?= e($step['text']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section diagnostic-deliverables-section">
  <div class="container method-shell diagnostic-deliverables-shell">
    <div class="method-copy">
      <div class="eyebrow">Livrables</div>
      <h2>À la fin du diagnostic, vous repartez avec :</h2>
      <p>Une restitution claire, exploitable par les décideurs et directement reliée aux prochaines actions à engager.</p>
    </div>
    <div class="method-steps diagnostic-deliverables-list">
      <?php foreach($deliverables as $item): ?>
        <div><strong><?= e($item) ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section soft-section diagnostic-field-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Preuve terrain</div>
      <h2>Exemples de contextes accompagnés</h2>
      <p>Ces exemples sont volontairement anonymisés afin de préserver la confidentialité des environnements clients.</p>
    </div>
    <div class="diagnostic-case-grid">
      <?php foreach($fieldCases as $case): ?>
        <article class="card diagnostic-case-card">
          <h3><?= e($case['title']) ?></h3>
          <p><strong>Problème :</strong> <?= e($case['problem']) ?></p>
          <p><strong>Intervention :</strong> <?= e($case['intervention']) ?></p>
          <p><strong>Résultat :</strong> <?= e($case['result']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section diagnostic-formats-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Formats d’intervention</div>
      <h2>Trois formats selon votre niveau de maturité et votre horizon d’action.</h2>
    </div>
    <div class="diagnostic-format-grid">
      <?php foreach($formats as $format): ?>
        <article class="card diagnostic-format-card">
          <h3><?= e($format['title']) ?></h3>
          <strong><?= e($format['price']) ?></strong>
          <p><?= e($format['text']) ?></p>
          <a class="btn btn-primary" href="/evaluer-mon-besoin-ia/?offre=<?= e($format['code']) ?>">Faire mon diagnostic 360°</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="final-cta diagnostic-final-cta">
  <div class="container final-cta-card">
    <div>
      <div class="eyebrow">Diagnostic Transformation 360°</div>
      <h2>Vous souhaitez clarifier vos priorités de transformation ?</h2>
    </div>
    <div class="hero-actions">
      <a class="btn btn-primary" href="<?= e($diagnosticCta) ?>">Faire mon diagnostic 360°</a>
      <a class="btn btn-outline" href="/gouvernance-ia-ia-act/">Voir la gouvernance IA</a>
    </div>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
