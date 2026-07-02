<?php
$current='legal';
require __DIR__.'/includes/functions.php';
if (current_path() === '/mentions-legales.php') {
  header('Location: /mentions-legales/', true, 301);
  exit;
}
$meta = page_meta('legal');
$meta['canonical'] = 'https://benittah.com/mentions-legales/';
$cfg = site();
$email = $cfg['email'] ?? 'cedrick@benittah.com';
$phone = $cfg['phone'] ?? '';
$address = $cfg['address'] ?? 'France';
require __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1>Mentions légales</h1>
    <p>Informations légales relatives au site benittah.com.</p>
  </div>
</section>

<section class="section">
  <div class="container premium-box legal-content">
    <h2>Éditeur du site</h2>
    <p>Le site benittah.com est édité par Cédrick Benittah.</p>
    <p>Activité présentée : conseil en adoption IA, transformation et gouvernance responsable.</p>

    <h2>Responsable de publication</h2>
    <p>Le responsable de publication est Cédrick Benittah.</p>

    <h2>Contact</h2>
    <p>Email : <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>
    <?php if ($phone): ?><p>Téléphone : <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $phone)) ?>"><?= e($phone) ?></a></p><?php endif; ?>
    <p>Zone d’intervention : <?= e($address) ?></p>

    <h2>Hébergeur</h2>
    <!-- TODO légal : renseigner le nom, l'adresse et les coordonnées de l'hébergeur exact dès confirmation par l'éditeur du site. -->
    <p>L’hébergeur exact n’est pas présent dans les fichiers du projet. Cette information doit être confirmée par l’éditeur du site.</p>

    <h2>Propriété intellectuelle</h2>
    <p>Les contenus, textes, visuels, éléments graphiques, logos et marques présents sur ce site sont protégés par le droit de la propriété intellectuelle. Toute reproduction, représentation, adaptation ou diffusion, totale ou partielle, nécessite une autorisation préalable.</p>
    <p>Crédits : <?= e($cfg['company_credit'] ?? 'Idée Cédrick Benittah · Réalisation Smartgraphik · SEO Popcorn-SEO') ?>.</p>

    <h2>Responsabilité</h2>
    <p>Les informations publiées sur ce site sont fournies à titre informatif. Malgré le soin apporté à leur mise à jour, elles ne constituent pas un engagement contractuel et peuvent évoluer.</p>
    <p>Le site peut contenir des liens vers des services tiers. Cédrick Benittah ne peut être tenu responsable du contenu, du fonctionnement ou des pratiques de confidentialité de ces sites externes.</p>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>