<?php
$current='home';
require __DIR__.'/includes/header.php';
$cfg=site();
$arts=array_slice(articles(),0,3);
$diagnosticUrl='/diagnostic-adoption-ia-transformation/';
$governanceUrl='/gouvernance-ia-ia-act/';
$homeOffers=[
  [
    'icon'=>'01',
    'kicker'=>'Cadrage',
    'title'=>'Diagnostic Adoption IA & Transformation',
    'excerpt'=>'Clarifier les cas d’usage IA, les risques, les priorités et la feuille de route 30 / 60 / 90 jours.',
    'url'=>$diagnosticUrl
  ],
  [
    'icon'=>'02',
    'kicker'=>'Gouvernance',
    'title'=>'Gouvernance IA & IA Act',
    'excerpt'=>'Structurer un cadre d’usage responsable : AI literacy, cartographie, risques, règles internes et adoption.',
    'url'=>$governanceUrl
  ],
  [
    'icon'=>'03',
    'kicker'=>'Adoption terrain',
    'title'=>'Accompagnement Adoption IA',
    'excerpt'=>'Passer du diagnostic à l’expérimentation : ateliers métiers, priorisation, conduite du changement et ancrage.',
    'url'=>'/accompagnements/acculturation-ia/'
  ],
];
$situationCases=[
  [
    'title'=>'Direction métier',
    'problem'=>'Des initiatives IA dispersées, sans priorisation ni cadre commun.',
    'intervention'=>'Atelier de cadrage, cartographie des cas d’usage, matrice valeur / risque.',
    'result'=>'Une liste priorisée de cas d’usage, deux expérimentations identifiées et une roadmap 90 jours.'
  ],
  [
    'title'=>'DSI / transformation',
    'problem'=>'Des équipes intéressées par l’IA, mais des questions sur la sécurité, les données et les usages autorisés.',
    'intervention'=>'Sensibilisation, règles d’usage, clarification des risques et gouvernance opérationnelle.',
    'result'=>'Un cadre d’usage plus clair, des risques mieux identifiés et une adoption plus maîtrisée.'
  ],
  [
    'title'=>'Équipe projet / organisation',
    'problem'=>'Des outils IA testés individuellement, mais peu intégrés aux processus métiers.',
    'intervention'=>'Analyse des irritants, priorisation des gains rapides et accompagnement terrain.',
    'result'=>'Des cas d’usage concrets, reliés aux activités quotidiennes des équipes.'
  ],
];
$clientRefsFallback=[
  ['name'=>'Orange','logo'=>'/assets/img/logos/orange.png'],
  ['name'=>'Swiss Life Banque Privée','logo'=>'/assets/img/logos/swiss-life-banque-privee.png'],
  ['name'=>'BNP Paribas Cardif','logo'=>'/assets/img/logos/bnp-paribas-cardif.jpg'],
  ['name'=>'Mykelson Consulting','logo'=>'/assets/img/logos/mykelson-consulting.jpg'],
  ['name'=>'Abylsen','logo'=>'/assets/img/logos/abylsen.png'],
  ['name'=>'Wemanity','logo'=>'/assets/img/logos/wemanity.png'],
  ['name'=>'Groupe SII','logo'=>'/assets/img/logos/sii.png'],
  ['name'=>'Capgemini Altran','logo'=>'/assets/img/logos/altran.png'],
  ['name'=>'Cegid','logo'=>'/assets/img/logos/cegid.png'],
  ['name'=>'Davidson Consulting','logo'=>'/assets/img/logos/davidson.png'],
  ['name'=>'GE HealthCare','logo'=>'/assets/img/logos/ge-healthcare.png'],
  ['name'=>'Siemens','logo'=>'/assets/img/logos/siemens.jpg'],
  ['name'=>'Schneider Electric','logo'=>'/assets/img/logos/schneider-electric.png'],
  ['name'=>'Navya','logo'=>'/assets/img/logos/navya.jpg'],
  ['name'=>'HP','logo'=>'/assets/img/logos/hp.png'],
  ['name'=>'IBM','logo'=>'/assets/img/logos/ibm.png'],
  ['name'=>'Takara','logo'=>'/assets/img/logos/takara.jpg'],
  ['name'=>'Star Croisières','logo'=>'/assets/img/logos/star-croisieres.png'],
  ['name'=>'Compaq','logo'=>'/assets/img/logos/compaq.png'],
];
$clientRefs=array();
foreach(read_json('home_logos',$clientRefsFallback) as $client){
  if (!empty($client['enabled']) && !empty($client['name']) && !empty($client['logo'])) {
    $clientRefs[]=$client;
  }
}
if (!$clientRefs) { $clientRefs=$clientRefsFallback; }
?>
<section class="home-hero">
  <div class="container hero-shell">
    <div class="hero-copy">
      <div class="eyebrow">Consultant Adoption IA & Transformation</div>
      <h1>Consultant Adoption IA<br><span>& Transformation</span></h1>
      <p><?= e($cfg['hero_subtitle']) ?></p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="<?= e($diagnosticUrl) ?>"><?= e($cfg['hero_cta_primary']) ?></a>
        <a class="btn btn-outline" href="#methode-30-60-90"><?= e($cfg['hero_cta_secondary']) ?></a>
      </div>
      <div class="trust-line" aria-label="Domaines de crédibilité">
        <?php foreach(($cfg['trust_items']??[]) as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
      </div>
    </div>
    <div class="hero-visual" aria-label="Portrait de Cédrick Benittah">
      <div class="signature-card">
        <strong>Roadmap 30 / 60 / 90</strong>
        <span>Clarifier les usages IA. Cadrer les risques. Décider les priorités.</span>
      </div>
      <div class="portrait-frame">
        <img src="<?= e($cfg['portrait_url']) ?>" alt="<?= e($cfg['portrait_alt']) ?>">
      </div>
    </div>
  </div>
</section>

<section class="stats-section">
  <div class="container stats-card">
    <?php foreach(($cfg['stats']??[]) as $s): ?>
      <div class="stat-item">
        <strong><?= e($s['value']) ?></strong>
        <span><?= e($s['label']) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section trusted-clients-section" aria-labelledby="trusted-clients-title">
  <div class="container trusted-clients-inner">
    <div class="trusted-clients-heading">
      <div class="eyebrow">Références & environnements</div>
      <h2 id="trusted-clients-title">Ils m’ont fait confiance</h2>
      <div class="trusted-divider" aria-hidden="true"></div>
      <p>Une expérience terrain en transformation, delivery, agilité et coaching, aujourd’hui mise au service de l’adoption IA.</p>
    </div>
    <div class="client-logo-marquee" aria-label="Sociétés et environnements accompagnés">
      <div class="client-logo-track">
        <?php foreach([0,1] as $loop): ?>
          <div class="client-logo-row"<?= $loop===1 ? ' aria-hidden="true"' : '' ?>>
            <?php foreach($clientRefs as $client): ?>
              <div class="client-logo-card">
                <img src="<?= e($client['logo']) ?>" alt="<?= e($client['name']) ?>" loading="lazy" decoding="async">
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <p class="client-note">Références issues de missions, collaborations et environnements professionnels, sans présumer d’un partenariat en cours.</p>
  </div>
</section>

<section class="section intro-section">
  <div class="container split-large">
    <div>
      <div class="eyebrow">Positionnement</div>
      <h2>Passer de l’intention IA à une feuille de route concrète, responsable et adoptée.</h2>
    </div>
    <div class="lead-text">
      <p>J’aide les dirigeants, DSI, managers et équipes transformation à identifier les bons cas d’usage IA, cadrer les risques, prioriser les actions et construire une roadmap utile à 30, 60 et 90 jours.</p>
      <p>Le DevOps, l’agilité et le coaching restent des repères de terrain : ils servent ici à rendre l’adoption IA plus claire, plus opérationnelle et plus durable.</p>
    </div>
  </div>
</section>

<section class="section home-diagnostic-section">
  <div class="container diagnostic-feature">
    <div>
      <div class="eyebrow">Offre phare</div>
      <h2>Diagnostic Adoption IA & Transformation</h2>
      <p>Un format court pour identifier les cas d’usage IA pertinents, clarifier les risques humains, métiers et réglementaires, puis construire une feuille de route utile 30 / 60 / 90 jours.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="<?= e($diagnosticUrl) ?>">Demander un diagnostic IA</a>
        <a class="btn btn-outline" href="<?= e($governanceUrl) ?>">Structurer votre gouvernance IA</a>
      </div>
    </div>
    <div class="diagnostic-proof-grid" aria-label="Axes du diagnostic">
      <div><span>01</span><strong>Cas d’usage</strong><p>Prioriser les usages IA à vraie valeur métier.</p></div>
      <div><span>02</span><strong>Risques</strong><p>Clarifier données, conformité, adoption et vigilance managériale.</p></div>
      <div><span>03</span><strong>Équipes</strong><p>Créer les conditions d’appropriation sur le terrain.</p></div>
      <div><span>04</span><strong>Roadmap</strong><p>Décider quoi lancer à 30, 60 et 90 jours.</p></div>
    </div>
  </div>
</section>

<section class="section soft-section diagnostic-field-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Exemples anonymisés</div>
      <h2>Exemples de situations accompagnées</h2>
      <p>Des contextes types, formulés sans citer de client, pour rendre l’accompagnement plus concret.</p>
    </div>
    <div class="diagnostic-case-grid">
      <?php foreach($situationCases as $case): ?>
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

<section class="section challenge-section">
  <div class="container">
    <div class="section-heading">
      <div class="eyebrow">Enjeux dirigeants</div>
      <h2>Passer d’une adoption IA dispersée à une transformation pilotée.</h2>
    </div>
    <div class="challenge-grid">
      <article class="challenge-card">
        <span>01</span>
        <h3>Clarifier les usages</h3>
        <p>Distinguer les cas d’usage IA utiles des expérimentations séduisantes mais peu prioritaires.</p>
      </article>
      <article class="challenge-card">
        <span>02</span>
        <h3>Cadrer les risques</h3>
        <p>Rendre lisibles les enjeux données, conformité, sécurité, responsabilité et adoption terrain.</p>
      </article>
      <article class="challenge-card">
        <span>03</span>
        <h3>Décider les priorités</h3>
        <p>Construire une trajectoire 30 / 60 / 90 jours que les sponsors et les équipes peuvent réellement utiliser.</p>
      </article>
    </div>
  </div>
</section>

<section class="section services-home">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Offres</div>
      <h2>Trois portes d’entrée pour avancer sans disperser les sujets IA.</h2>
      <p>Chaque format répond à un problème clair : cadrer, gouverner ou accompagner l’adoption sur le terrain.</p>
    </div>
    <div class="service-row">
      <?php foreach($homeOffers as $s): ?>
        <a class="service-mini" href="<?= e($s['url']) ?>">
          <div class="suit-icon"><?= e($s['icon'] ?? '✦') ?></div>
          <div>
            <span><?= e($s['kicker'] ?? 'Accompagnement') ?></span>
            <h3><?= e($s['title']) ?></h3>
            <p><?= e($s['excerpt']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section method-section" id="methode-30-60-90">
  <div class="container method-shell">
    <div class="method-copy">
      <div class="eyebrow">Méthode</div>
      <h2>Une méthode 30 / 60 / 90 orientée décision.</h2>
      <p>Chaque intervention vise un effet utile : clarifier les cas d’usage, sécuriser les risques, embarquer les parties prenantes, puis transformer les décisions en actions priorisées.</p>
      <a class="link-orange" href="/cedrick-benittah/">Découvrir Cédrick Benittah →</a>
    </div>
    <div class="method-steps">
      <div><strong>Diagnostiquer</strong><span>Comprendre le contexte, les irritants et les contraintes réelles.</span></div>
      <div><strong>Prioriser</strong><span>Comparer valeur, faisabilité, risque et capacité d’adoption.</span></div>
      <div><strong>Expérimenter</strong><span>Choisir les usages à tester, les règles à poser et les dépendances à lever.</span></div>
      <div><strong>Ancrer</strong><span>Construire une roadmap 30 / 60 / 90 jours exploitable par les équipes.</span></div>
    </div>
  </div>
</section>

<section class="section articles-home">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Insights</div>
      <h2>Articles pour éclairer vos décisions</h2>
      <p>IA, transformation, gouvernance, adoption et performance collective : des contenus reliés aux enjeux de passage à l’action.</p>
    </div>
    <div class="compact-articles">
      <?php foreach($arts as $a): ?>
        <a class="compact-article" href="<?= e(article_url($a)) ?>">
          <img src="<?= e($a['image']) ?>" alt="<?= e($a['title']) ?>" width="1200" height="675" loading="lazy" decoding="async">
          <div>
            <span class="category"><?= e($a['category'] ?? 'Article') ?></span>
            <h3><?= e($a['title']) ?></h3>
            <span class="read-time"><?= e($a['read_time'] ?? '7 min') ?> de lecture</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="all-articles"><a href="/articles/">Voir tous les articles →</a></div>
  </div>
</section>

<section class="final-cta">
  <div class="container final-cta-card">
    <div>
      <div class="eyebrow">Premier échange</div>
      <h2>Vous voulez passer de l’intention IA à une feuille de route utile et maîtrisée ?</h2>
    </div>
    <a class="btn btn-primary" href="<?= e($diagnosticUrl) ?>">Demander un diagnostic IA</a>
  </div>
</section>
<!-- Elfsight WhatsApp Chat | Cedrick WhatsApp Chat -->
<script src="https://elfsightcdn.com/platform.js" async></script>
<div class="elfsight-app-9668fa2d-021c-4cd5-82c8-529473db8b3b" data-elfsight-app-lazy></div>
<?php require __DIR__.'/includes/footer.php'; ?>
