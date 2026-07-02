<?php
$current='about';
require __DIR__.'/includes/functions.php';
if (current_path() === '/cedrick-benittah.php') {
  header('Location: /cedrick-benittah/', true, 301);
  exit;
}
$meta = page_meta('about');
$meta['canonical'] = 'https://benittah.com/cedrick-benittah/';
require __DIR__.'/includes/header.php';
$cfg=site();
?>
<section class="page-hero">
  <div class="container">
    <div class="eyebrow">Cédrick Benittah</div>
    <h1>Cédrick Benittah, un profil hybride entre transformation, IA, technologie et performance.</h1>
    <p>J’accompagne les organisations qui veulent transformer leurs pratiques, intégrer l’IA et renforcer la performance collective avec une approche concrète, humaine et structurée.</p>
  </div>
</section>

<section class="section about-profile-section">
  <div class="container split">
    <div class="portrait-card">
      <img src="<?= e($cfg['portrait_url']) ?>" alt="<?= e($cfg['portrait_alt']) ?>">
    </div>
    <div class="content-panel">
      <div class="eyebrow">Vision</div>
      <h2>Relier la décision stratégique, les pratiques terrain et la posture humaine.</h2>
      <p>Mon parcours croise l’informatique, la gestion de projet, l’agilité, le DevOps, l’IA, le coaching professionnel et la préparation mentale. Cette combinaison me permet d’intervenir autant sur les modèles d’organisation que sur l’adoption concrète par les équipes.</p>
      <p>Une transformation réussie ne repose pas uniquement sur des méthodes. Elle exige un cap lisible, des arbitrages, des routines de pilotage, des leaders alignés et une capacité à apprendre rapidement.</p>
      <ul class="bullets">
        <li>Transformation organisationnelle et agile à l’échelle</li>
        <li>Adoption IA, automatisation et usages responsables</li>
        <li>Coaching de dirigeants, managers et équipes</li>
        <li>Culture DevOps, delivery, flux et pilotage par la valeur</li>
      </ul>
    </div>
  </div>
</section>

<section class="section soft-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Différence</div>
      <h2>Une approche exigeante, mais opérationnelle.</h2>
    </div>
    <div class="cards">
      <article class="card"><h3>Conseil</h3><p>Structurer les décisions, les priorités et la gouvernance de transformation.</p></article>
      <article class="card"><h3>Coaching</h3><p>Développer la posture des leaders et renforcer l’autonomie des équipes.</p></article>
      <article class="card"><h3>Facilitation</h3><p>Créer les espaces utiles pour aligner, arbitrer et faire émerger les solutions.</p></article>
      <article class="card"><h3>Technologie</h3><p>Faire de l’IA et du DevOps des leviers concrets de performance, pas des slogans.</p></article>
    </div>
  </div>
</section>

<section class="section credentials-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Diplômes & certifications</div>
      <h2>Des repères solides entre management, agilité, coaching et performance humaine.</h2>
      <p>Ces diplômes et certifications donnent un aperçu des fondations qui nourrissent mon approche : structurer la transformation, accompagner les équipes et relier performance opérationnelle et posture humaine.</p>
    </div>
    <div class="credentials-grid">
      <article class="credential-card">
        <a class="credential-thumb" href="/assets/img/certifications/master-2-mapi.jpg" target="_blank" rel="noopener" aria-label="Voir le diplôme Master 2 Management des projets innovants">
          <img src="/assets/img/certifications/master-2-mapi.jpg" alt="Diplôme Master 2 Management des projets innovants" loading="lazy" decoding="async">
        </a>
        <div class="credential-body">
          <span>Management & innovation</span>
          <h3>Master 2 Management des projets innovants</h3>
          <p>Université de Nice, management de projets innovants, pilotage et transformation.</p>
          <a class="link-orange" href="/assets/img/certifications/master-2-mapi.jpg" target="_blank" rel="noopener">Voir le justificatif →</a>
        </div>
      </article>
      <article class="credential-card">
        <a class="credential-thumb" href="/assets/img/certifications/safe-6-spc.png" target="_blank" rel="noopener" aria-label="Voir le certificat SAFe 6 Practice Consultant">
          <img src="/assets/img/certifications/safe-6-spc.png" alt="Certificat SAFe 6 Practice Consultant" loading="lazy" decoding="async">
        </a>
        <div class="credential-body">
          <span>Agilité à l’échelle</span>
          <h3>SAFe 6 Practice Consultant</h3>
          <p>Certification Scaled Agile pour accompagner les transformations agiles à l’échelle.</p>
          <a class="link-orange" href="/assets/img/certifications/safe-6-spc.png" target="_blank" rel="noopener">Voir le justificatif →</a>
        </div>
      </article>
      <article class="credential-card">
        <a class="credential-thumb" href="/assets/img/certifications/pspo-i.jpg" target="_blank" rel="noopener" aria-label="Voir le certificat Professional Scrum Product Owner I">
          <img src="/assets/img/certifications/pspo-i.jpg" alt="Certificat Professional Scrum Product Owner I" loading="lazy" decoding="async">
        </a>
        <div class="credential-body">
          <span>Produit & Scrum</span>
          <h3>Professional Scrum Product Owner I</h3>
          <p>Certification Scrum.org orientée valeur produit, priorisation et maximisation de l’impact.</p>
          <a class="link-orange" href="/assets/img/certifications/pspo-i.jpg" target="_blank" rel="noopener">Voir le justificatif →</a>
        </div>
      </article>
      <article class="credential-card">
        <a class="credential-thumb" href="/assets/img/certifications/coach.jpg" target="_blank" rel="noopener" aria-label="Voir le certificat de coach">
          <img src="/assets/img/certifications/coach.jpg" alt="Certificat Coach" loading="lazy" decoding="async">
        </a>
        <div class="credential-body">
          <span>Coaching</span>
          <h3>Formation Coach</h3>
          <p>Accompagnement des postures, de la prise de recul et de la performance individuelle ou collective.</p>
          <a class="link-orange" href="/assets/img/certifications/coach.jpg" target="_blank" rel="noopener">Voir le justificatif →</a>
        </div>
      </article>
      <article class="credential-card">
        <a class="credential-thumb" href="/assets/img/certifications/mental2pro.jpg" target="_blank" rel="noopener" aria-label="Voir l’attestation Mental2Pros">
          <img src="/assets/img/certifications/mental2pro.jpg" alt="Attestation Mental2Pros" loading="lazy" decoding="async">
        </a>
        <div class="credential-body">
          <span>Préparation mentale</span>
          <h3>Mental2Pros</h3>
          <p>Formation dédiée aux leviers de concentration, d’énergie, de stress et de performance durable.</p>
          <a class="link-orange" href="/assets/img/certifications/mental2pro.jpg" target="_blank" rel="noopener">Voir le justificatif →</a>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="section about-speaking-section">
  <div class="container">
    <div class="section-heading center">
      <div class="eyebrow">Prises de parole</div>
      <h2>Conférences, ateliers et réflexions publiques.</h2>
      <p>J’interviens pour éclairer les enjeux de transformation, d’adoption IA, d’agilité, de DevOps, de leadership et de performance collective.</p>
    </div>
    <div class="speaking-card-grid">
      <article class="speaking-card">
        <span>01</span>
        <h3>Conférences</h3>
        <p>Des interventions pour créer un langage commun, ouvrir la réflexion et mobiliser autour d’un cap clair.</p>
      </article>
      <article class="speaking-card">
        <span>02</span>
        <h3>Ateliers</h3>
        <p>Des formats courts pour faire travailler dirigeants, managers ou équipes sur un sujet concret.</p>
      </article>
      <article class="speaking-card">
        <span>03</span>
        <h3>Tables rondes</h3>
        <p>Des échanges autour de l’IA responsable, du leadership, du changement et des organisations apprenantes.</p>
      </article>
    </div>
  </div>
</section>

<section class="section linkedin-feed-section">
  <div class="container linkedin-feed-shell">
    <div class="section-heading center">
      <div class="eyebrow">Fil LinkedIn</div>
      <h2>Mes dernières publications LinkedIn.</h2>
      <p>Retrouvez mes prises de parole, partages d’expérience et réflexions récentes.</p>
    </div>
    <!-- Elfsight LinkedIn Feed | Cedrick Benittah LinkedIn Feed -->
    <script src="https://elfsightcdn.com/platform.js" async></script>
    <div class="elfsight-app-27942e4a-284f-422e-9e48-08e5e8da7f23" data-elfsight-app-lazy></div>
  </div>
</section>

<?php require __DIR__.'/includes/footer.php'; ?>
