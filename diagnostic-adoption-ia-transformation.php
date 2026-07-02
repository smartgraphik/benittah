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
$diagnosticCta='/contact/#calendly-widget';

$audienceItems=array(
  'Identifier les bons cas d’usage IA.',
  'Éviter les expérimentations inutiles.',
  'Sécuriser les risques data, humains et organisationnels.',
  'Embarquer les équipes.',
  'Construire une roadmap claire avant d’investir.'
);

$methodSteps=array(
  array(
    'num'=>'1',
    'title'=>'Diagnostiquer',
    'text'=>'Comprendre les irritants métier, les usages actuels, les contraintes IT, les risques et les attentes des parties prenantes.'
  ),
  array(
    'num'=>'2',
    'title'=>'Prioriser',
    'text'=>'Identifier les cas d’usage IA selon leur valeur métier, leur faisabilité, leur niveau de risque et leur capacité d’adoption.'
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
  'Une cartographie des cas d’usage IA.',
  'Une matrice de priorisation.',
  'Une analyse des risques.',
  'Une roadmap 30 / 60 / 90 jours.',
  'Des recommandations opérationnelles pour lancer les premières actions.'
);

$formatItems=array(
  '2 à 3 entretiens ciblés avec les parties prenantes.',
  '1 atelier de priorisation des cas d’usage IA.',
  '1 cartographie des opportunités, risques et irritants.',
  '1 matrice valeur / faisabilité / risque.',
  '1 restitution dirigeant, sponsor ou comité projet.',
  '1 roadmap 30 / 60 / 90 jours.',
  '1 livrable synthétique exploitable en interne.'
);

$fitItems=array(
  'Vous avez testé ChatGPT, Copilot ou d’autres outils IA sans vision claire.',
  'Vous sentez une pression autour de l’IA mais ne savez pas quoi prioriser.',
  'Vos équipes sont curieuses mais dispersées.',
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
    'title'=>'Diagnostic Flash IA',
    'price'=>'À partir de 1 500 € HT',
    'text'=>'Un format court pour identifier rapidement les premiers cas d’usage, clarifier les enjeux et produire une synthèse actionnable.'
  ),
  array(
    'code'=>'adoption',
    'title'=>'Diagnostic Adoption IA',
    'price'=>'Sur devis',
    'text'=>'Un format complet avec entretiens ciblés, analyse des irritants, cartographie des cas d’usage, lecture des risques et roadmap 30 / 60 / 90 jours.'
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
  'name'=>'Diagnostic Adoption IA & Transformation',
  'description'=>'Diagnostic pour clarifier les cas d’usage IA, sécuriser les risques et construire une roadmap opérationnelle 30 / 60 / 90 jours.',
  'provider'=>array('@type'=>'Person','name'=>'Cédrick Benittah','url'=>absolute_url('/cedrick-benittah/')),
  'areaServed'=>'France',
  'serviceType'=>'Conseil en transformation, adoption IA et conduite du changement',
  'offers'=>array('@type'=>'Offer','priceSpecification'=>array('@type'=>'PriceSpecification','priceCurrency'=>'EUR','minPrice'=>'1500'),'url'=>$canonical),
  'url'=>$canonical
);

require __DIR__.'/includes/header.php';
?>
<script type="application/ld+json"><?= json_encode($serviceSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>

<section class="page-hero diagnostic-hero">
  <div class="container">
    <div class="eyebrow">Diagnostic IA</div>
    <h1>Diagnostic Adoption IA & Transformation</h1>
    <p>Clarifiez vos cas d’usage IA, sécurisez les risques et repartez avec une roadmap opérationnelle 30 / 60 / 90 jours.</p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="<?= e($diagnosticCta) ?>">Planifier un diagnostic IA</a>
    </div>
    <div class="diagnostic-hero-points" aria-label="Points clés du diagnostic">
      <span>Cas d’usage utiles</span>
      <span>Risques clarifiés</span>
      <span>Priorités arbitrées</span>
      <span>Roadmap 30 / 60 / 90</span>
    </div>
  </div>
</section>

<section class="section diagnostic-problem-section">
  <div class="container split-large">
    <div class="section-heading">
      <div class="eyebrow">Problème client</div>
      <h2>Vous avez commencé à tester l’IA, mais les usages restent dispersés ?</h2>
    </div>
    <div class="premium-box diagnostic-problem-card">
      <p>Les métiers identifient des opportunités, l’IT voit les risques, les managers doivent embarquer les équipes, mais les priorités ne sont pas toujours claires.</p>
      <p>Le diagnostic Adoption IA & Transformation permet de remettre de la clarté, d’identifier les vrais cas d’usage, de mesurer les risques et de construire une trajectoire réaliste.</p>
      <p>Lorsque le sujet porte surtout sur les règles d’usage, les responsabilités et l’IA Act, l’accompagnement <a class="link-orange" href="/gouvernance-ia-ia-act/">Gouvernance IA & IA Act</a> permet d’aller plus loin sur le cadre responsable.</p>
    </div>
  </div>
</section>

<section class="section soft-section diagnostic-audience-section">
  <div class="container diagnostic-feature">
    <div>
      <div class="eyebrow">Pour qui ?</div>
      <h2>Passer d’une expérimentation IA dispersée à une démarche structurée.</h2>
      <p>Ce diagnostic s’adresse aux dirigeants, DSI, responsables transformation, directions métiers et managers qui veulent passer d’une expérimentation IA dispersée à une démarche structurée, utile et maîtrisée.</p>
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
      <h2>Vous avez besoin de clarté avant de généraliser l’IA.</h2>
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
          <a class="btn btn-primary" href="/evaluer-mon-besoin-ia/?offre=<?= e($format['code']) ?>">Démarrer le pré-diagnostic</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="final-cta diagnostic-final-cta">
  <div class="container final-cta-card">
    <div>
      <div class="eyebrow">Diagnostic IA</div>
      <h2>Vous souhaitez clarifier vos priorités IA ?</h2>
    </div>
    <div class="hero-actions">
      <a class="btn btn-primary" href="<?= e($diagnosticCta) ?>">Planifier un diagnostic IA</a>
      <a class="btn btn-outline" href="/gouvernance-ia-ia-act/">Voir la gouvernance IA</a>
    </div>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
