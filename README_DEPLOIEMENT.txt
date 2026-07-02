BENITTAH.COM — DÉPLOIEMENT PHP/MYSQL

Contenu du ZIP
- Site PHP simple, sans framework lourd.
- Contenus JSON dans /data pour le socle historique.
- CRM leads, articles et SEO en base MySQL si la migration SQL est installée.
- Administration dans /admin.
- Sitemap statique /sitemap.xml et générateur admin.

Prérequis IONOS
- PHP 8.0 ou supérieur, recommandé : PHP 8.2+ selon le contrat.
- MySQL avec les tables créées via database/001_create_admin_crm_seo_articles.sql.
- Fonction mail() active ou SMTP configuré dans config/config.local.php.

Configuration
- Copier config/config.example.php vers config/config.local.php côté serveur.
- Renseigner les accès MySQL et, si nécessaire, SMTP.
- Ne jamais versionner config/config.local.php.
- Garder config/.htaccess, logs/.htaccess et database/.htaccess en place.

Accès admin
- URL : /admin/login.php
- Aucun mot de passe ne doit être conservé dans ce fichier.
- Pour initialiser le premier compte, renseigner temporairement FIRST_ADMIN_EMAIL et FIRST_ADMIN_PASSWORD dans config/config.local.php, se connecter une première fois, puis remettre ces deux constantes à null.

Déploiement
1. Uploader les fichiers à la racine du domaine benittah.com.
2. Vérifier que .htaccess et .user.ini sont bien transférés.
3. Importer database/001_create_admin_crm_seo_articles.sql.
4. Tester /evaluer-mon-besoin-ia/ puis une soumission complète.
5. Tester /admin/login.php, /admin/leads/, /admin/articles/ et /admin/seo/.
6. Régénérer le sitemap depuis /admin/sitemap/generate.php si besoin.

Sécurité
- config/config.local.php est ignoré par Git et protégé par config/.htaccess.
- Les logs sont protégés par logs/.htaccess.
- Les erreurs PHP ne sont pas affichées en production via .user.ini et includes/php8_guard.php.
- Le fichier php8-check.php est uniquement un diagnostic temporaire : le supprimer après validation PHP.