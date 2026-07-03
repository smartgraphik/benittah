<?php
// Copier ce fichier vers config.local.php et renseigner les vraies valeurs côté serveur.

define('DB_HOST', 'db5020807240.hosting-data.io');
define('DB_NAME', 'dbs15843398');
define('DB_USER', 'dbu3677171');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('SITE_URL', 'https://benittah.com');
define('ADMIN_NOTIFICATION_EMAIL', 'cedrick@benittah.com');

// Création automatique du premier admin si la table admin_users est vide.
// Laisser null après création du compte.
define('FIRST_ADMIN_EMAIL', null);
define('FIRST_ADMIN_PASSWORD', null);

// SMTP optionnel. Si ces constantes restent nulles, mail() sera utilisé.
define('SMTP_HOST', null);
define('SMTP_PORT', 587);
define('SMTP_USER', null);
define('SMTP_PASS', null);
define('SMTP_FROM_EMAIL', 'cedrick@benittah.com');
define('SMTP_FROM_NAME', 'benittah.com');

// Sécurité admin. Laisser ADMIN_SECURITY_SECRET null utilise un secret serveur existant si disponible.
define('ADMIN_SECURITY_SECRET', null);
define('TRUST_PROXY_HEADERS', false);
define('ADMIN_SESSION_IDLE_TIMEOUT', 1800);
define('ADMIN_SESSION_ABSOLUTE_TIMEOUT', 28800);
define('ADMIN_LOGIN_MAX_ATTEMPTS_ACCOUNT', 5);
define('ADMIN_LOGIN_MAX_ATTEMPTS_IP', 15);
define('ADMIN_LOGIN_WINDOW_SECONDS', 900);
define('ADMIN_LOGIN_LOCK_ACCOUNT_SECONDS', 900);
define('ADMIN_LOGIN_LOCK_IP_SECONDS', 1800);
define('ADMIN_LOGIN_ATTEMPT_RETENTION_SECONDS', 2592000);
