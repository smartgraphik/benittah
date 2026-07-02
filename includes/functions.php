<?php
require_once __DIR__ . '/php8_guard.php';
function data_path($name) {
  return __DIR__ . '/../data/' . $name . '.json';
}

function read_json($name, $fallback = array()) {
  $p = data_path($name);
  if (!file_exists($p)) {
    return $fallback;
  }
  $raw = file_get_contents($p);
  if ($raw === false || trim($raw) === '') {
    return $fallback;
  }
  $d = json_decode($raw, true);
  return is_array($d) ? $d : $fallback;
}

function write_json($name, $data) {
  $p = data_path($name);
  $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  if ($json === false) { return false; }
  return file_put_contents($p, $json, LOCK_EX);
}

function e($v) {
  return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function current_url($path = '') {
  return $path ? $path : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
}

function site_base_url() {
  $cfg = site();
  $base = isset($cfg['site_url']) ? rtrim($cfg['site_url'], '/') : 'https://benittah.com';
  return $base;
}

function absolute_url($path) {
  if (preg_match('#^https?://#', (string)$path)) { return $path; }
  return site_base_url() . '/' . ltrim((string)$path, '/');
}

function current_path() {
  $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
  $path = parse_url($uri, PHP_URL_PATH);
  return $path ? $path : '/';
}

function redirect_legacy($legacyPath, $newPath) {
  if (!headers_sent() && current_path() === $legacyPath) {
    $qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: ' . $newPath . $qs, true, 301);
    exit;
  }
}

function active($page, $current) {
  return $page === $current ? 'is-active' : '';
}

function site() {
  return read_json('site', array());
}

function sort_by_priority_asc($a, $b) {
  $pa = isset($a['priority']) ? (int)$a['priority'] : 99;
  $pb = isset($b['priority']) ? (int)$b['priority'] : 99;
  if ($pa === $pb) { return 0; }
  return ($pa < $pb) ? -1 : 1;
}

function sort_by_priority_desc($a, $b) {
  $pa = isset($a['priority']) ? (int)$a['priority'] : 0;
  $pb = isset($b['priority']) ? (int)$b['priority'] : 0;
  if ($pa === $pb) { return 0; }
  return ($pa > $pb) ? -1 : 1;
}

function services() {
  $s = read_json('services', array());
  usort($s, 'sort_by_priority_asc');
  return $s;
}

function articles($published = true) {
  $dbArticles = db_articles($published);
  if (!empty($dbArticles)) { return $dbArticles; }
  $a = read_json('articles', array());
  if ($published) {
    $filtered = array();
    foreach ($a as $x) {
      $status = isset($x['status']) ? $x['status'] : 'published';
      if ($status !== 'draft') {
        $filtered[] = $x;
      }
    }
    $a = $filtered;
  }
  usort($a, 'sort_by_priority_desc');
  return $a;
}

function find_article($slug) {
  $dbArticle = db_find_article($slug);
  if ($dbArticle) { return $dbArticle; }
  foreach (read_json('articles', array()) as $a) {
    if ((isset($a['slug']) ? $a['slug'] : '') === $slug) {
      return $a;
    }
  }
  return null;
}

function normalize_db_article($row) {
  $published = isset($row['published_at']) && $row['published_at'] ? substr($row['published_at'], 0, 10) : '';
  return array(
    'id' => isset($row['id']) ? (int)$row['id'] : 0,
    'slug' => $row['slug'] ?? '',
    'title' => $row['title'] ?? '',
    'category' => $row['category'] ?? '',
    'excerpt' => $row['excerpt'] ?? '',
    'content' => $row['content'] ?? '',
    'content_format' => 'html',
    'date' => $published,
    'read_time' => '7 min',
    'status' => $row['status'] ?? 'draft',
    'author' => 'Cédrick Benittah',
    'image' => !empty($row['cover_image']) ? $row['cover_image'] : '/assets/img/ui/og-image.svg',
    'seo_title' => $row['meta_title'] ?? '',
    'seo_description' => $row['meta_description'] ?? '',
    'canonical_url' => $row['canonical_url'] ?? '',
    'noindex' => !empty($row['noindex']),
    'updated' => isset($row['updated_at']) && $row['updated_at'] ? substr($row['updated_at'], 0, 10) : $published,
  );
}

function db_articles($published = true) {
  require_once __DIR__ . '/db.php';
  $pdo = db();
  if (!$pdo) { return array(); }
  try {
    $sql = 'SELECT * FROM articles';
    if ($published) { $sql .= " WHERE status = 'published'"; }
    $sql .= ' ORDER BY COALESCE(published_at, created_at) DESC';
    $rows = $pdo->query($sql)->fetchAll();
    $out = array();
    foreach ($rows as $row) { $out[] = normalize_db_article($row); }
    return $out;
  } catch (Throwable $e) {
    if (function_exists('app_log')) { app_log('DB articles fallback: ' . $e->getMessage()); }
    return array();
  }
}

function db_find_article($slug) {
  require_once __DIR__ . '/db.php';
  $pdo = db();
  if (!$pdo) { return null; }
  try {
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE slug = :slug LIMIT 1');
    $stmt->execute(array(':slug'=>$slug));
    $row = $stmt->fetch();
    return $row ? normalize_db_article($row) : null;
  } catch (Throwable $e) {
    if (function_exists('app_log')) { app_log('DB article lookup fallback: ' . $e->getMessage()); }
    return null;
  }
}

function find_service($slug) {
  foreach (services() as $s) {
    if ((isset($s['slug']) ? $s['slug'] : '') === $slug) {
      return $s;
    }
  }
  return null;
}

function page_meta($key) {
  foreach (read_json('pages_seo', array()) as $p) {
    if ((isset($p['key']) ? $p['key'] : '') === $key) {
      return $p;
    }
  }
  return array();
}

function excerpt($txt, $len = 180) {
  $txt = trim(strip_tags((string)$txt));
  if (function_exists('mb_strlen') && function_exists('mb_substr')) {
    return mb_strlen($txt, 'UTF-8') > $len ? mb_substr($txt, 0, $len, 'UTF-8') . '…' : $txt;
  }
  return strlen($txt) > $len ? substr($txt, 0, $len) . '…' : $txt;
}

function starts_with_text($text, $prefix) {
  return substr($text, 0, strlen($prefix)) === $prefix;
}

function render_content($txt) {
  $lines = preg_split('/\R/', (string)$txt);
  $html = '';
  $inList = false;
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '') {
      if ($inList) { $html .= '</ul>'; $inList = false; }
      continue;
    }
    if (starts_with_text($line, '### ')) {
      if ($inList) { $html .= '</ul>'; $inList = false; }
      $html .= '<h3>' . e(substr($line, 4)) . '</h3>';
      continue;
    }
    if (starts_with_text($line, '## ')) {
      if ($inList) { $html .= '</ul>'; $inList = false; }
      $html .= '<h2>' . e(substr($line, 3)) . '</h2>';
      continue;
    }
    if (preg_match('/^[-•]\s+(.+)/u', $line, $m)) {
      if (!$inList) { $html .= '<ul>'; $inList = true; }
      $html .= '<li>' . e($m[1]) . '</li>';
      continue;
    }
    if ($inList) { $html .= '</ul>'; $inList = false; }
    $html .= '<p>' . e($line) . '</p>';
  }
  if ($inList) { $html .= '</ul>'; }
  return $html;
}

function article_url($a) {
  $slug = isset($a['slug']) ? $a['slug'] : '';
  return '/articles/' . rawurlencode($slug) . '/';
}

function service_url($s) {
  $slug = isset($s['slug']) ? $s['slug'] : '';
  if ($slug === 'diagnostic-adoption-ia-transformation') { return '/diagnostic-adoption-ia-transformation/'; }
  if ($slug === 'gouvernance-ia-ia-act') { return '/gouvernance-ia-ia-act/'; }
  return '/accompagnements/' . rawurlencode($slug) . '/';
}


function article_pillar($a) {
  if (!empty($a['pillar'])) { return $a['pillar']; }
  $category = isset($a['category']) ? $a['category'] : '';
  if ($category === 'IA & Data') { return 'IA & Adoption'; }
  if ($category === 'Agilité' || $category === 'Agilité & DevOps' || $category === 'Coaching agile') { return 'Agilité & DevOps'; }
  if ($category === 'Leadership' || $category === 'Performance') { return 'Leadership & Coaching'; }
  if ($category === 'Lectures') { return 'Lectures & Inspirations'; }
  return 'Transformation & Performance';
}

function article_cta($a) {
  if (!empty($a['cta']) && is_array($a['cta'])) { return $a['cta']; }
  $pillar = article_pillar($a);
  if ($pillar === 'IA & Adoption') {
    return array(
      'title' => 'Vous souhaitez passer de l’expérimentation IA à une feuille de route concrète ?',
      'text' => 'Le Diagnostic Adoption IA & Transformation permet d’identifier les cas d’usage utiles, de clarifier les risques et de prioriser les actions 30 / 60 / 90 jours.',
      'label' => 'Découvrir le diagnostic IA',
      'url' => '/diagnostic-adoption-ia-transformation/'
    );
  }
  if ($pillar === 'Agilité & DevOps') {
    return array(
      'title' => 'Votre organisation veut accélérer sans perdre en qualité ni en alignement ?',
      'text' => 'Mon expérience en agilité, DevOps et delivery sert ici un objectif unique : rendre l’adoption IA plus claire, plus fiable et plus utile au terrain.',
      'label' => 'Explorer les accompagnements',
      'url' => '/accompagnements/'
    );
  }
  if ($pillar === 'Leadership & Coaching') {
    return array(
      'title' => 'Vous voulez renforcer la posture de vos dirigeants, managers ou équipes ?',
      'text' => 'Un accompagnement expérimenté permet de travailler la clarté, la posture, l’engagement et la performance collective dans les moments de transformation.',
      'label' => 'Planifier un rendez-vous découverte',
      'url' => '/contact/'
    );
  }
  return array(
    'title' => 'Votre transformation a besoin d’un cadrage clair et actionnable ?',
    'text' => 'Clarifions vos priorités, vos irritants terrain et vos leviers de transformation pour construire une feuille de route utile et adoptée.',
    'label' => 'Demander un diagnostic',
    'url' => '/diagnostic-adoption-ia-transformation/'
  );
}

function related_articles($article, $limit = 3) {
  $items = articles(true);
  $out = array();
  $slug = isset($article['slug']) ? $article['slug'] : '';
  $pillar = article_pillar($article);
  $cat = isset($article['category']) ? $article['category'] : '';
  foreach ($items as $item) {
    if ((isset($item['slug']) ? $item['slug'] : '') === $slug) { continue; }
    if (article_pillar($item) === $pillar || (isset($item['category']) && $item['category'] === $cat)) {
      $out[] = $item;
    }
    if (count($out) >= $limit) { return $out; }
  }
  foreach ($items as $item) {
    if ((isset($item['slug']) ? $item['slug'] : '') === $slug) { continue; }
    $exists = false;
    foreach ($out as $o) { if (($o['slug'] ?? '') === ($item['slug'] ?? '')) { $exists = true; } }
    if (!$exists) { $out[] = $item; }
    if (count($out) >= $limit) { return $out; }
  }
  return $out;
}

function article_takeaways($article) {
  if (!empty($article['takeaways']) && is_array($article['takeaways'])) { return $article['takeaways']; }
  return array(
    'Clarifier le problème à résoudre avant de choisir une méthode ou un outil.',
    'Prioriser les actions selon la valeur, la faisabilité et l’adhésion des équipes.',
    'Transformer les apprentissages en décisions concrètes et en feuille de route.'
  );
}

function article_internal_targets($article) {
  $slug = isset($article['slug']) ? $article['slug'] : '';
  $targets = array(
    array('term' => 'Diagnostic Adoption IA', 'url' => '/diagnostic-adoption-ia-transformation/'),
    array('term' => 'adoption IA', 'url' => '/diagnostic-adoption-ia-transformation/'),
    array('term' => 'IA Act', 'url' => '/articles/ia-act-organisations-anticiper/'),
    array('term' => 'IA générative', 'url' => '/articles/ia-generative-passer-experimentation-impact/'),
    array('term' => 'DevOps', 'url' => '/articles/agile-devops-duo-gagnant/'),
    array('term' => 'agilité', 'url' => '/articles/agilite-au-dela-des-methodes/'),
    array('term' => 'transformation', 'url' => '/diagnostic-adoption-ia-transformation/'),
    array('term' => 'leadership', 'url' => '/cedrick-benittah/'),
    array('term' => 'coaching', 'url' => '/cedrick-benittah/')
  );
  $filtered = array();
  foreach ($targets as $t) {
    if (strpos($t['url'], '/articles/'.$slug.'/') !== false) { continue; }
    $filtered[] = $t;
  }
  return $filtered;
}

function inject_internal_links($html, $article) {
  $targets = article_internal_targets($article);
  $count = 0;
  foreach ($targets as $t) {
    if ($count >= 4) { break; }
    $term = preg_quote($t['term'], '/');
    $url = e($t['url']);
    $replacement = '<a href="'.$url.'" class="internal-link">$0</a>';
    $new = preg_replace('/(?<![\w>])(' . $term . ')(?![\w<])/iu', $replacement, $html, 1);
    if ($new !== null && $new !== $html) { $html = $new; $count++; }
  }
  return $html;
}

function render_content_geo($txt, $article) {
  return inject_internal_links(render_content($txt), $article);
}

function render_article_content($article) {
  $content = $article['content'] ?? '';
  if (($article['content_format'] ?? '') === 'html') {
    $allowed = '<p><br><strong><b><em><i><ul><ol><li><h2><h3><blockquote><a>';
    return strip_tags($content, $allowed);
  }
  return render_content_geo($content, $article);
}

function article_breadcrumb_json($article) {
  return array(
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array(
      array('@type'=>'ListItem','position'=>1,'name'=>'Accueil','item'=>absolute_url('/')),
      array('@type'=>'ListItem','position'=>2,'name'=>'Articles','item'=>absolute_url('/articles/')),
      array('@type'=>'ListItem','position'=>3,'name'=>isset($article['title']) ? $article['title'] : 'Article','item'=>absolute_url(article_url($article)))
    )
  );
}

function article_schema_json($article) {
  return array(
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => isset($article['title']) ? $article['title'] : '',
    'description' => isset($article['seo_description']) ? $article['seo_description'] : (isset($article['excerpt']) ? $article['excerpt'] : ''),
    'image' => absolute_url(isset($article['image']) ? $article['image'] : '/assets/img/ui/og-image.svg'),
    'author' => array('@type'=>'Person','name'=>isset($article['author']) ? $article['author'] : 'Cédrick Benittah'),
    'publisher' => array('@type'=>'Organization','name'=>'Cédrick Benittah','logo'=>array('@type'=>'ImageObject','url'=>absolute_url('/assets/img/logo-full.png'))),
    'datePublished' => isset($article['date']) ? $article['date'] : '',
    'dateModified' => isset($article['updated']) ? $article['updated'] : (isset($article['date']) ? $article['date'] : ''),
    'mainEntityOfPage' => absolute_url(article_url($article)),
    'keywords' => !empty($article['keywords']) && is_array($article['keywords']) ? implode(', ', $article['keywords']) : ''
  );
}

?>
