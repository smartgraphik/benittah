-- Migration 002 — qualification commerciale du diagnostic IA.
-- À exécuter une fois sur une base existante déjà créée avec 001_create_admin_crm_seo_articles.sql.
-- Version volontairement simple, sans procédure stockée, pour rester compatible hébergement mutualisé IONOS/phpMyAdmin.

ALTER TABLE leads_diagnostic_ia
  ADD COLUMN prenom VARCHAR(120) NULL AFTER source_offre,
  ADD COLUMN taille_entreprise VARCHAR(120) NULL AFTER role_contact,
  ADD COLUMN secteur_activite VARCHAR(190) NULL AFTER taille_entreprise,
  ADD COLUMN score_maturite_ia TINYINT UNSIGNED NULL AFTER date_relance,
  ADD COLUMN score_gouvernance_risque TINYINT UNSIGNED NULL AFTER score_maturite_ia,
  ADD COLUMN score_opportunite_business TINYINT UNSIGNED NULL AFTER score_gouvernance_risque,
  ADD COLUMN score_urgence TINYINT UNSIGNED NULL AFTER score_opportunite_business,
  ADD COLUMN niveau_maturite VARCHAR(80) NULL AFTER score_urgence,
  ADD COLUMN niveau_risque VARCHAR(80) NULL AFTER niveau_maturite,
  ADD COLUMN niveau_opportunite VARCHAR(80) NULL AFTER niveau_risque,
  ADD COLUMN niveau_urgence VARCHAR(80) NULL AFTER niveau_opportunite,
  ADD COLUMN offre_recommandee VARCHAR(190) NULL AFTER niveau_urgence,
  ADD COLUMN explication_recommandation TEXT NULL AFTER offre_recommandee,
  ADD COLUMN raw_answers_json LONGTEXT NULL AFTER explication_recommandation,
  ADD INDEX idx_leads_offre_recommandee (offre_recommandee),
  ADD INDEX idx_leads_score_urgence (score_urgence);