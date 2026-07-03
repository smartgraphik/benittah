<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

function redact_sensitive_log_values($message) {
  $message = (string)$message;
  $constantNames = array('DB_PASS', 'SMTP_PASS', 'FIRST_ADMIN_PASSWORD');
  foreach ($constantNames as $name) {
    if (defined($name)) {
      $value = constant($name);
      if (is_string($value) && $value !== '') {
        $message = str_replace($value, '[redacted]', $message);
      }
    }
  }
  $message = preg_replace('/(password|passwd|pwd|secret|token)(\s*[=:]\s*)([^\s;]+)/i', '$1$2[redacted]', $message);
  return $message;
}

function app_log($message) {
  $dir = __DIR__ . '/../logs';
  if (!is_dir($dir)) { return; }
  $line = '[' . date('Y-m-d H:i:s') . '] ' . redact_sensitive_log_values($message) . PHP_EOL;
  @file_put_contents($dir . '/app.log', $line, FILE_APPEND | LOCK_EX);
}

function mail_log($message) {
  $dir = __DIR__ . '/../logs';
  if (!is_dir($dir)) { return; }
  $line = '[' . date('Y-m-d H:i:s') . '] ' . redact_sensitive_log_values($message) . PHP_EOL;
  @file_put_contents($dir . '/mail-errors.log', $line, FILE_APPEND | LOCK_EX);
}

function app_site_url() {
  if (defined('SITE_URL') && SITE_URL) { return rtrim(SITE_URL, '/'); }
  return site_base_url();
}

function app_url($path) {
  if (preg_match('#^https?://#', (string)$path)) { return $path; }
  return app_site_url() . '/' . ltrim((string)$path, '/');
}

function redirect_to($path) {
  header('Location: ' . $path);
  exit;
}

function request_method_is_post() {
  return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function clean_text($value, $max = 255) {
  $value = trim((string)$value);
  $value = preg_replace('/\s+/u', ' ', $value);
  if (function_exists('mb_substr')) { return mb_substr($value, 0, $max, 'UTF-8'); }
  return substr($value, 0, $max);
}

function clean_textarea($value, $max = 5000) {
  $value = trim((string)$value);
  if (function_exists('mb_substr')) { return mb_substr($value, 0, $max, 'UTF-8'); }
  return substr($value, 0, $max);
}

function slugify_text($text) {
  $text = trim((string)$text);
  if (function_exists('iconv')) {
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if ($converted !== false) { $text = $converted; }
  }
  $text = strtolower($text);
  $text = preg_replace('/[^a-z0-9]+/', '-', $text);
  $text = trim($text, '-');
  return $text !== '' ? $text : 'article';
}

function client_ip_hash() {
  $ip = $_SERVER['REMOTE_ADDR'] ?? '';
  if ($ip === '') { return null; }
  return hash('sha256', $ip . '|' . app_site_url());
}

function client_user_agent() {
  return clean_text($_SERVER['HTTP_USER_AGENT'] ?? '', 255);
}

function offer_label_from_code($code) {
  $map = array(
    'flash' => 'Diagnostic Flash IA',
    'adoption' => 'Diagnostic Transformation 360°',
    '90jours' => 'Accompagnement 90 jours',
  );
  return $map[$code] ?? null;
}

function offer_code_from_label($label) {
  $map = array(
    'Diagnostic Flash IA' => 'flash',
    'Diagnostic Transformation 360°' => 'adoption',
    'Diagnostic Adoption IA' => 'adoption',
    'Accompagnement 90 jours' => '90jours',
  );
  return $map[$label] ?? '';
}

function getSeoMeta($path) {
  $path = '/' . trim((string)$path, '/') . '/';
  if ($path === '//') { $path = '/'; }
  $fallback = array();
  foreach (read_json('pages_seo', array()) as $page) {
    if (($page['slug'] ?? '') === $path || ($page['canonical'] ?? '') === app_url($path)) {
      $fallback = $page;
      break;
    }
  }
  $pdo = db();
  if (!$pdo) { return $fallback; }
  try {
    $stmt = $pdo->prepare('SELECT * FROM seo_pages WHERE page_path = :path LIMIT 1');
    $stmt->execute(array(':path' => $path));
    $row = $stmt->fetch();
    if (!$row) { return $fallback; }
    return array(
      'page' => $row['page_label'] ?: $path,
      'slug' => $row['page_path'],
      'meta_title' => $row['meta_title'] ?: ($fallback['meta_title'] ?? ''),
      'meta_description' => $row['meta_description'] ?: ($fallback['meta_description'] ?? ''),
      'canonical' => $row['canonical_url'] ?: app_url($row['page_path']),
      'og_image' => $row['og_image'] ?: ($fallback['og_image'] ?? '/assets/img/ui/og-image.svg'),
      'robots' => $row['robots'] ?: 'index, follow',
    );
  } catch (Throwable $e) {
    app_log('SEO meta lookup failed: ' . $e->getMessage());
    return $fallback;
  }
}
