<?php
require_once __DIR__ . '/php8_guard.php';

function load_app_config() {
  $example = __DIR__ . '/../config/config.example.php';
  $local = __DIR__ . '/../config/config.local.php';
  if (file_exists($local)) {
    require_once $local;
    return;
  }
  if (file_exists($example)) { require_once $example; }
}

load_app_config();

function db_configured() {
  return defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS') && DB_PASS !== '';
}

function db() {
  static $pdo = null;
  static $failed = false;
  if ($pdo instanceof PDO) { return $pdo; }
  if ($failed || !db_configured()) { return null; }
  if (!class_exists('PDO')) {
    $failed = true;
    return null;
  }

  $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . $charset;
  try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ));
    return $pdo;
  } catch (Throwable $e) {
    $failed = true;
    if (function_exists('app_log')) {
      app_log('DB connection failed: ' . $e->getMessage());
    }
    return null;
  }
}

function db_required() {
  $pdo = db();
  if (!$pdo) {
    throw new RuntimeException('Database is not configured or unavailable.');
  }
  return $pdo;
}

function db_fetch_all($sql, $params = array()) {
  $pdo = db_required();
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll();
}

function db_fetch_one($sql, $params = array()) {
  $pdo = db_required();
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $row = $stmt->fetch();
  return $row ?: null;
}

function db_execute($sql, $params = array()) {
  $pdo = db_required();
  $stmt = $pdo->prepare($sql);
  return $stmt->execute($params);
}
