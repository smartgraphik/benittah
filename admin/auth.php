<?php
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/session.php';

admin_session_start();

if (!defined('ADMIN_LOGIN_MAX_ATTEMPTS_ACCOUNT')) { define('ADMIN_LOGIN_MAX_ATTEMPTS_ACCOUNT', 5); }
if (!defined('ADMIN_LOGIN_MAX_ATTEMPTS_IP')) { define('ADMIN_LOGIN_MAX_ATTEMPTS_IP', 15); }
if (!defined('ADMIN_LOGIN_WINDOW_SECONDS')) { define('ADMIN_LOGIN_WINDOW_SECONDS', 900); }
if (!defined('ADMIN_LOGIN_LOCK_ACCOUNT_SECONDS')) { define('ADMIN_LOGIN_LOCK_ACCOUNT_SECONDS', 900); }
if (!defined('ADMIN_LOGIN_LOCK_IP_SECONDS')) { define('ADMIN_LOGIN_LOCK_IP_SECONDS', 1800); }
if (!defined('ADMIN_LOGIN_ATTEMPT_RETENTION_SECONDS')) { define('ADMIN_LOGIN_ATTEMPT_RETENTION_SECONDS', 2592000); }
if (!defined('ADMIN_SECURITY_SECRET')) { define('ADMIN_SECURITY_SECRET', null); }

function admin_security_secret() {
  if (defined('ADMIN_SECURITY_SECRET') && ADMIN_SECURITY_SECRET) { return ADMIN_SECURITY_SECRET; }
  if (defined('DB_PASS') && DB_PASS) { return DB_PASS; }
  return app_site_url();
}

function admin_normalize_identifier($email) {
  return strtolower(clean_text($email, 190));
}

function admin_hash_value($value) {
  return hash_hmac('sha256', (string)$value, admin_security_secret());
}

function admin_client_ip_for_hash() {
  // Ne pas utiliser X-Forwarded-For ici : sur mutualisé, REMOTE_ADDR est le signal maîtrisé.
  return clean_text($_SERVER['REMOTE_ADDR'] ?? 'unknown', 80);
}

function admin_login_hashes($email) {
  return array(
    'identifier_hash' => admin_hash_value('admin-login|' . admin_normalize_identifier($email)),
    'ip_hash' => admin_hash_value('admin-ip|' . admin_client_ip_for_hash()),
  );
}

function admin_login_attempts_available() {
  static $available = null;
  if ($available !== null) { return $available; }
  $pdo = db();
  if (!$pdo) { $available = false; return false; }
  try {
    $pdo->query('SELECT 1 FROM admin_login_attempts LIMIT 1');
    $available = true;
  } catch (Throwable $e) {
    app_log('Admin login attempts unavailable: ' . $e->getMessage());
    $available = false;
  }
  return $available;
}

function admin_record_login_attempt($email, $success) {
  if (!admin_login_attempts_available()) { return; }
  try {
    $hashes = admin_login_hashes($email);
    db_execute('INSERT INTO admin_login_attempts (identifier_hash, ip_hash, success, attempted_at) VALUES (:identifier_hash, :ip_hash, :success, NOW())', array(
      ':identifier_hash' => $hashes['identifier_hash'],
      ':ip_hash' => $hashes['ip_hash'],
      ':success' => $success ? 1 : 0,
    ));
  } catch (Throwable $e) {
    app_log('Admin login attempt record failed: ' . $e->getMessage());
  }
}

function admin_failed_attempt_stats($column, $hash, $windowSeconds) {
  if (!in_array($column, array('identifier_hash', 'ip_hash'), true)) { return array('failures'=>0, 'latest'=>null); }
  if (!admin_login_attempts_available()) { return array('failures'=>0, 'latest'=>null); }
  try {
    $since = date('Y-m-d H:i:s', time() - (int)$windowSeconds);
    $stmt = db_required()->prepare('SELECT COUNT(*) AS failures, MAX(attempted_at) AS latest FROM admin_login_attempts WHERE ' . $column . ' = :hash AND success = 0 AND attempted_at >= :since');
    $stmt->execute(array(':hash'=>$hash, ':since'=>$since));
    $row = $stmt->fetch();
    return array('failures'=>(int)($row['failures'] ?? 0), 'latest'=>$row['latest'] ?? null);
  } catch (Throwable $e) {
    app_log('Admin failed attempt lookup failed: ' . $e->getMessage());
    return array('failures'=>0, 'latest'=>null);
  }
}

function admin_lock_active($stats, $maxAttempts, $lockSeconds) {
  if ((int)$stats['failures'] < (int)$maxAttempts || empty($stats['latest'])) { return false; }
  $latest = strtotime($stats['latest']);
  return $latest !== false && ($latest + (int)$lockSeconds) > time();
}

function admin_login_is_locked($email) {
  $hashes = admin_login_hashes($email);
  $account = admin_failed_attempt_stats('identifier_hash', $hashes['identifier_hash'], ADMIN_LOGIN_WINDOW_SECONDS);
  if (admin_lock_active($account, ADMIN_LOGIN_MAX_ATTEMPTS_ACCOUNT, ADMIN_LOGIN_LOCK_ACCOUNT_SECONDS)) { return true; }
  $ip = admin_failed_attempt_stats('ip_hash', $hashes['ip_hash'], ADMIN_LOGIN_WINDOW_SECONDS);
  return admin_lock_active($ip, ADMIN_LOGIN_MAX_ATTEMPTS_IP, ADMIN_LOGIN_LOCK_IP_SECONDS);
}

function admin_apply_login_delay($email) {
  $hashes = admin_login_hashes($email);
  $account = admin_failed_attempt_stats('identifier_hash', $hashes['identifier_hash'], ADMIN_LOGIN_WINDOW_SECONDS);
  $ip = admin_failed_attempt_stats('ip_hash', $hashes['ip_hash'], ADMIN_LOGIN_WINDOW_SECONDS);
  $failures = max((int)$account['failures'], (int)$ip['failures']);
  $delay = min(3, max(0, ($failures - 1) * 0.6));
  if ($delay > 0) { usleep((int)round($delay * 1000000)); }
}

function admin_clear_account_failures($email) {
  if (!admin_login_attempts_available()) { return; }
  try {
    $hashes = admin_login_hashes($email);
    db_execute('DELETE FROM admin_login_attempts WHERE identifier_hash = :identifier_hash AND success = 0', array(':identifier_hash'=>$hashes['identifier_hash']));
  } catch (Throwable $e) {
    app_log('Admin login failure cleanup failed: ' . $e->getMessage());
  }
}

function admin_cleanup_old_login_attempts() {
  if (!admin_login_attempts_available()) { return; }
  if (random_int(1, 100) > 3) { return; }
  try {
    $before = date('Y-m-d H:i:s', time() - ADMIN_LOGIN_ATTEMPT_RETENTION_SECONDS);
    db_execute('DELETE FROM admin_login_attempts WHERE attempted_at < :before', array(':before'=>$before));
  } catch (Throwable $e) {
    app_log('Admin login attempt cleanup failed: ' . $e->getMessage());
  }
}

function ensure_first_admin() {
  $pdo = db();
  if (!$pdo) { return; }
  try {
    $count = (int)$pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    if ($count > 0) { return; }
    if (!defined('FIRST_ADMIN_EMAIL') || !defined('FIRST_ADMIN_PASSWORD') || !FIRST_ADMIN_EMAIL || !FIRST_ADMIN_PASSWORD) { return; }
    $stmt = $pdo->prepare('INSERT INTO admin_users (email, password_hash, role) VALUES (:email, :password_hash, :role)');
    $stmt->execute(array(
      ':email' => FIRST_ADMIN_EMAIL,
      ':password_hash' => password_hash(FIRST_ADMIN_PASSWORD, PASSWORD_DEFAULT),
      ':role' => 'admin',
    ));
  } catch (Throwable $e) {
    app_log('First admin init failed: ' . $e->getMessage());
  }
}

ensure_first_admin();

function is_admin() {
  return !empty($_SESSION['admin_user_id']);
}

function current_admin() {
  if (!is_admin()) { return null; }
  return array(
    'id' => $_SESSION['admin_user_id'],
    'email' => $_SESSION['admin_email'] ?? '',
    'role' => $_SESSION['admin_role'] ?? 'admin',
  );
}

function require_admin() {
  if (!is_admin()) {
    header('Location: /admin/login.php');
    exit;
  }
  $expired = admin_session_expired_reason();
  if ($expired !== '') {
    admin_destroy_session();
    header('Location: /admin/login.php?expired=1');
    exit;
  }
  admin_no_store_headers();
  admin_touch_session();
}

function admin_login($email, $password, &$status = null) {
  $status = 'invalid';
  $pdo = db();
  if (!$pdo) { return false; }
  $email = admin_normalize_identifier($email);
  if (admin_login_is_locked($email)) {
    $status = 'blocked';
    return false;
  }
  try {
    $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE email = :email LIMIT 1');
    $stmt->execute(array(':email' => $email));
    $user = $stmt->fetch();
    $dummyHash = '$2y$10$C6UzMDM.H6dfI/f/IKcEeOInUuTxOhj5abDfA6c7d6pj1vn5I8hFi';
    $hash = $user['password_hash'] ?? $dummyHash;
    $verified = password_verify($password, $hash);
    if (!$user || !$verified) {
      admin_record_login_attempt($email, false);
      admin_apply_login_delay($email);
      return false;
    }
    admin_record_login_attempt($email, true);
    admin_clear_account_failures($email);
    admin_cleanup_old_login_attempts();
    admin_initialize_authenticated_session($user);
    $status = 'ok';
    return true;
  } catch (Throwable $e) {
    app_log('Admin login failed: ' . $e->getMessage());
    return false;
  }
}

function admin_logout() {
  admin_destroy_session();
}
