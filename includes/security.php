<?php
require_once __DIR__ . '/helpers.php';

function start_app_session() {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
}

function csrf_token() {
  start_app_session();
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrf_field() {
  return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_is_valid($token) {
  start_app_session();
  return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function require_csrf() {
  if (!csrf_is_valid($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Action non autorisée.');
  }
}

function allowed_lead_statuses() {
  return array('Nouveau', 'À qualifier', 'RDV proposé', 'RDV planifié', 'Proposition envoyée', 'Gagné', 'Perdu', 'À relancer');
}

function require_valid_lead_status($status) {
  return in_array($status, allowed_lead_statuses(), true) ? $status : 'Nouveau';
}

