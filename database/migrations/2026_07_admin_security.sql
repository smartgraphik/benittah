-- Migration sécurité admin et restitution diagnostic.
-- Non destructive, compatible MySQL/phpMyAdmin IONOS.

CREATE TABLE IF NOT EXISTS admin_login_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  identifier_hash CHAR(64) NOT NULL,
  ip_hash CHAR(64) NOT NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  attempted_at DATETIME NOT NULL,
  INDEX idx_identifier_date (identifier_hash, attempted_at),
  INDEX idx_ip_date (ip_hash, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UPDATE seo_pages
SET robots = 'noindex, follow',
    sitemap_include = 0,
    updated_at = NOW()
WHERE page_path IN ('/merci-pre-diagnostic-ia/', '/merci-pre-diagnostic-ia');

-- Vérification recommandée après exécution :
-- SELECT page_path, robots, sitemap_include FROM seo_pages WHERE page_path LIKE '/merci-pre-diagnostic-ia%';
