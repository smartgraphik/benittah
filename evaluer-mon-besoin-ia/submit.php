<?php
require __DIR__ . '/../includes/security.php';
require __DIR__ . '/../includes/transformation_assessment.php';
require __DIR__ . '/../includes/mailer.php';

start_app_session();

if (!request_method_is_post()) { redirect_to('/evaluer-mon-besoin-ia/'); }

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

$answers = transformation_clean_answers($_POST);
$publicAnswers = transformation_public_answers($answers);
$assessment = transformation_assess($answers);
$objectifLabels = transformation_objective_labels($answers['objectifs_business']);
$secondaryJson = $assessment['recommandations_secondaires_json'] ?: '[]';
$nextStepsJson = $assessment['prochaines_etapes_json'] ?: '[]';

$completion = (int)($_POST['temps_completion_secondes'] ?? 0);
if ($completion <= 0 && !empty($_POST['form_started_at']) && ctype_digit((string)$_POST['form_started_at'])) {
  $started = (int)floor(((int)$_POST['form_started_at']) / 1000);
  if ($started > 0) { $completion = max(0, time() - $started); }
}
$completion = $completion > 0 ? min($completion, 86400) : null;

$lead = array(
  'source_page' => clean_text($_POST['source_page'] ?? '/evaluer-mon-besoin-ia/', 255),
  'source_offre' => clean_text($_POST['source_offre'] ?? 'Diagnostic Transformation 360°', 190),
  'prenom' => clean_text($_POST['prenom'] ?? '', 120),
  'nom' => clean_text($_POST['nom'] ?? '', 190),
  'entreprise' => clean_text($_POST['entreprise'] ?? '', 190),
  'email' => clean_text($_POST['email'] ?? '', 190),
  'telephone' => clean_text($_POST['telephone'] ?? '', 80),
  'role_contact' => clean_text($_POST['fonction'] ?? '', 120),
  'taille_entreprise' => $publicAnswers['taille_organisation'],
  'secteur_activite' => clean_text($_POST['secteur_activite'] ?? '', 190),
  'localisation' => clean_text($_POST['localisation'] ?? '', 190),
  'chiffre_affaires' => clean_text($_POST['chiffre_affaires'] ?? '', 190),
  'niveau_ia' => $publicAnswers['usages_ia'],
  'besoin_principal' => $assessment['priorite_principale'],
  'perimetre' => $publicAnswers['taille_organisation'],
  'horizon' => $publicAnswers['temporalite_action'],
  'budget' => $publicAnswers['budget'],
  'message' => clean_textarea($_POST['message'] ?? '', 5000),
  'consentement_rgpd' => !empty($_POST['consentement_rgpd']) ? 1 : 0,
  'ip_hash' => client_ip_hash(),
  'user_agent' => client_user_agent(),
  'utm_source' => clean_text($_POST['utm_source'] ?? '', 190),
  'utm_medium' => clean_text($_POST['utm_medium'] ?? '', 190),
  'utm_campaign' => clean_text($_POST['utm_campaign'] ?? '', 190),
  'utm_content' => clean_text($_POST['utm_content'] ?? '', 190),
  'utm_term' => clean_text($_POST['utm_term'] ?? '', 190),
  'referrer_url' => clean_text($_POST['referrer_url'] ?? '', 500),
  'temps_completion_secondes' => $completion,
);
$lead = array_merge($lead, $assessment);

$rawAnswers = array(
  'identite' => array(
    'prenom'=>$lead['prenom'],'nom'=>$lead['nom'],'email'=>$lead['email'],'telephone'=>$lead['telephone'],'entreprise'=>$lead['entreprise'],'fonction'=>$lead['role_contact'],'secteur_activite'=>$lead['secteur_activite'],'taille_organisation'=>$lead['taille_entreprise'],'localisation'=>$lead['localisation'],'chiffre_affaires'=>$lead['chiffre_affaires'],
  ),
  'strategie_leadership' => array(
    'vision_transformation'=>$publicAnswers['vision_transformation'],'priorites_comprises'=>$publicAnswers['priorites_comprises'],'sponsor_direction'=>$publicAnswers['sponsor_direction'],'mesure_resultats'=>$publicAnswers['mesure_resultats'],
  ),
  'organisation_agilite' => array(
    'autonomie_equipes'=>$publicAnswers['autonomie_equipes'],'collaboration_metier_tech'=>$publicAnswers['collaboration_metier_tech'],'decision_rapide'=>$publicAnswers['decision_rapide'],'livraison_reguliere'=>$publicAnswers['livraison_reguliere'],
  ),
  'maturite_ia' => array(
    'usages_ia'=>$publicAnswers['usages_ia'],'outils_ia'=>$publicAnswers['outils_ia'],'cas_usage_ia'=>$publicAnswers['cas_usage_ia'],'mesure_valeur_ia'=>$publicAnswers['mesure_valeur_ia'],
  ),
  'gouvernance_risques' => array(
    'comptes_personnels'=>$publicAnswers['comptes_personnels'],'donnees_confidentielles'=>$publicAnswers['donnees_confidentielles'],'politique_ia'=>$publicAnswers['politique_ia'],'cartographie_usages'=>$publicAnswers['cartographie_usages'],'responsabilites_obligations'=>$publicAnswers['responsabilites_obligations'],
  ),
  'adoption_competences' => array(
    'comprehension_objectifs'=>$publicAnswers['comprehension_objectifs'],'managers_outilles'=>$publicAnswers['managers_outilles'],'formation_collaborateurs'=>$publicAnswers['formation_collaborateurs'],'adoption_durable'=>$publicAnswers['adoption_durable'],
  ),
  'processus_automatisation' => array(
    'taches_repetitives'=>$publicAnswers['taches_repetitives'],'processus_manuels'=>$publicAnswers['processus_manuels'],'outils_connectes'=>$publicAnswers['outils_connectes'],'potentiel_agents'=>$publicAnswers['potentiel_agents'],
  ),
  'priorites' => array(
    'temporalite_action'=>$publicAnswers['temporalite_action'],'priorite_principale'=>$publicAnswers['priorite_principale'],'objectifs_business'=>$objectifLabels,'budget'=>$lead['budget'],'enjeu'=>$lead['message'],
  ),
);
$rawAnswersJson = json_encode($rawAnswers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($rawAnswersJson === false) { $rawAnswersJson = '{}'; }
$lead['raw_answers_json'] = $rawAnswersJson;

$errors = array();
if ($lead['prenom'] === '') { $errors[] = 'Le prénom est obligatoire.'; }
if ($lead['nom'] === '') { $errors[] = 'Le nom est obligatoire.'; }
if ($lead['email'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'Un email professionnel valide est obligatoire.'; }
if ($lead['telephone'] === '') { $errors[] = 'Le téléphone est obligatoire.'; }
if ($lead['entreprise'] === '') { $errors[] = 'L’entreprise est obligatoire.'; }
if ($lead['role_contact'] === '') { $errors[] = 'La fonction est obligatoire.'; }
if ($lead['secteur_activite'] === '') { $errors[] = 'Le secteur d’activité est obligatoire.'; }
if ($answers['taille_organisation'] === '') { $errors[] = 'Merci de renseigner la taille de l’organisation.'; }
if ($lead['localisation'] === '') { $errors[] = 'La localisation est obligatoire.'; }
foreach (transformation_assessment_question_config() as $field => $config) {
  if (($config['type'] ?? 'select') === 'multi') { if (empty($answers[$field])) { $errors[] = 'Merci de répondre à la question : ' . $config['label']; } }
  elseif (($answers[$field] ?? '') === '') { $errors[] = 'Merci de répondre à la question : ' . $config['label']; }
}
if ($answers['temporalite_action'] === '') { $errors[] = 'Merci de préciser la temporalité.'; }
if ($answers['priorite_principale'] === '') { $errors[] = 'Merci de choisir la priorité principale.'; }
if (empty($answers['objectifs_business'])) { $errors[] = 'Merci de sélectionner au moins un objectif.'; }
if (!$lead['consentement_rgpd']) { $errors[] = 'Le consentement à la politique de confidentialité est obligatoire.'; }

if ($errors) {
  $_SESSION['pre_diag_errors'] = array_values(array_unique($errors));
  redirect_to('/evaluer-mon-besoin-ia/');
}

try {
  $pdo = db_required();
  $stmt = $pdo->prepare('
    INSERT INTO leads_diagnostic_ia
      (source_page, source_offre, prenom, nom, entreprise, email, telephone, role_contact, taille_entreprise, secteur_activite, localisation, chiffre_affaires, niveau_ia, besoin_principal, perimetre, horizon, budget, message, consentement_rgpd, ip_hash, user_agent, score_strategie_leadership, score_organisation_agilite, score_maturite_ia, score_gouvernance, score_adoption, score_automatisation, score_transformation_global, score_risque, score_creation_valeur, score_gouvernance_risque, score_opportunite_business, score_urgence, niveau_maturite, niveau_transformation, niveau_risque, niveau_creation_valeur, niveau_opportunite, niveau_urgence, offre_recommandee, priorite_principale, recommandation_principale, recommandations_secondaires_json, explication_recommandation, prochaines_etapes_json, raw_answers_json, utm_source, utm_medium, utm_campaign, utm_content, utm_term, referrer_url, temps_completion_secondes)
    VALUES
      (:source_page, :source_offre, :prenom, :nom, :entreprise, :email, :telephone, :role_contact, :taille_entreprise, :secteur_activite, :localisation, :chiffre_affaires, :niveau_ia, :besoin_principal, :perimetre, :horizon, :budget, :message, :consentement_rgpd, :ip_hash, :user_agent, :score_strategie_leadership, :score_organisation_agilite, :score_maturite_ia, :score_gouvernance, :score_adoption, :score_automatisation, :score_transformation_global, :score_risque, :score_creation_valeur, :score_gouvernance_risque, :score_opportunite_business, :score_urgence, :niveau_maturite, :niveau_transformation, :niveau_risque, :niveau_creation_valeur, :niveau_opportunite, :niveau_urgence, :offre_recommandee, :priorite_principale, :recommandation_principale, :recommandations_secondaires_json, :explication_recommandation, :prochaines_etapes_json, :raw_answers_json, :utm_source, :utm_medium, :utm_campaign, :utm_content, :utm_term, :referrer_url, :temps_completion_secondes)
  ');
  $stmt->execute(array(
    ':source_page'=>$lead['source_page'], ':source_offre'=>$lead['source_offre'], ':prenom'=>$lead['prenom'], ':nom'=>$lead['nom'], ':entreprise'=>$lead['entreprise'] ?: null, ':email'=>$lead['email'], ':telephone'=>$lead['telephone'] ?: null, ':role_contact'=>$lead['role_contact'] ?: null, ':taille_entreprise'=>$lead['taille_entreprise'] ?: null, ':secteur_activite'=>$lead['secteur_activite'] ?: null, ':localisation'=>$lead['localisation'] ?: null, ':chiffre_affaires'=>$lead['chiffre_affaires'] ?: null,
    ':niveau_ia'=>$lead['niveau_ia'] ?: null, ':besoin_principal'=>$lead['besoin_principal'] ?: null, ':perimetre'=>$lead['perimetre'] ?: null, ':horizon'=>$lead['horizon'] ?: null, ':budget'=>$lead['budget'] ?: null, ':message'=>$lead['message'] ?: null, ':consentement_rgpd'=>$lead['consentement_rgpd'], ':ip_hash'=>$lead['ip_hash'], ':user_agent'=>$lead['user_agent'],
    ':score_strategie_leadership'=>$lead['score_strategie_leadership'], ':score_organisation_agilite'=>$lead['score_organisation_agilite'], ':score_maturite_ia'=>$lead['score_maturite_ia'], ':score_gouvernance'=>$lead['score_gouvernance'], ':score_adoption'=>$lead['score_adoption'], ':score_automatisation'=>$lead['score_automatisation'], ':score_transformation_global'=>$lead['score_transformation_global'], ':score_risque'=>$lead['score_risque'], ':score_creation_valeur'=>$lead['score_creation_valeur'], ':score_gouvernance_risque'=>$lead['score_risque'], ':score_opportunite_business'=>$lead['score_creation_valeur'], ':score_urgence'=>$lead['score_urgence'],
    ':niveau_maturite'=>$lead['niveau_maturite_ia'], ':niveau_transformation'=>$lead['niveau_transformation'], ':niveau_risque'=>$lead['niveau_risque'], ':niveau_creation_valeur'=>$lead['niveau_creation_valeur'], ':niveau_opportunite'=>$lead['niveau_creation_valeur'], ':niveau_urgence'=>$lead['niveau_urgence'], ':offre_recommandee'=>$lead['recommandation_principale'], ':priorite_principale'=>$lead['priorite_principale'], ':recommandation_principale'=>$lead['recommandation_principale'], ':recommandations_secondaires_json'=>$secondaryJson, ':explication_recommandation'=>$lead['explication_recommandation'], ':prochaines_etapes_json'=>$nextStepsJson, ':raw_answers_json'=>$lead['raw_answers_json'],
    ':utm_source'=>$lead['utm_source'] ?: null, ':utm_medium'=>$lead['utm_medium'] ?: null, ':utm_campaign'=>$lead['utm_campaign'] ?: null, ':utm_content'=>$lead['utm_content'] ?: null, ':utm_term'=>$lead['utm_term'] ?: null, ':referrer_url'=>$lead['referrer_url'] ?: null, ':temps_completion_secondes'=>$lead['temps_completion_secondes'],
  ));
  $leadId = (int)$pdo->lastInsertId();
  send_lead_notification($leadId, $lead);
  send_prospect_assessment_confirmation($leadId, $lead);
  $_SESSION['pre_diag_result'] = array_merge($assessment, array(
    'lead_id'=>$leadId,
    'prenom'=>$lead['prenom'],
    'dimensions'=>transformation_assessment_dimensions(),
    'constats'=>$assessment['constats'],
  ));
  unset($_SESSION['pre_diag_old'], $_SESSION['pre_diag_errors']);
  redirect_to('/merci-pre-diagnostic-ia/');
} catch (Throwable $e) {
  app_log('Lead submit failed: ' . $e->getMessage());
  $_SESSION['pre_diag_errors'] = array('Votre demande ne peut pas être envoyée pour le moment. Vous pouvez planifier directement un échange.');
  redirect_to('/evaluer-mon-besoin-ia/');
}
