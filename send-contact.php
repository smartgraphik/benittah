<?php
require_once __DIR__ . '/includes/php8_guard.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /contact/'); exit; }
$name = trim(isset($_POST['name']) ? $_POST['name'] : '');
$email = trim(isset($_POST['email']) ? $_POST['email'] : '');
$company = trim(isset($_POST['company']) ? $_POST['company'] : '');
$message = trim(isset($_POST['message']) ? $_POST['message'] : '');
$body = "Nom: $name
Email: $email
Entreprise: $company

$message";
// À configurer chez IONOS si besoin : mail('contact@smartgraphik.com','Contact benittah.com',$body);
?><!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="/assets/css/style.css"><title>Message reçu</title></head><body><section class="page-hero"><div class="container"><h1>Message préparé</h1><p>Le formulaire est prêt. Configure la fonction mail() dans send-contact.php pour l’envoi réel depuis IONOS.</p><a class="btn btn-primary" href="/">Retour à l’accueil</a></div></section></body></html>