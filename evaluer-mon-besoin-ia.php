<?php
$current='diagnostic';
require __DIR__.'/includes/security.php';
require __DIR__.'/includes/transformation_assessment.php';

if (current_path() === '/evaluer-mon-besoin-ia.php') {
  $target = '/evaluer-mon-besoin-ia/';
  if (!empty($_SERVER['QUERY_STRING'])) { $target .= '?' . $_SERVER['QUERY_STRING']; }
  header('Location: ' . $target, true, 301);
  exit;
}

start_app_session();

$meta=getSeoMeta('/evaluer-mon-besoin-ia/');
$meta['meta_title']='Diagnostic Transformation 360° — Cédrick Benittah';
$meta['meta_description']='Évaluez votre maturité de transformation à 360° : stratégie, organisation, IA, gouvernance, adoption, automatisation et capacité d’exécution.';
$meta['canonical']='https://benittah.com/evaluer-mon-besoin-ia/';
$canonical=absolute_url('/evaluer-mon-besoin-ia/');
$calendlyCta='/contact/#calendly-widget';

$errors = $_SESSION['pre_diag_errors'] ?? array();
$old = $_SESSION['pre_diag_old'] ?? array();
unset($_SESSION['pre_diag_errors'], $_SESSION['pre_diag_old']);

$fonctionOptions = array('Dirigeant / Fondateur','Direction générale','DSI / Responsable IT','Responsable transformation','Direction métier','Manager','Consultant / Expert','Autre');
$steps = transformation_step_config();
$questions = transformation_assessment_question_config();
$totalSteps = count($steps);

function selected_attr($old, $name, $value) { return (($old[$name] ?? '') === $value) ? ' selected' : ''; }
function checked_attr($old, $name, $value) { $values = $old[$name] ?? array(); return is_array($values) && in_array($value, $values, true) ? ' checked' : ''; }
function render_select_question($field, $old, $required = true) {
  $questions = transformation_assessment_question_config();
  $q = $questions[$field];
  echo '<label class="question-field">' . e($q['label']) . '<select name="' . e($field) . '"' . ($required ? ' required' : '') . '><option value="">Sélectionner</option>';
  foreach (transformation_choice_options($field) as $code => $label) { echo '<option value="' . e($code) . '"' . selected_attr($old, $field, $code) . '>' . e($label) . '</option>'; }
  echo '</select></label>';
}
function render_multi_question($field, $old) {
  $questions = transformation_assessment_question_config();
  $q = $questions[$field];
  echo '<fieldset class="question-field full" data-required-group="' . e($field) . '"><legend>' . e($q['label']) . '</legend><div class="checkbox-grid compact">';
  foreach (transformation_choice_options($field) as $code => $label) { echo '<label class="choice-card"><input type="checkbox" name="' . e($field) . '[]" value="' . e($code) . '"' . checked_attr($old, $field, $code) . '> <span>' . e($label) . '</span></label>'; }
  echo '</div><p class="field-error" aria-live="polite"></p></fieldset>';
}

require __DIR__.'/includes/header.php';
?>
<section class="page-hero prediagnostic-hero">
  <div class="container">
    <div class="eyebrow">Votre diagnostic 360°</div>
    <h1>Évaluez votre maturité de transformation à 360°</h1>
    <p>Identifiez vos forces, vos principaux points de vigilance et les leviers prioritaires pour accélérer durablement la transformation de votre organisation.</p>
  </div>
</section>

<section class="section prediagnostic-section">
  <div class="container split-large prediagnostic-layout">
    <form class="premium-box form prediagnostic-form assessment-form" method="post" action="/evaluer-mon-besoin-ia/submit.php" data-assessment-form novalidate>
      <div class="assessment-intro">
        <div class="eyebrow">Diagnostic Transformation 360°</div>
        <h2>Une première photographie gratuite, claire et exploitable.</h2>
        <p class="form-note">Durée indicative : 5 à 7 minutes. La prestation complète de conseil va plus loin : entretiens, analyse approfondie, feuille de route et recommandations opérationnelles.</p>
        <?php if($errors): ?><div class="notice error"><?php foreach($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?></div><?php endif; ?>
      </div>

      <div class="assessment-progress" aria-label="Progression du diagnostic">
        <div><span data-assessment-current>1</span> / <?= (int)$totalSteps ?></div>
        <progress max="<?= (int)$totalSteps ?>" value="1" data-assessment-progress></progress>
      </div>

      <?= csrf_field() ?>
      <input type="hidden" name="source_page" value="/evaluer-mon-besoin-ia/">
      <input type="hidden" name="source_offre" value="Diagnostic Transformation 360°">
      <input type="hidden" name="utm_source" value="<?= e($_GET['utm_source'] ?? ($old['utm_source'] ?? '')) ?>">
      <input type="hidden" name="utm_medium" value="<?= e($_GET['utm_medium'] ?? ($old['utm_medium'] ?? '')) ?>">
      <input type="hidden" name="utm_campaign" value="<?= e($_GET['utm_campaign'] ?? ($old['utm_campaign'] ?? '')) ?>">
      <input type="hidden" name="utm_content" value="<?= e($_GET['utm_content'] ?? ($old['utm_content'] ?? '')) ?>">
      <input type="hidden" name="utm_term" value="<?= e($_GET['utm_term'] ?? ($old['utm_term'] ?? '')) ?>">
      <input type="hidden" name="referrer_url" value="<?= e($old['referrer_url'] ?? '') ?>" data-referrer-field>
      <input type="hidden" name="form_started_at" value="<?= e($old['form_started_at'] ?? '') ?>" data-started-field>
      <input type="hidden" name="temps_completion_secondes" value="<?= e($old['temps_completion_secondes'] ?? '') ?>" data-completion-field>
      <label class="hp-field" aria-hidden="true">Site web<input name="website" tabindex="-1" autocomplete="off"></label>

      <section class="assessment-step" data-assessment-step data-step-title="Identité du prospect">
        <div class="form-section">
          <div class="step-kicker">Étape 1 / <?= (int)$totalSteps ?></div>
          <h3>Identité du prospect</h3>
          <p>Ces informations permettent de contextualiser le diagnostic et de préparer un retour utile.</p>
          <div class="prediagnostic-form-grid">
            <label>Prénom *<input name="prenom" value="<?= e($old['prenom'] ?? '') ?>" required></label>
            <label>Nom *<input name="nom" value="<?= e($old['nom'] ?? '') ?>" required></label>
            <label>Email professionnel *<input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required></label>
            <label>Téléphone *<input name="telephone" value="<?= e($old['telephone'] ?? '') ?>" required></label>
            <label>Entreprise *<input name="entreprise" value="<?= e($old['entreprise'] ?? '') ?>" required></label>
            <label>Fonction *<select name="fonction" required><option value="">Sélectionner</option><?php foreach($fonctionOptions as $option): ?><option value="<?= e($option) ?>"<?= selected_attr($old,'fonction',$option) ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
            <label>Secteur d’activité *<input name="secteur_activite" value="<?= e($old['secteur_activite'] ?? '') ?>" required></label>
            <label>Taille de l’organisation *<select name="taille_organisation" required><option value="">Sélectionner</option><?php foreach(transformation_choice_options('taille_organisation') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'taille_organisation',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
            <label>Localisation *<input name="localisation" value="<?= e($old['localisation'] ?? '') ?>" required></label>
            <label>Chiffre d’affaires indicatif<input name="chiffre_affaires" value="<?= e($old['chiffre_affaires'] ?? '') ?>"></label>
            <label class="full consent-field"><input type="checkbox" name="consentement_rgpd" value="1" <?= !empty($old['consentement_rgpd']) ? 'checked' : '' ?> required> <span>J’accepte que mes informations soient utilisées pour traiter ma demande, conformément à la <a href="/politique-confidentialite/">politique de confidentialité</a>.</span></label>
          </div>
        </div>
      </section>

      <?php foreach(array_slice($steps, 1, 6) as $idx=>$step): ?>
        <section class="assessment-step" data-assessment-step data-step-title="<?= e($step['title']) ?>" hidden>
          <div class="form-section">
            <div class="step-kicker">Étape <?= (int)($idx + 2) ?> / <?= (int)$totalSteps ?></div>
            <h3><?= e($step['title']) ?></h3>
            <p><?= e($step['intro']) ?></p>
            <div class="prediagnostic-form-grid">
              <?php foreach($step['fields'] as $field): ?>
                <?php (($questions[$field]['type'] ?? 'select') === 'multi') ? render_multi_question($field, $old) : render_select_question($field, $old); ?>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      <?php endforeach; ?>

      <section class="assessment-step" data-assessment-step data-step-title="Priorités et temporalité" hidden>
        <div class="form-section">
          <div class="step-kicker">Étape <?= (int)$totalSteps ?> / <?= (int)$totalSteps ?></div>
          <h3>Priorités et temporalité</h3>
          <p>Ces réponses orientent la recommandation commerciale et le niveau d’urgence.</p>
          <div class="prediagnostic-form-grid">
            <label>Quand souhaitez-vous engager une action ? *<select name="temporalite_action" required><option value="">Sélectionner</option><?php foreach(transformation_choice_options('temporalite_action') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'temporalite_action',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
            <label>Priorité principale *<select name="priorite_principale" required><option value="">Sélectionner</option><?php foreach(transformation_choice_options('priorite_principale') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'priorite_principale',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
            <label>Budget envisagé<select name="budget"><option value="">Sélectionner</option><?php foreach(transformation_choice_options('budget') as $code=>$label): ?><option value="<?= e($code) ?>"<?= selected_attr($old,'budget',$code) ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
            <fieldset class="question-field full" data-required-group="objectifs_business"><legend>Objectifs recherchés *</legend><div class="checkbox-grid compact"><?php foreach(transformation_objective_options() as $code=>$label): ?><label class="choice-card"><input type="checkbox" name="objectifs_business[]" value="<?= e($code) ?>"<?= checked_attr($old,'objectifs_business',$code) ?>> <span><?= e($label) ?></span></label><?php endforeach; ?></div><p class="field-error" aria-live="polite"></p></fieldset>
            <label class="full">Quel est aujourd’hui votre principal enjeu de transformation ?<textarea name="message"><?= e($old['message'] ?? '') ?></textarea></label>
          </div>
          <div class="assessment-summary" data-assessment-summary>
            <h4>Résumé avant envoi</h4>
            <p>Vérifiez rapidement vos réponses avant de soumettre le diagnostic.</p>
            <dl></dl>
          </div>
        </div>
      </section>

      <div class="assessment-nav">
        <button class="btn btn-outline" type="button" data-assessment-prev>Précédent</button>
        <button class="btn btn-primary" type="button" data-assessment-next>Suivant</button>
        <button class="btn btn-primary" type="submit" data-assessment-submit hidden>Faire mon diagnostic 360°</button>
      </div>
    </form>

    <aside class="premium-box prediagnostic-contact-card">
      <div class="eyebrow">Évaluation en ligne</div>
      <h2>Une première lecture, pas une mission de conseil complète.</h2>
      <p>Le formulaire gratuit donne une photographie indicative : scores, points de vigilance et recommandation d’accompagnement.</p>
      <p>Le Diagnostic Transformation 360° complet inclut entretiens, analyse approfondie, revue des processus, restitution personnalisée et feuille de route priorisée.</p>
      <a class="btn btn-outline" href="<?= e($calendlyCta) ?>">Prendre rendez-vous directement</a>
    </aside>
  </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
