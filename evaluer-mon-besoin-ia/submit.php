<?php
require __DIR__ . '/../includes/security.php';
require __DIR__ . '/../includes/diagnostic_scoring.php';
require __DIR__ . '/../includes/mailer.php';

start_app_session();

if (!request_method_is_post()) {
  redirect_to('/evaluer-mon-besoin-ia/');
}

$old = $_POST;
unset($old['csrf_token'], $old['website']);
$_SESSION['pre_diag_old'] = $old;

if (!empty($_POST['website'] ?? '')) {
  unset($_SESSION['pre_diag_old']);
  redirect_to('/merci-pre-diagnostic-ia/');
}

if (!csrf_is_valid($_POST['csrf_token'] ?? '')) {
  $_SESSION['pre_diag_errors'] = array('Le formulaire a expiré. Merci de réessayer.');
  redirect_to('/evaluer-mon-besoin-ia/');
}

$sourceOffre = offer_label_from_code(clean_text($_POST['source_offre_code'] ?? '', 30));
$answers = array(
  'taille_entreprise' => diagnostic_clean_choice($_POST['taille_entreprise'] ?? '', 'taille_entreprise'),
  'secteur_activite' => clean_text($_POST['secteur_activite'] ?? '', 190),
  'outils_ia' => diagnostic_clean_choice($_POST['outils_ia'] ?? '', 'outils_ia'),
  'equipes_ia' => diagnostic_clean_choice($_POST['equipes_ia'] ?? '', 'equipes_ia'),
  'politique_ia' => diagnostic_clean_choice($_POST['politique_ia'] ?? '', 'politique_ia'),
  'cas_usage' => diagnostic_clean_choice($_POST['cas_usage'] ?? '', 'cas_usage'),
  'automatisation' => diagnostic_clean_choice($_POST['automatisation'] ?? '', 'automatisation'),
  'donnees' => diagnostic_clean_choice($_POST['donnees'] ?? '', 'donnees'),
  'responsable_ia' => diagnostic_clean_choice($_POST['responsable_ia'] ?? '', 'responsable_ia'),
  'risques_ia' => diagnostic_clean_choice($_POST['risques_ia'] ?? '', 'risques_ia'),
  'ia_act' => diagnostic_clean_choice($_POST['ia_act'] ?? '', 'ia_act'),
  'confidentialite' => diagnostic_clean_choice($_POST['confidentialite'] ?? '', 'confidentialite'),
  'formation_ia' => diagnostic_clean_choice($_POST['formation_ia'] ?? '', 'formation_ia'),
  'objectifs_business' => diagnostic_clean_objectives($_POST['objectifs_business'] ?? array()),
  'urgence' => diagnostic_clean_choice($_POST['urgence'] ?? '', 'urgence'),
);
$publicAnswers = diagnostic_public_answers($answers);
$scoring = diagnostic_score_lead($answers);
$objectifLabels = diagnostic_objective_labels($answers['objectifs_business']);

$lead = array(
  'source_page' => clean_text($_POST['source_page'] ?? '/evaluer-mon-besoin-ia/', 255),
  'source_offre' => $sourceOffre,
  'prenom' => clean_text($_POST['prenom'] ?? '', 120),
  'nom' => clean_text($_POST['nom'] ?? '', 190),
  'entreprise' => clean_text($_POST['entreprise'] ?? '', 190),
  'email' => clean_text($_POST['email'] ?? '', 190),
  'telephone' => clean_text($_POST['telephone'] ?? '', 80),
  'role_contact' => clean_text($_POST['fonction'] ?? '', 120),
  'taille_entreprise' => $publicAnswers['taille_entreprise'],
  'secteur_activite' => $answers['secteur_activite'],
  'niveau_ia' => $publicAnswers['outils_ia'],
  'besoin_principal' => implode(', ', $objectifLabels),
  'perimetre' => $publicAnswers['taille_entreprise'],
  'horizon' => $publicAnswers['urgence'],
  'budget' => clean_text($_POST['budget'] ?? '', 190),
  'message' => clean_textarea($_POST['message'] ?? '', 5000),
  'consentement_rgpd' => !empty($_POST['consentement_rgpd']) ? 1 : 0,
  'ip_hash' => client_ip_hash(),
  'user_agent' => client_user_agent(),
);
$lead = array_merge($lead, $scoring);

$rawAnswers = array(
  'profil' => array(
    'prenom' => $lead['prenom'],
    'nom' => $lead['nom'],
    'email' => $lead['email'],
    'telephone' => $lead['telephone'],
    'entreprise' => $lead['entreprise'],
    'fonction' => $lead['role_contact'],
    'taille_entreprise' => $lead['taille_entreprise'],
    'secteur_activite' => $lead['secteur_activite'],
  ),
  'situation_actuelle' => array(
    'outils_ia' => $publicAnswers['outils_ia'],
    'equipes_ia' => $publicAnswers['equipes_ia'],
    'politique_ia' => $publicAnswers['politique_ia'],
    'cas_usage' => $publicAnswers['cas_usage'],
    'automatisation' => $publicAnswers['automatisation'],
    'donnees' => $publicAnswers['donnees'],
  ),
  'gouvernance_risques' => array(
    'responsable_ia' => $publicAnswers['responsable_ia'],
    'risques_ia' => $publicAnswers['risques_ia'],
    'ia_act' => $publicAnswers['ia_act'],
    'confidentialite' => $publicAnswers['confidentialite'],
    'formation_ia' => $publicAnswers['formation_ia'],
  ),
  'objectifs_business' => $objectifLabels,
  'urgence' => $publicAnswers['urgence'],
  'message' => $lead['message'],
);
$rawAnswersJson = json_encode($rawAnswers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($rawAnswersJson === false) { $rawAnswersJson = '{}'; }
$lead['raw_answers_json'] = $rawAnswersJson;

$errors = array();
if ($lead['prenom'] === '') { $errors[] = 'Le prénom est obligatoire.'; }
if ($lead['nom'] === '') { $errors[] = 'Le nom est obligatoire.'; }
if ($lead['email'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'Un email valide est obligatoire.'; }
if ($answers['outils_ia'] === '' || $answers['cas_usage'] === '') { $errors[] = 'Merci de renseigner votre situation IA actuelle.'; }
if ($answers['urgence'] === '') { $errors[] = 'Merci de préciser le niveau d’urgence.'; }
if (!$lead['consentement_rgpd']) { $errors[] = 'Le consentement RGPD est obligatoire.'; }

if ($errors) {
  $_SESSION['pre_diag_errors'] = $errors;
  redirect_to('/evaluer-mon-besoin-ia/' . (!empty($_POST['source_offre_code']) ? '?offre=' . rawurlencode($_POST['source_offre_code']) : ''));
}

try {
  $pdo = db_required();
  $stmt = $pdo->prepare('
    INSERT INTO leads_diagnostic_ia
      (source_page, source_offre, prenom, nom, entreprise, email, telephone, role_contact, taille_entreprise, secteur_activite, niveau_ia, besoin_principal, perimetre, horizon, budget, message, consentement_rgpd, ip_hash, user_agent, score_maturite_ia, score_gouvernance_risque, score_opportunite_business, score_urgence, niveau_maturite, niveau_risque, niveau_opportunite, niveau_urgence, offre_recommandee, explication_recommandation, raw_answers_json)
    VALUES
      (:source_page, :source_offre, :prenom, :nom, :entreprise, :email, :telephone, :role_contact, :taille_entreprise, :secteur_activite, :niveau_ia, :besoin_principal, :perimetre, :horizon, :budget, :message, :consentement_rgpd, :ip_hash, :user_agent, :score_maturite_ia, :score_gouvernance_risque, :score_opportunite_business, :score_urgence, :niveau_maturite, :niveau_risque, :niveau_opportunite, :niveau_urgence, :offre_recommandee, :explication_recommandation, :raw_answers_json)
  ');
  $stmt->execute(array(
    ':source_page' => $lead['source_page'],
    ':source_offre' => $lead['source_offre'],
    ':prenom' => $lead['prenom'],
    ':nom' => $lead['nom'],
    ':entreprise' => $lead['entreprise'] ?: null,
    ':email' => $lead['email'],
    ':telephone' => $lead['telephone'] ?: null,
    ':role_contact' => $lead['role_contact'] ?: null,
    ':taille_entreprise' => $lead['taille_entreprise'] ?: null,
    ':secteur_activite' => $lead['secteur_activite'] ?: null,
    ':niveau_ia' => $lead['niveau_ia'] ?: null,
    ':besoin_principal' => $lead['besoin_principal'] ?: null,
    ':perimetre' => $lead['perimetre'] ?: null,
    ':horizon' => $lead['horizon'] ?: null,
    ':budget' => $lead['budget'] ?: null,
    ':message' => $lead['message'] ?: null,
    ':consentement_rgpd' => $lead['consentement_rgpd'],
    ':ip_hash' => $lead['ip_hash'],
    ':user_agent' => $lead['user_agent'],
    ':score_maturite_ia' => $lead['score_maturite_ia'],
    ':score_gouvernance_risque' => $lead['score_gouvernance_risque'],
    ':score_opportunite_business' => $lead['score_opportunite_business'],
    ':score_urgence' => $lead['score_urgence'],
    ':niveau_maturite' => $lead['niveau_maturite'],
    ':niveau_risque' => $lead['niveau_risque'],
    ':niveau_opportunite' => $lead['niveau_opportunite'],
    ':niveau_urgence' => $lead['niveau_urgence'],
    ':offre_recommandee' => $lead['offre_recommandee'],
    ':explication_recommandation' => $lead['explication_recommandation'],
    ':raw_answers_json' => $lead['raw_answers_json'],
  ));
  $leadId = (int)$pdo->lastInsertId();
  send_lead_notification($leadId, $lead);
  $_SESSION['pre_diag_result'] = array(
    'lead_id' => $leadId,
    'prenom' => $lead['prenom'],
    'offre_recommandee' => $lead['offre_recommandee'],
    'explication_recommandation' => $lead['explication_recommandation'],
    'score_maturite_ia' => $lead['score_maturite_ia'],
    'score_gouvernance_risque' => $lead['score_gouvernance_risque'],
    'score_opportunite_business' => $lead['score_opportunite_business'],
    'score_urgence' => $lead['score_urgence'],
    'niveau_maturite' => $lead['niveau_maturite'],
    'niveau_risque' => $lead['niveau_risque'],
    'niveau_opportunite' => $lead['niveau_opportunite'],
    'niveau_urgence' => $lead['niveau_urgence'],
  );
  unset($_SESSION['pre_diag_old'], $_SESSION['pre_diag_errors']);
  redirect_to('/merci-pre-diagnostic-ia/');
} catch (Throwable $e) {
  app_log('Lead submit failed: ' . $e->getMessage());
  $_SESSION['pre_diag_errors'] = array('Votre demande ne peut pas être envoyée pour le moment. Vous pouvez planifier directement un échange.');
  redirect_to('/evaluer-mon-besoin-ia/' . (!empty($_POST['source_offre_code']) ? '?offre=' . rawurlencode($_POST['source_offre_code']) : ''));
}
