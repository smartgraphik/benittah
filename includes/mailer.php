<?php
require_once __DIR__ . '/helpers.php';

function mailer_from_email() {
  return defined('SMTP_FROM_EMAIL') && SMTP_FROM_EMAIL ? SMTP_FROM_EMAIL : 'cedrick@benittah.com';
}

function mailer_from_name() {
  return defined('SMTP_FROM_NAME') && SMTP_FROM_NAME ? SMTP_FROM_NAME : 'benittah.com';
}

function send_plain_mail($to, $subject, $body) {
  $fromEmail = mailer_from_email();
  $fromName = mailer_from_name();
  $headers = array(
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: ' . $fromName . ' <' . $fromEmail . '>',
    'Reply-To: ' . $fromEmail,
  );
  return @mail($to, $subject, $body, implode("\r\n", $headers));
}

function send_with_phpmailer_if_available($to, $subject, $body) {
  if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer') || !defined('SMTP_HOST') || !SMTP_HOST) {
    return null;
  }
  try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->Port = defined('SMTP_PORT') ? SMTP_PORT : 587;
    $mail->SMTPAuth = defined('SMTP_USER') && SMTP_USER ? true : false;
    if ($mail->SMTPAuth) {
      $mail->Username = SMTP_USER;
      $mail->Password = SMTP_PASS;
    }
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(mailer_from_email(), mailer_from_name());
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->send();
    return true;
  } catch (Throwable $e) {
    mail_log('PHPMailer failed: ' . $e->getMessage());
    return false;
  }
}

function send_lead_notification($leadId, $lead) {
  $to = defined('ADMIN_NOTIFICATION_EMAIL') && ADMIN_NOTIFICATION_EMAIL ? ADMIN_NOTIFICATION_EMAIL : 'cedrick@benittah.com';
  $prenom = $lead['prenom'] ?? '';
  $nom = $lead['nom'] ?? '';
  $fullName = trim($prenom . ' ' . $nom);
  if ($fullName === '') { $fullName = $nom; }
  $entreprise = $lead['entreprise'] ?? '';
  $offre = $lead['offre_recommandee'] ?? ($lead['source_offre'] ?? 'Pré-diagnostic IA');
  $subject = 'Nouveau diagnostic IA — ' . $offre . ' — ' . ($fullName ?: 'Prospect');
  $adminLink = app_url('/admin/leads/view.php?id=' . (int)$leadId);

  $raw = array();
  if (!empty($lead['raw_answers_json'])) {
    $decoded = json_decode($lead['raw_answers_json'], true);
    if (is_array($decoded)) { $raw = $decoded; }
  }
  $objectifs = array();
  if (!empty($raw['objectifs_business']) && is_array($raw['objectifs_business'])) {
    $objectifs = $raw['objectifs_business'];
  } elseif (!empty($lead['besoin_principal'])) {
    $objectifs = array($lead['besoin_principal']);
  }

  $lines = array(
    'Nouveau diagnostic IA qualifié',
    'Date : ' . date('Y-m-d H:i:s'),
    '',
    'Identité',
    'Prénom : ' . $prenom,
    'Nom : ' . $nom,
    'Entreprise : ' . $entreprise,
    'Email : ' . ($lead['email'] ?? ''),
    'Téléphone : ' . ($lead['telephone'] ?? ''),
    'Fonction : ' . ($lead['role_contact'] ?? ''),
    'Taille : ' . ($lead['taille_entreprise'] ?? ''),
    'Secteur : ' . ($lead['secteur_activite'] ?? ''),
    '',
    'Qualification',
    'Offre recommandée : ' . $offre,
    'Maturité IA : ' . ($lead['score_maturite_ia'] ?? '') . '/100 — ' . ($lead['niveau_maturite'] ?? ''),
    'Gouvernance / risque : ' . ($lead['score_gouvernance_risque'] ?? '') . '/100 — ' . ($lead['niveau_risque'] ?? ''),
    'Opportunité business : ' . ($lead['score_opportunite_business'] ?? '') . '/100 — ' . ($lead['niveau_opportunite'] ?? ''),
    'Urgence : ' . ($lead['score_urgence'] ?? '') . '/100 — ' . ($lead['niveau_urgence'] ?? ''),
    '',
    'Objectifs déclarés : ' . ($objectifs ? implode(', ', $objectifs) : 'Non renseigné'),
    '',
    'Résumé de recommandation :',
    $lead['explication_recommandation'] ?? '',
    '',
    'Message prospect :',
    $lead['message'] ?? '',
    '',
    'Fiche lead : ' . $adminLink,
  );
  $body = implode("\n", $lines);

  $phpmailerResult = send_with_phpmailer_if_available($to, $subject, $body);
  if ($phpmailerResult === true) { return true; }

  $sent = send_plain_mail($to, $subject, $body);
  if (!$sent) {
    mail_log('mail() failed for lead #' . (int)$leadId . ' to ' . $to);
  }
  return $sent;
}
