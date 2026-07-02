<?php
require_once __DIR__ . '/../includes/security.php';

start_app_session();

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
}

function admin_login($email, $password) {
  $pdo = db();
  if (!$pdo) { return false; }
  try {
    $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE email = :email LIMIT 1');
    $stmt->execute(array(':email' => $email));
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) { return false; }
    session_regenerate_id(true);
    $_SESSION['admin_user_id'] = (int)$user['id'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['admin_role'] = $user['role'] ?: 'admin';
    return true;
  } catch (Throwable $e) {
    app_log('Admin login failed: ' . $e->getMessage());
    return false;
  }
}

function admin_logout() {
  $_SESSION = array();
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
}

