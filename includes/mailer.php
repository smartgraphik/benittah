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
  $entreprise = $lead['entreprise'] ?? '';
  $recommendation = $lead['recommandation_principale'] ?? ($lead['offre_recommandee'] ?? 'Diagnostic Transformation 360°');
  $subject = 'Nouveau Diagnostic Transformation 360° — ' . $recommendation . ' — ' . ($fullName ?: 'Prospect');
  $adminLink = app_url('/admin/leads/view.php?id=' . (int)$leadId);

  $secondaries = array();
  if (!empty($lead['recommandations_secondaires_json'])) {
    $decoded = json_decode($lead['recommandations_secondaires_json'], true);
    if (is_array($decoded)) { $secondaries = $decoded; }
  } elseif (!empty($lead['recommandations_secondaires']) && is_array($lead['recommandations_secondaires'])) {
    $secondaries = $lead['recommandations_secondaires'];
  }

  $lines = array(
    'Nouveau Diagnostic Transformation 360°',
    'Date : ' . date('Y-m-d H:i:s'),
    '',
    'Identité',
    'Prénom : ' . $prenom,
    'Nom : ' . $nom,
    'Entreprise : ' . $entreprise,
    'Fonction : ' . ($lead['role_contact'] ?? ''),
    'Email : ' . ($lead['email'] ?? ''),
    'Téléphone : ' . ($lead['telephone'] ?? ''),
    'Taille : ' . ($lead['taille_entreprise'] ?? ''),
    'Secteur : ' . ($lead['secteur_activite'] ?? ''),
    '',
    'Qualification 360°',
    'Score global : ' . ($lead['score_transformation_global'] ?? '') . '/100 — ' . ($lead['niveau_transformation'] ?? ''),
    'Risque : ' . ($lead['score_risque'] ?? '') . '/100 — ' . ($lead['niveau_risque'] ?? ''),
    'Création de valeur : ' . ($lead['score_creation_valeur'] ?? '') . '/100 — ' . ($lead['niveau_creation_valeur'] ?? ''),
    'Urgence : ' . ($lead['score_urgence'] ?? '') . '/100 — ' . ($lead['niveau_urgence'] ?? ''),
    'Priorité principale : ' . ($lead['priorite_principale'] ?? ''),
    'Recommandation principale : ' . $recommendation,
    'Recommandations complémentaires : ' . ($secondaries ? implode(', ', $secondaries) : 'Aucune'),
    '',
    'Principal enjeu exprimé :',
    $lead['message'] ?? '',
    '',
    'Résumé de recommandation :',
    $lead['explication_recommandation'] ?? '',
    '',
    'Fiche prospect : ' . $adminLink,
  );
  $body = implode("\n", $lines);

  $phpmailerResult = send_with_phpmailer_if_available($to, $subject, $body);
  if ($phpmailerResult === true) { return true; }

  $sent = send_plain_mail($to, $subject, $body);
  if (!$sent) { mail_log('mail() failed for lead #' . (int)$leadId . ' to ' . $to); }
  return $sent;
}

function send_prospect_assessment_confirmation($leadId, $lead) {
  $to = $lead['email'] ?? '';
  if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) { return false; }

  $prenom = $lead['prenom'] ?? '';
  $subject = 'Votre Diagnostic Transformation 360°';
  $calendlyUrl = app_url('/contact/#calendly-widget');
  $recommendation = $lead['recommandation_principale'] ?? 'Diagnostic Transformation 360°';

  $lines = array(
    'Bonjour' . ($prenom ? ' ' . $prenom : '') . ',',
    '',
    'Merci d’avoir complété le Diagnostic Transformation 360°.',
    '',
    'Votre première lecture en ligne :',
    'Niveau global : ' . (($lead['niveau_transformation'] ?? '') ?: 'à approfondir'),
    'Score global : ' . (($lead['score_transformation_global'] ?? '') !== '' ? ($lead['score_transformation_global'] . '/100') : 'à approfondir'),
    'Priorité principale : ' . (($lead['priorite_principale'] ?? '') ?: 'à préciser ensemble'),
    'Recommandation : ' . $recommendation,
    '',
    'Ce résultat constitue une première photographie. Un échange permettra d’approfondir le contexte, de qualifier les priorités et d’identifier le meilleur format d’accompagnement.',
    '',
    'Pour planifier un rendez-vous :',
    $calendlyUrl,
    '',
    'À bientôt,',
    'Cédrick Benittah',
  );

  $phpmailerResult = send_with_phpmailer_if_available($to, $subject, implode("\n", $lines));
  if ($phpmailerResult === true) { return true; }

  $sent = send_plain_mail($to, $subject, implode("\n", $lines));
  if (!$sent) { mail_log('Prospect confirmation failed for lead #' . (int)$leadId); }
  return $sent;
}
