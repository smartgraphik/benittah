<?php
$current='diagnostic';
require __DIR__.'/../includes/security.php';
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
    <p>J’ai bien reçu vos réponses. Je reviens vers vous rapidement pour vous proposer le format d’accompagnement le plus adapté à votre contexte.</p>
    <div class="hero-actions offer-actions">
      <a class="btn btn-primary" href="<?= e($calendlyCta) ?>">Planifier directement un échange</a>
    </div>
  </div>
</section>
<?php require __DIR__.'/../includes/footer.php'; ?>
