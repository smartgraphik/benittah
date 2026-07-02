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

