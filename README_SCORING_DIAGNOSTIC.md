# Scoring du Diagnostic Transformation 360°

Le scoring est centralisé dans `includes/transformation_assessment.php`. L’ancien fichier `includes/diagnostic_scoring.php` est conservé comme wrapper de compatibilité, sans règles métier propres.

## Dimensions calculées

Chaque dimension est notée sur 100 :

- `score_strategie_leadership` : vision, priorités, sponsoring direction, mesure des résultats.
- `score_organisation_agilite` : autonomie, coopération métiers/tech, vitesse de décision, livraison régulière.
- `score_maturite_ia` : usages IA, outils, cas d’usage, mesure de la valeur.
- `score_gouvernance` : comptes personnels, confidentialité, politique IA, cartographie, responsabilités.
- `score_adoption` : compréhension, managers, formation, adoption durable.
- `score_automatisation` : tâches répétitives, processus manuels, outils connectés, potentiel agents IA.
- `score_transformation_global` : moyenne pondérée des six dimensions.

## Indicateurs complémentaires

- `score_risque` augmente avec l’exposition : comptes personnels, données confidentielles, absence de politique, absence de cartographie, obligations mal maîtrisées.
- `score_creation_valeur` augmente avec les irritants opérationnels, objectifs business, potentiel agents IA et temporalité.
- `score_urgence` dépend de la temporalité déclarée.

## Niveaux

Dimensions : `Initial`, `En structuration`, `Maîtrisé`, `Avancé`.

Score global : `Transformation à initier`, `Transformation en structuration`, `Transformation en accélération`, `Transformation maîtrisée`.

Risque : `Risque faible`, `Risque modéré`, `Risque important`, `Risque critique`.

Création de valeur : `Potentiel à préciser`, `Potentiel intéressant`, `Potentiel élevé`, `Potentiel stratégique`.

## Recommandation

Le moteur produit une recommandation principale, jusqu’à deux recommandations complémentaires, une explication et trois prochaines étapes.

Priorité des règles :

1. Risque élevé : `Gouvernance IA et IA Act`.
2. Fragilités transverses ou priorités floues : `Diagnostic Transformation 360°`.
3. Potentiel opérationnel fort : `Automatisation et agents IA`.
4. Outils IA présents mais adoption insuffisante : `Adoption IA et transformation des équipes`.
5. Exécution, coordination ou priorités instables : `Transformation des organisations et agilité`.
6. Vision, arbitrage ou alignement direction insuffisant : `Accompagnement de dirigeants et leadership`.

En cas de scores proches ou d’incertitude, le moteur revient volontairement vers `Diagnostic Transformation 360°`.

## Tests de scénarios

Le script `tools/test_transformation_assessment.php` vérifie les six scénarios fonctionnels demandés :

- organisation sans feuille de route claire ;
- organisation exposée aux risques IA ;
- organisation équipée mais adoption insuffisante ;
- potentiel élevé d’automatisation ;
- difficultés organisationnelles ;
- enjeu de direction et d’alignement.

Commande :

```bash
php tools/test_transformation_assessment.php
```
