<?php
$current='privacy';
require __DIR__.'/includes/functions.php';
if (current_path() === '/politique-confidentialite.php') {
  header('Location: /politique-confidentialite/', true, 301);
  exit;
}
$meta = page_meta('privacy');
$meta['canonical'] = 'https://benittah.com/politique-confidentialite/';
$cfg = site();
$email = $cfg['email'] ?? 'cedrick@benittah.com';
require __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1>Politique de confidentialité</h1>
    <p>Traitement des données personnelles et confidentialité sur benittah.com.</p>
  </div>
</section>

<section class="section">
  <div class="container premium-box legal-content">
    <h2>Responsable du traitement</h2>
    <p>Le responsable du traitement des données personnelles collectées via ce site est Cédrick Benittah.</p>
    <p>Contact : <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>

    <h2>Données collectées via formulaire</h2>
    <p>Les formulaires du site peuvent collecter les informations transmises volontairement : prénom, nom, entreprise, adresse email, téléphone, fonction, taille d’entreprise, secteur d’activité, réponses au diagnostic IA, objectifs business, budget, message, consentement RGPD, scores de qualification et offre recommandée.</p>
    <p>Le formulaire de pré-diagnostic peut également enregistrer une empreinte d’adresse IP et le user-agent afin de sécuriser les demandes et limiter les abus.</p>

    <h2>Finalités</h2>
    <p>Ces données sont utilisées pour répondre aux demandes, préparer un échange, qualifier le besoin d’accompagnement IA ou transformation, assurer le suivi de la relation et sécuriser les formulaires.</p>

    <h2>Base légale</h2>
    <p>Les traitements reposent sur le consentement de l’utilisateur lorsqu’il soumet un formulaire, ainsi que sur l’intérêt légitime de l’éditeur à répondre aux demandes reçues et à sécuriser le site.</p>

    <h2>Durée de conservation</h2>
    <p>Les données sont conservées pendant la durée nécessaire au traitement de la demande et au suivi de la relation, puis supprimées ou anonymisées lorsqu’elles ne sont plus utiles, sauf obligation légale contraire.</p>
    <!-- TODO RGPD : confirmer la durée de conservation exacte retenue par l'éditeur, notamment pour les demandes entrantes et les leads de pré-diagnostic. -->

    <h2>Destinataires</h2>
    <p>Les données sont destinées à Cédrick Benittah et aux prestataires techniques strictement nécessaires au fonctionnement du site, de l’hébergement, de la messagerie, de la prise de rendez-vous ou des widgets intégrés.</p>
    <p>Aucune donnée personnelle n’est vendue à des tiers.</p>

    <h2>Droits de l’utilisateur</h2>
    <p>Conformément au RGPD, vous pouvez demander l’accès, la rectification, l’effacement, la limitation, l’opposition au traitement ou la portabilité de vos données lorsque ces droits s’appliquent.</p>
    <p>Vous pouvez également retirer votre consentement à tout moment pour les traitements fondés sur celui-ci.</p>

    <h2>Contact pour exercer vos droits</h2>
    <p>Pour exercer vos droits, écrivez à : <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>.</p>

    <h2>Cookies et services tiers</h2>
    <p>Aucun outil d’analytics de type Google Analytics, Matomo ou Pixel publicitaire n’a été identifié dans les fichiers du projet.</p>
    <p>Le site charge toutefois des services tiers visibles dans le code, notamment Calendly pour la prise de rendez-vous, Elfsight pour certains widgets de contact et Google Fonts pour les polices. Ces services peuvent déposer des cookies ou traiter des données selon leurs propres politiques de confidentialité.</p>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
