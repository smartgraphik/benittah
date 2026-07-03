<?php

if (!defined('ADMIN_SESSION_IDLE_TIMEOUT')) { define('ADMIN_SESSION_IDLE_TIMEOUT', 1800); }
if (!defined('ADMIN_SESSION_ABSOLUTE_TIMEOUT')) { define('ADMIN_SESSION_ABSOLUTE_TIMEOUT', 28800); }
if (!defined('TRUST_PROXY_HEADERS')) { define('TRUST_PROXY_HEADERS', false); }

function admin_is_https_request() {
  if (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') { return true; }
  if (!empty($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443') { return true; }
  if (TRUST_PROXY_HEADERS && !empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $proto = strtolower(trim(explode(',', (string)$_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
    return $proto === 'https';
  }
  return false;
}

function admin_no_store_headers() {
  if (headers_sent()) { return; }
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
}

function admin_configure_session() {
  if (session_status() !== PHP_SESSION_NONE) { return; }
  ini_set('session.use_strict_mode', '1');
  ini_set('session.use_only_cookies', '1');
  ini_set('session.cookie_httponly', '1');
  ini_set('session.use_trans_sid', '0');
  ini_set('session.cookie_samesite', 'Lax');
  session_name('BENITTAH_ADMINSESSID');
  session_set_cookie_params(array(
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => admin_is_https_request(),
    'httponly' => true,
    'samesite' => 'Lax',
  ));
}

function admin_session_start() {
  admin_configure_session();
  if (session_status() === PHP_SESSION_NONE) { session_start(); }
}

function admin_user_agent_fingerprint() {
  $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
  return hash('sha256', 'admin-ua|' . $ua);
}

function admin_destroy_session() {
  if (session_status() !== PHP_SESSION_ACTIVE) { admin_session_start(); }
  $_SESSION = array();
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', array(
      'expires' => time() - 42000,
      'path' => $params['path'] ?: '/',
      'domain' => $params['domain'] ?: '',
      'secure' => !empty($params['secure']),
      'httponly' => !empty($params['httponly']),
      'samesite' => $params['samesite'] ?? 'Lax',
    ));
  }
  session_destroy();
}

function admin_initialize_authenticated_session($user) {
  session_regenerate_id(true);
  $now = time();
  $_SESSION['admin_user_id'] = (int)$user['id'];
  $_SESSION['admin_email'] = $user['email'];
  $_SESSION['admin_role'] = $user['role'] ?: 'admin';
  $_SESSION['admin_session_created_at'] = $now;
  $_SESSION['admin_last_activity_at'] = $now;
  $_SESSION['admin_ua_hash'] = admin_user_agent_fingerprint();
}

function admin_session_expired_reason() {
  if (empty($_SESSION['admin_user_id'])) { return ''; }
  $now = time();
  $created = (int)($_SESSION['admin_session_created_at'] ?? $now);
  $last = (int)($_SESSION['admin_last_activity_at'] ?? $now);
  if ($created <= 0 || ($now - $created) > ADMIN_SESSION_ABSOLUTE_TIMEOUT) { return 'absolute'; }
  if ($last <= 0 || ($now - $last) > ADMIN_SESSION_IDLE_TIMEOUT) { return 'idle'; }
  if (!empty($_SESSION['admin_ua_hash']) && !hash_equals($_SESSION['admin_ua_hash'], admin_user_agent_fingerprint())) { return 'context'; }
  return '';
}

function admin_touch_session() {
  $_SESSION['admin_last_activity_at'] = time();
}
