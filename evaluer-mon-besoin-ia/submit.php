<?php
require __DIR__ . '/../includes/security.php';
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
$lead = array(
  'source_page' => clean_text($_POST['source_page'] ?? '/evaluer-mon-besoin-ia/', 255),
  'source_offre' => $sourceOffre,
  'nom' => clean_text($_POST['nom'] ?? '', 190),
  'entreprise' => clean_text($_POST['entreprise'] ?? '', 190),
  'email' => clean_text($_POST['email'] ?? '', 190),
  'telephone' => clean_text($_POST['telephone'] ?? '', 80),
  'role_contact' => clean_text($_POST['role_contact'] ?? '', 120),
  'niveau_ia' => clean_text($_POST['niveau_ia'] ?? '', 190),
  'besoin_principal' => clean_text($_POST['besoin_principal'] ?? '', 190),
  'perimetre' => clean_text($_POST['perimetre'] ?? '', 190),
  'horizon' => clean_text($_POST['horizon'] ?? '', 190),
  'budget' => clean_text($_POST['budget'] ?? '', 190),
  'message' => clean_textarea($_POST['message'] ?? '', 5000),
  'consentement_rgpd' => !empty($_POST['consentement_rgpd']) ? 1 : 0,
  'ip_hash' => client_ip_hash(),
  'user_agent' => client_user_agent(),
);

$errors = array();
if ($lead['nom'] === '') { $errors[] = 'Le nom est obligatoire.'; }
if ($lead['email'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'Un email valide est obligatoire.'; }
if (!$lead['consentement_rgpd']) { $errors[] = 'Le consentement RGPD est obligatoire.'; }

if ($errors) {
  $_SESSION['pre_diag_errors'] = $errors;
  redirect_to('/evaluer-mon-besoin-ia/' . (!empty($_POST['source_offre_code']) ? '?offre=' . rawurlencode($_POST['source_offre_code']) : ''));
}

try {
  $pdo = db_required();
  $stmt = $pdo->prepare('
    INSERT INTO leads_diagnostic_ia
      (source_page, source_offre, nom, entreprise, email, telephone, role_contact, niveau_ia, besoin_principal, perimetre, horizon, budget, message, consentement_rgpd, ip_hash, user_agent)
    VALUES
      (:source_page, :source_offre, :nom, :entreprise, :email, :telephone, :role_contact, :niveau_ia, :besoin_principal, :perimetre, :horizon, :budget, :message, :consentement_rgpd, :ip_hash, :user_agent)
  ');
  $stmt->execute(array(
    ':source_page' => $lead['source_page'],
    ':source_offre' => $lead['source_offre'],
    ':nom' => $lead['nom'],
    ':entreprise' => $lead['entreprise'] ?: null,
    ':email' => $lead['email'],
    ':telephone' => $lead['telephone'] ?: null,
    ':role_contact' => $lead['role_contact'] ?: null,
    ':niveau_ia' => $lead['niveau_ia'] ?: null,
    ':besoin_principal' => $lead['besoin_principal'] ?: null,
    ':perimetre' => $lead['perimetre'] ?: null,
    ':horizon' => $lead['horizon'] ?: null,
    ':budget' => $lead['budget'] ?: null,
    ':message' => $lead['message'] ?: null,
    ':consentement_rgpd' => $lead['consentement_rgpd'],
    ':ip_hash' => $lead['ip_hash'],
    ':user_agent' => $lead['user_agent'],
  ));
  $leadId = (int)$pdo->lastInsertId();
  send_lead_notification($leadId, $lead);
  unset($_SESSION['pre_diag_old'], $_SESSION['pre_diag_errors']);
  redirect_to('/merci-pre-diagnostic-ia/');
} catch (Throwable $e) {
  app_log('Lead submit failed: ' . $e->getMessage());
  $_SESSION['pre_diag_errors'] = array('Votre demande ne peut pas être envoyée pour le moment. Vous pouvez planifier directement un échange.');
  redirect_to('/evaluer-mon-besoin-ia/' . (!empty($_POST['source_offre_code']) ? '?offre=' . rawurlencode($_POST['source_offre_code']) : ''));
}

