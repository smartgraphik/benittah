-- Migration 003 — Diagnostic Transformation 360°.
-- À exécuter après 002 sur une base historique.
-- Sur une base neuve créée avec le 001 actuel, cette migration est sans effet car les colonnes existent déjà.
-- Le script reste compatible phpMyAdmin : chaque ajout vérifie INFORMATION_SCHEMA avant ALTER TABLE.

SET @db_name = DATABASE();
SET @table_name = 'leads_diagnostic_ia';

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN localisation VARCHAR(190) NULL AFTER secteur_activite', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'localisation');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN chiffre_affaires VARCHAR(190) NULL AFTER localisation', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'chiffre_affaires');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_strategie_leadership TINYINT UNSIGNED NULL AFTER date_relance', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_strategie_leadership');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_organisation_agilite TINYINT UNSIGNED NULL AFTER score_strategie_leadership', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_organisation_agilite');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_gouvernance TINYINT UNSIGNED NULL AFTER score_maturite_ia', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_gouvernance');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_adoption TINYINT UNSIGNED NULL AFTER score_gouvernance', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_adoption');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_automatisation TINYINT UNSIGNED NULL AFTER score_adoption', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_automatisation');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_transformation_global TINYINT UNSIGNED NULL AFTER score_automatisation', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_transformation_global');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_risque TINYINT UNSIGNED NULL AFTER score_transformation_global', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_risque');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN score_creation_valeur TINYINT UNSIGNED NULL AFTER score_risque', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'score_creation_valeur');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN niveau_transformation VARCHAR(120) NULL AFTER niveau_maturite', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'niveau_transformation');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN niveau_creation_valeur VARCHAR(80) NULL AFTER niveau_risque', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'niveau_creation_valeur');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN priorite_principale VARCHAR(190) NULL AFTER offre_recommandee', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'priorite_principale');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN recommandation_principale VARCHAR(190) NULL AFTER priorite_principale', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'recommandation_principale');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN recommandations_secondaires_json LONGTEXT NULL AFTER recommandation_principale', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'recommandations_secondaires_json');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN prochaines_etapes_json LONGTEXT NULL AFTER explication_recommandation', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'prochaines_etapes_json');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN utm_source VARCHAR(190) NULL AFTER raw_answers_json', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'utm_source');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN utm_medium VARCHAR(190) NULL AFTER utm_source', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'utm_medium');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN utm_campaign VARCHAR(190) NULL AFTER utm_medium', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'utm_campaign');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN utm_content VARCHAR(190) NULL AFTER utm_campaign', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'utm_content');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN utm_term VARCHAR(190) NULL AFTER utm_content', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'utm_term');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN referrer_url VARCHAR(500) NULL AFTER utm_term', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'referrer_url');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD COLUMN temps_completion_secondes INT UNSIGNED NULL AFTER referrer_url', 'SELECT 1') FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND COLUMN_NAME = 'temps_completion_secondes');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD INDEX idx_leads_score_global (score_transformation_global)', 'SELECT 1') FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND INDEX_NAME = 'idx_leads_score_global');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE leads_diagnostic_ia ADD INDEX idx_leads_recommandation (recommandation_principale)', 'SELECT 1') FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = @table_name AND INDEX_NAME = 'idx_leads_recommandation');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE seo_pages
SET page_label = 'Diagnostic Transformation 360°',
    meta_title = 'Diagnostic Transformation 360° — Cédrick Benittah',
    meta_description = 'Évaluez votre maturité de transformation : stratégie, organisation, IA, gouvernance, adoption, automatisation et feuille de route 30 / 60 / 90 jours.'
WHERE page_path = '/diagnostic-adoption-ia-transformation/';

UPDATE seo_pages
SET meta_title = 'Offres — Diagnostic Transformation 360°, Gouvernance IA & Adoption',
    meta_description = 'Trois accompagnements pour cadrer votre transformation : Diagnostic Transformation 360°, Gouvernance IA & IA Act, Accompagnement Adoption IA.'
WHERE page_path = '/accompagnements/';

-- Rollback indicatif si la migration doit etre annulee manuellement :
-- ALTER TABLE leads_diagnostic_ia
--   DROP INDEX idx_leads_recommandation,
--   DROP INDEX idx_leads_score_global,
--   DROP COLUMN temps_completion_secondes,
--   DROP COLUMN referrer_url,
--   DROP COLUMN utm_term,
--   DROP COLUMN utm_content,
--   DROP COLUMN utm_campaign,
--   DROP COLUMN utm_medium,
--   DROP COLUMN utm_source,
--   DROP COLUMN prochaines_etapes_json,
--   DROP COLUMN recommandations_secondaires_json,
--   DROP COLUMN recommandation_principale,
--   DROP COLUMN priorite_principale,
--   DROP COLUMN niveau_creation_valeur,
--   DROP COLUMN niveau_transformation,
--   DROP COLUMN score_creation_valeur,
--   DROP COLUMN score_risque,
--   DROP COLUMN score_transformation_global,
--   DROP COLUMN score_automatisation,
--   DROP COLUMN score_adoption,
--   DROP COLUMN score_gouvernance,
--   DROP COLUMN score_organisation_agilite,
--   DROP COLUMN score_strategie_leadership,
--   DROP COLUMN chiffre_affaires,
--   DROP COLUMN localisation;
