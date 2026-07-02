<?php
$current='diagnostic';
require __DIR__.'/../includes/security.php';
start_app_session();
$result = $_SESSION['pre_diag_result'] ?? null;
if (is_array($result)) {
  $result = array_merge(array(
    'prenom' => '',
    'offre_recommandee' => 'Diagnostic IA & Opportunités',
    'explication_recommandation' => '',
    'score_maturite_ia' => 0,
    'score_gouvernance_risque' => 0,
    'score_opportunite_business' => 0,
    'score_urgence' => 0,
    'niveau_maturite' => '',
    'niveau_risque' => '',
    'niveau_opportunite' => '',
    'niveau_urgence' => '',
  ), $result);
} else {
  $result = null;
}
$meta=getSeoMeta('/merci-pre-diagnostic-ia/');
$meta['canonical']='https://benittah.com/merci-pre-diagnostic-ia/';
$canonical=absolute_url('/merci-pre-diagnostic-ia/');
$calendlyCta='/contact/#calendly-widget';
require __DIR__.'/../includes/header.php';
?>
<section class="page-hero thank-you-hero">
  <div class="container">
    <div class="eyebrow">Pré-diagnostic IA</div>
    <h1>Merci pour votre demande.</h1>
    <?php if($result): ?>
      <p>Voici une première restitution de votre niveau de maturité IA. Elle ne remplace pas un échange de cadrage, mais permet déjà d’orienter la suite.</p>
    <?php else: ?>
      <p>J’ai bien reçu vos réponses si vous venez de soumettre le formulaire. Je reviens vers vous rapidement pour vous proposer le format d’accompagnement le plus adapté à votre contexte.</p>
    <?php endif; ?>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="<?= e($calendlyCta) ?>">Planifier directement un échange</a>
    </div>
  </div>
</section>

<?php if($result): ?>
<section class="section diagnostic-result-section">
  <div class="container">
    <div class="section-heading">
      <div class="eyebrow">Restitution immédiate</div>
      <h2><?= e($result['prenom'] ? 'Première lecture pour ' . $result['prenom'] : 'Première lecture de votre diagnostic') ?></h2>
      <p>Un retour personnalisé sera fait après analyse de vos réponses. Les scores ci-dessous donnent une lecture synthétique, volontairement simple.</p>
    </div>

    <div class="result-score-grid">
      <article class="premium-box result-score-card"><span><?= (int)$result['score_maturite_ia'] ?>/100</span><strong><?= e($result['niveau_maturite']) ?></strong><p>Maturité IA</p></article>
      <article class="premium-box result-score-card"><span><?= (int)$result['score_gouvernance_risque'] ?>/100</span><strong><?= e($result['niveau_risque']) ?></strong><p>Gouvernance / risque</p></article>
      <article class="premium-box result-score-card"><span><?= (int)$result['score_opportunite_business'] ?>/100</span><strong><?= e($result['niveau_opportunite']) ?></strong><p>Opportunité business</p></article>
      <article class="premium-box result-score-card"><span><?= (int)$result['score_urgence'] ?>/100</span><strong><?= e($result['niveau_urgence']) ?></strong><p>Urgence estimée</p></article>
    </div>

    <div class="premium-box result-recommendation">
      <div>
        <div class="eyebrow">Offre recommandée</div>
        <h2><?= e($result['offre_recommandee']) ?></h2>
        <p><?= e($result['explication_recommandation']) ?></p>
        <p>Vos réponses ont aussi été enregistrées dans le CRM afin de préparer un retour personnalisé et concret.</p>
      </div>
      <a class="btn btn-primary" href="<?= e($calendlyCta) ?>">Planifier un échange</a>
    </div>
  </div>
</section>
<?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
