<?php
$current='diagnostic';
require __DIR__.'/../includes/security.php';
require __DIR__.'/../includes/transformation_assessment.php';
start_app_session();
$result = $_SESSION['pre_diag_result'] ?? null;
if (is_array($result)) {
  $result = array_merge(array(
    'prenom'=>'','score_transformation_global'=>0,'niveau_transformation'=>'','score_risque'=>0,'niveau_risque'=>'','score_creation_valeur'=>0,'niveau_creation_valeur'=>'','score_urgence'=>0,'niveau_urgence'=>'','priorite_principale'=>'','recommandation_principale'=>'Diagnostic Transformation 360°','recommandations_secondaires'=>array(),'explication_recommandation'=>'','prochaines_etapes'=>array(),'constats'=>array(),
  ), $result);
} else { $result = null; }
$meta=getSeoMeta('/merci-pre-diagnostic-ia/');
$meta['meta_title']='Votre restitution Diagnostic Transformation 360°';
$meta['meta_description']='Première restitution de votre diagnostic de maturité de transformation.';
$meta['canonical']='https://benittah.com/merci-pre-diagnostic-ia/';
$meta['robots']='noindex, follow';
$canonical=absolute_url('/merci-pre-diagnostic-ia/');
$calendlyCta='/contact/#calendly-widget';
$dimensions = transformation_assessment_dimensions();
if (!headers_sent()) { header('X-Robots-Tag: noindex, follow', true); }
require __DIR__.'/../includes/header.php';
?>
<section class="page-hero thank-you-hero">
  <div class="container">
    <div class="eyebrow">Diagnostic Transformation 360°</div>
    <h1>Merci pour votre diagnostic.</h1>
    <?php if($result): ?>
      <p><?= e($result['prenom'] ? 'Voici une première lecture pour ' . $result['prenom'] . '.' : 'Voici une première lecture de votre situation.') ?> Elle donne une photographie indicative ; un échange permettra d’approfondir l’analyse et de qualifier les priorités.</p>
    <?php else: ?>
      <p>J’ai bien reçu vos réponses si vous venez de soumettre le formulaire. Je reviens vers vous rapidement pour vous proposer le format d’accompagnement le plus adapté.</p>
    <?php endif; ?>
    <div class="hero-actions offer-actions"><a class="btn btn-primary" href="<?= e($calendlyCta) ?>">Échanger sur mes résultats</a></div>
  </div>
</section>

<?php if($result): ?>
<section class="section diagnostic-result-section">
  <div class="container">
    <div class="section-heading">
      <div class="eyebrow">Restitution immédiate</div>
      <h2><?= e($result['niveau_transformation']) ?></h2>
      <p>Cette évaluation en ligne ne remplace pas le Diagnostic Transformation 360° complet : entretiens, analyse approfondie, revue des processus et feuille de route opérationnelle.</p>
    </div>

    <div class="result-score-grid result-score-grid-wide">
      <article class="premium-box result-score-card result-score-main"><span><?= (int)$result['score_transformation_global'] ?>/100</span><strong><?= e($result['niveau_transformation']) ?></strong><p>Score global de transformation</p></article>
      <article class="premium-box result-score-card"><span><?= (int)$result['score_risque'] ?>/100</span><strong><?= e($result['niveau_risque']) ?></strong><p>Niveau de risque</p></article>
      <article class="premium-box result-score-card"><span><?= (int)$result['score_creation_valeur'] ?>/100</span><strong><?= e($result['niveau_creation_valeur']) ?></strong><p>Création de valeur</p></article>
      <article class="premium-box result-score-card"><span><?= (int)$result['score_urgence'] ?>/100</span><strong><?= e($result['niveau_urgence']) ?></strong><p>Urgence estimée</p></article>
    </div>

    <div class="premium-box result-recommendation result-recommendation-stacked">
      <div>
        <div class="eyebrow">Recommandation principale</div>
        <h2><?= e($result['recommandation_principale']) ?></h2>
        <p><?= e($result['explication_recommandation']) ?></p>
        <?php if(!empty($result['priorite_principale'])): ?><p><strong>Priorité principale :</strong> <?= e($result['priorite_principale']) ?></p><?php endif; ?>
      </div>
      <div class="result-actions"><a class="btn btn-primary" href="<?= e($calendlyCta) ?>">Échanger sur mes résultats</a><a class="btn btn-outline" href="/diagnostic-adoption-ia-transformation/">Découvrir le Diagnostic Transformation 360°</a></div>
    </div>

    <div class="result-detail-grid">
      <div class="premium-box result-panel">
        <h3>Scores par dimension</h3>
        <?php foreach($dimensions as $key=>$dimension): $scoreKey='score_'.$key; $levelKey='niveau_'.$key; ?>
          <div class="score-bar-row"><div><strong><?= e($dimension['label']) ?></strong><span><?= e($result[$levelKey] ?? '') ?></span></div><meter min="0" max="100" value="<?= (int)($result[$scoreKey] ?? 0) ?>"></meter><b><?= (int)($result[$scoreKey] ?? 0) ?>/100</b></div>
        <?php endforeach; ?>
      </div>
      <div class="premium-box result-panel">
        <h3>Constats principaux</h3>
        <ul><?php foreach((array)$result['constats'] as $constat): ?><li><?= e($constat) ?></li><?php endforeach; ?></ul>
        <?php if(!empty($result['recommandations_secondaires'])): ?><h3>Compléments possibles</h3><ul><?php foreach((array)$result['recommandations_secondaires'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul><?php endif; ?>
      </div>
      <div class="premium-box result-panel full-result-panel">
        <h3>Trois prochaines étapes conseillées</h3>
        <ol><?php foreach((array)$result['prochaines_etapes'] as $step): ?><li><?= e($step) ?></li><?php endforeach; ?></ol>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
