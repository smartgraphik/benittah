<?php
$current='diagnostic';
require __DIR__.'/includes/security.php';
require __DIR__.'/includes/diagnostic_scoring.php';

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

$fonctionOptions = array('Dirigeant / Fondateur', 'DSI / Responsable IT', 'Responsable transformation', 'Direction métier', 'Manager', 'Consultant / Expert', 'Autre');
$budgetOptions = array('Je ne sais pas encore', 'Autour de 1 500 € HT', '3 500 à 5 000 € HT', '8 000 € HT et plus');

function selected_attr($old, $name, $value) {
  return (($old[$name] ?? '') === $value) ? ' selected' : '';
}
function checked_attr($old, $name, $value) {
  $values = $old[$name] ?? array();
  return is_array($values) && in_array($value, $values, true) ? ' checked' : '';
}

require __DIR__.'/includes/header.php';
?>
<section class="page-hero prediagnostic-hero">
  <div class="container">
    <div class="eyebrow">Pré-diagnostic IA</div>
    <h1>Évaluer votre maturité IA et vos priorités.</h1>
    <p>Répondez à quelques questions pour obtenir une première restitution, qualifier vos enjeux et identifier l’accompagnement le plus pertinent.</p>
  </div>
</section>

<section class="section prediagnostic-section">
  <div class="container split-large prediagnostic-layout">
    <form class="premium-box form prediagnostic-form" method="post" action="/evaluer-mon-besoin-ia/submit.php" novalidate>
      <div>
        <div class="eyebrow">Questionnaire</div>
        <h2>Un cadrage court pour prioriser vos enjeux IA.</h2>
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

      <div class="form-section">
        <h3>1. Profil de l’organisation</h3>
        <div class="prediagnostic-form-grid">
          <label>Prénom *<input name="prenom" value="<?= e($old['prenom'] ?? '') ?>" required></label>
          <label>Nom *<input name="nom" value="<?= e($old['nom'] ?? '') ?>" required></label>
          <label>Email *<input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required></label>
          <label>Téléphone<input name="telephone" value="<?= e($old['telephone'] ?? '') ?>"></label>
          <label>Entreprise<input name="entreprise" value="<?= e($old['entreprise'] ?? '') ?>"></label>
          <label>Fonction<select name="fonction"><option value="">Sélectionner</option><?php foreach($fonctionOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'fonction',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
          <label>Taille de l’entreprise<select name="taille_entreprise"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('taille_entreprise') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'taille_entreprise',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Secteur d’activité<input name="secteur_activite" value="<?= e($old['secteur_activite'] ?? '') ?>"></label>
        </div>
      </div>

      <div class="form-section">
        <h3>2. Situation actuelle</h3>
        <div class="prediagnostic-form-grid">
          <label>Utilisez-vous déjà des outils d’IA ?<select name="outils_ia"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('outils_ia') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'outils_ia',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Vos équipes utilisent-elles ChatGPT, Copilot, Gemini ou autres IA ?<select name="equipes_ia"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('equipes_ia') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'equipes_ia',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Avez-vous une politique interne d’usage de l’IA ?<select name="politique_ia"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('politique_ia') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'politique_ia',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Avez-vous identifié des cas d’usage IA ?<select name="cas_usage"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('cas_usage') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'cas_usage',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Avez-vous déjà automatisé certains processus ?<select name="automatisation"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('automatisation') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'automatisation',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Vos données sont-elles structurées et accessibles ?<select name="donnees"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('donnees') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'donnees',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
        </div>
      </div>

      <div class="form-section">
        <h3>3. Gouvernance et risques</h3>
        <div class="prediagnostic-form-grid">
          <label>Avez-vous une personne responsable des sujets IA ?<select name="responsable_ia"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('responsable_ia') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'responsable_ia',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Avez-vous évalué les risques liés à l’IA ?<select name="risques_ia"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('risques_ia') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'risques_ia',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Êtes-vous concerné par l’IA Act ?<select name="ia_act"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('ia_act') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'ia_act',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Avez-vous un cadre de confidentialité pour les données ?<select name="confidentialite"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('confidentialite') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'confidentialite',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label class="full">Vos collaborateurs sont-ils formés aux bons usages de l’IA ?<select name="formation_ia"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('formation_ia') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'formation_ia',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
        </div>
      </div>

      <div class="form-section">
        <h3>4. Objectifs business</h3>
        <div class="checkbox-grid">
          <?php foreach(diagnostic_objective_options() as $code=>$label): ?>
            <label class="choice-card"><input type="checkbox" name="objectifs_business[]" value="<?= e($code) ?>"<?= checked_attr($old,'objectifs_business',$code) ?>> <span><?= e($label) ?></span></label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-section">
        <h3>5. Niveau d’urgence</h3>
        <div class="prediagnostic-form-grid">
          <label>Niveau d’urgence<select name="urgence"><option value="">Sélectionner</option><?php foreach(diagnostic_choice_options('urgence') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'urgence',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
          <label>Budget envisagé<select name="budget"><option value="">Sélectionner</option><?php foreach($budgetOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'budget',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
          <label class="full">Message complémentaire<textarea name="message"><?= e($old['message'] ?? '') ?></textarea></label>
          <label class="full consent-field"><input type="checkbox" name="consentement_rgpd" value="1" <?= !empty($old['consentement_rgpd']) ? 'checked' : '' ?>> <span>J’accepte que mes informations soient utilisées uniquement pour être recontacté dans le cadre de ma demande.</span></label>
        </div>
      </div>

      <button class="btn btn-primary" type="submit">Obtenir ma restitution IA</button>
    </form>

    <aside class="premium-box prediagnostic-contact-card">
      <div class="eyebrow">Restitution</div>
      <h2>Une recommandation lisible, puis un retour personnalisé.</h2>
      <p>Après envoi, vous obtenez une première lecture de votre maturité IA, du niveau de risque, du potentiel business et de l’offre la plus adaptée.</p>
      <a class="btn btn-outline" href="<?= e($calendlyCta) ?>">Planifier un rendez-vous</a>
    </aside>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>