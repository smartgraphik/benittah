<?php
require __DIR__.'/../auth.php';
require_admin();

function sitemap_xml_escape($value) {
  return htmlspecialchars((string)$value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

function build_sitemap_xml() {
  $entries = array();
  $pages = db_fetch_all('SELECT * FROM seo_pages WHERE sitemap_include = 1 ORDER BY page_path ASC');
  foreach ($pages as $page) {
    $loc = $page['canonical_url'] ?: app_url($page['page_path']);
    $entries[] = array(
      'loc'=>$loc,
      'lastmod'=>$page['updated_at'] ? substr($page['updated_at'], 0, 10) : date('Y-m-d'),
      'changefreq'=>$page['sitemap_changefreq'] ?: 'monthly',
      'priority'=>$page['sitemap_priority'] ?: '0.8',
    );
  }
  $articles = db_fetch_all("SELECT slug, updated_at, published_at, canonical_url FROM articles WHERE status = 'published' ORDER BY COALESCE(published_at, created_at) DESC");
  foreach ($articles as $article) {
    $entries[] = array(
      'loc'=>$article['canonical_url'] ?: app_url('/articles/' . rawurlencode($article['slug']) . '/'),
      'lastmod'=>substr(($article['updated_at'] ?: $article['published_at'] ?: date('Y-m-d')), 0, 10),
      'changefreq'=>'monthly',
      'priority'=>'0.7',
    );
  }

  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
  foreach ($entries as $entry) {
    $xml .= "  <url><loc>" . sitemap_xml_escape($entry['loc']) . "</loc><lastmod>" . sitemap_xml_escape($entry['lastmod']) . "</lastmod><changefreq>" . sitemap_xml_escape($entry['changefreq']) . "</changefreq><priority>" . sitemap_xml_escape($entry['priority']) . "</priority></url>\n";
  }
  $xml .= "</urlset>\n";
  return $xml;
}

$message = '';
$error = '';
if (request_method_is_post()) {
  require_csrf();
  try {
    $xml = build_sitemap_xml();
    if (file_put_contents(__DIR__ . '/../../sitemap.xml', $xml, LOCK_EX) === false) {
      throw new RuntimeException('Unable to write sitemap.xml');
    }
    header('Location: /admin/seo/?sitemap=1');
    exit;
  } catch (Throwable $e) {
    app_log('Sitemap generation failed: ' . $e->getMessage());
    $error = 'Impossible de générer le sitemap. Vérifiez la base de données et les permissions fichier.';
  }
}

require __DIR__.'/../_layout.php';
admin_header('Admin — Sitemap','sitemap');
?>
<div class="admin-top">
  <div><h1>Sitemap</h1><p>Génération de /sitemap.xml depuis les pages SEO et les articles publiés.</p></div>
  <form method="post"><?= csrf_field() ?><button class="btn btn-primary" type="submit">Régénérer le sitemap</button></form>
</div>
<?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<?php if($message): ?><div class="notice"><?= e($message) ?></div><?php endif; ?>
<div class="admin-panel">
  <p>Le sitemap inclut les entrées <code>seo_pages</code> avec <code>sitemap_include = 1</code> et les articles publiés.</p>
  <p><a class="mini-btn" href="/sitemap.xml">Voir le sitemap actuel</a></p>
</div>
<?php admin_footer(); ?>
