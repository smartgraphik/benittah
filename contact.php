<?php
$current='contact';
require __DIR__.'/includes/functions.php';
if (current_path() === '/contact.php') {
  header('Location: /contact/', true, 301);
  exit;
}
$meta = page_meta('contact');
$meta['canonical'] = 'https://benittah.com/contact/';
$cfg=site();
require __DIR__.'/includes/header.php';
?>
<section class="page-hero contact-hero">
  <div class="container">
    <div class="eyebrow">Contact</div>
    <h1>Échangeons sur vos enjeux de transformation.</h1>
    <p>Un premier échange confidentiel pour comprendre votre contexte, identifier vos priorités et poser les premiers leviers d’action.</p>
  </div>
</section>

<section class="section calendly-section">
  <div class="container split">
    <div class="premium-box contact-intro">
      <h2>Un échange utile, direct et confidentiel.</h2>
      <ul class="bullets">
        <li>Comprendre votre contexte et vos contraintes</li>
        <li>Clarifier vos priorités de transformation</li>
        <li>Identifier les premiers leviers actionnables</li>
        <li>Évaluer le format d’accompagnement adapté</li>
      </ul>
      <p><strong>Email :</strong> <?= e($cfg['email'] ?? 'cedrick@benittah.com') ?></p>
      <p><strong>Zone :</strong> <?= e($cfg['address'] ?? 'France') ?></p>
      <div class="contact-actions">
        <a class="btn btn-primary" href="/contact/#calendly-widget">Prendre rendez-vous</a>
        <?php if(!empty($cfg['phone'])): ?>
          <a class="btn btn-outline" href="tel:<?= e(preg_replace('/[^\d+]/', '', $cfg['phone'])) ?>">Téléphoner</a>
        <?php endif; ?>
        <?php if(!empty($cfg['linkedin_url']) && $cfg['linkedin_url'] !== '#'): ?>
          <a class="contact-social-link" href="<?= e($cfg['linkedin_url']) ?>" target="_blank" rel="noopener">LinkedIn</a>
        <?php endif; ?>
        <?php if(!empty($cfg['malt_url'])): ?>
          <a class="contact-social-link" href="<?= e($cfg['malt_url']) ?>" target="_blank" rel="noopener">Malt</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="premium-box calendly-shell" id="calendly-widget">
      <!-- Début de widget en ligne Calendly -->
      <div class="calendly-inline-widget" data-url="https://calendly.com/cedrick-benittah/decouverte" style="min-width:320px;height:700px;"></div>
      <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
      <!-- Fin de widget en ligne Calendly -->
    </div>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
