<?php
$current='diagnostic';
require __DIR__.'/includes/security.php';

if (current_path() === '/evaluer-mon-besoin-ia.php') {
  $target = '/evaluer-mon-besoin-ia/';
  if (!empty($_SERVER['QUERY_STRING'])) { $target .= '?' . $_SERVER['QUERY_STRING']; }
  header('Location: ' . $target, true, 301);
  exit;
}

start_app_session();

$meta=getSeoMeta('/evaluer-mon-besoin-ia/');
$meta['canonical']='https://benittah.com/evaluer-mon-besoin-ia/';
$canonical=absolute_url('/evaluer-mon-besoin-ia/');
$calendlyCta='/contact/#calendly-widget';

$errors = $_SESSION['pre_diag_errors'] ?? array();
$old = $_SESSION['pre_diag_old'] ?? array();
unset($_SESSION['pre_diag_errors'], $_SESSION['pre_diag_old']);

$offreCode = clean_text($_GET['offre'] ?? ($old['source_offre_code'] ?? ''), 30);
$offreLabel = offer_label_from_code($offreCode);

$roleOptions = array('Dirigeant / Fondateur', 'DSI / Responsable IT', 'Responsable transformation', 'Direction métier', 'Manager', 'Autre');
$niveauOptions = array('Découverte du sujet', 'Premiers tests', 'Usages dispersés sans cadre clair', 'Cas d’usage déjà identifiés', 'Volonté de passer à l’échelle');
$besoinOptions = array('Identifier les bons cas d’usage IA', 'Prioriser les opportunités', 'Sécuriser les risques', 'Embarquer les équipes', 'Construire une roadmap', 'Lancer les premiers pilotes');
$perimetreOptions = array('Une personne', 'Une équipe', 'Plusieurs équipes', 'Une direction métier', 'Toute l’organisation');
$horizonOptions = array('Immédiat', 'Dans le mois', 'Dans les 3 mois', 'Plus tard');
$budgetOptions = array('Autour de 1 500 € HT', '3 500 à 5 000 € HT', '8 000 € HT et plus', 'Je ne sais pas encore');

function selected_attr($old, $name, $value) {
  return (($old[$name] ?? '') === $value) ? ' selected' : '';
}

require __DIR__.'/includes/header.php';
?>
<section class="page-hero prediagnostic-hero">
  <div class="container">
    <div class="eyebrow">Pré-diagnostic IA</div>
    <h1>Évaluer votre besoin IA</h1>
    <p>Répondez à quelques questions pour clarifier votre contexte et identifier le format d’accompagnement le plus adapté : Diagnostic Flash IA, Diagnostic Adoption IA ou Accompagnement 90 jours.</p>
  </div>
</section>

<section class="section prediagnostic-section">
  <div class="container split-large prediagnostic-layout">
    <form class="premium-box form prediagnostic-form" method="post" action="/evaluer-mon-besoin-ia/submit.php" novalidate>
      <div>
        <div class="eyebrow">Questionnaire</div>
        <h2>Quelques informations pour cadrer votre demande.</h2>
        <?php if($offreLabel): ?><p class="form-note">Offre sélectionnée : <strong><?= e($offreLabel) ?></strong></p><?php endif; ?>
        <?php if($errors): ?>
          <div class="notice error">
            <?php foreach($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <?= csrf_field() ?>
      <input type="hidden" name="source_page" value="/evaluer-mon-besoin-ia/">
      <input type="hidden" name="source_offre_code" value="<?= e($offreCode) ?>">
      <label class="hp-field" aria-hidden="true">Site web<input name="website" tabindex="-1" autocomplete="off"></label>

      <div class="prediagnostic-form-grid">
        <label>Nom *<input name="nom" value="<?= e($old['nom'] ?? '') ?>" required></label>
        <label>Entreprise<input name="entreprise" value="<?= e($old['entreprise'] ?? '') ?>"></label>
        <label>Email *<input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required></label>
        <label>Téléphone<input name="telephone" value="<?= e($old['telephone'] ?? '') ?>"></label>
        <label>Votre rôle<select name="role_contact"><option value="">Sélectionner</option><?php foreach($roleOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'role_contact',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label>Où en êtes-vous avec l’IA ?<select name="niveau_ia"><option value="">Sélectionner</option><?php foreach($niveauOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'niveau_ia',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label>Besoin principal<select name="besoin_principal"><option value="">Sélectionner</option><?php foreach($besoinOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'besoin_principal',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label>Périmètre concerné<select name="perimetre"><option value="">Sélectionner</option><?php foreach($perimetreOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'perimetre',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label>Horizon souhaité<select name="horizon"><option value="">Sélectionner</option><?php foreach($horizonOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'horizon',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label>Budget envisagé<select name="budget"><option value="">Sélectionner</option><?php foreach($budgetOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'budget',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label class="full">Message complémentaire<textarea name="message"><?= e($old['message'] ?? '') ?></textarea></label>
        <label class="full consent-field"><input type="checkbox" name="consentement_rgpd" value="1" <?= !empty($old['consentement_rgpd']) ? 'checked' : '' ?>> <span>J’accepte que mes informations soient utilisées uniquement pour être recontacté dans le cadre de ma demande.</span></label>
      </div>

      <button class="btn btn-primary" type="submit">Envoyer ma demande</button>
    </form>

    <aside class="premium-box prediagnostic-contact-card">
      <div class="eyebrow">Échange direct</div>
      <h2>Vous préférez en parler directement ?</h2>
      <p>Planifiez un échange confidentiel pour clarifier votre contexte, vos priorités IA et le format d’accompagnement le plus pertinent.</p>
      <a class="btn btn-outline" href="<?= e($calendlyCta) ?>">Planifier un rendez-vous</a>
    </aside>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
