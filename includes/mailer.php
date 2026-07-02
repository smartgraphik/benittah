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
  $nom = $lead['nom'] ?? '';
  $entreprise = $lead['entreprise'] ?? '';
  $subject = 'Nouveau pré-diagnostic IA — ' . $nom . ' — ' . ($entreprise ?: 'Sans entreprise');
  $adminLink = app_url('/admin/leads/view.php?id=' . (int)$leadId);
  $lines = array(
    'Date : ' . date('Y-m-d H:i:s'),
    'Offre d’origine : ' . ($lead['source_offre'] ?? ''),
    'Nom : ' . $nom,
    'Entreprise : ' . $entreprise,
    'Email : ' . ($lead['email'] ?? ''),
    'Téléphone : ' . ($lead['telephone'] ?? ''),
    'Rôle : ' . ($lead['role_contact'] ?? ''),
    'Niveau IA : ' . ($lead['niveau_ia'] ?? ''),
    'Besoin principal : ' . ($lead['besoin_principal'] ?? ''),
    'Périmètre : ' . ($lead['perimetre'] ?? ''),
    'Horizon : ' . ($lead['horizon'] ?? ''),
    'Budget : ' . ($lead['budget'] ?? ''),
    'Message :',
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

