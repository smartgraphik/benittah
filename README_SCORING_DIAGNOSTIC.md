# Scoring du diagnostic IA

Le scoring est centralisé dans `includes/diagnostic_scoring.php`.

## Scores calculés

- `score_maturite_ia` : usage actuel des outils IA, adoption par les équipes, cas d’usage, automatisations et état des données.
- `score_gouvernance_risque` : absence de politique IA, absence de responsable, risques non évalués, exposition IA Act, confidentialité et formation.
- `score_opportunite_business` : nombre et nature des objectifs business, automatisation, cas d’usage et disponibilité des données.
- `score_urgence` : niveau d’urgence déclaré, renforcé légèrement si le risque est élevé.

Chaque score est ramené sur 100.

## Niveaux

- 0 à 30 : niveau faible / priorité faible / potentiel limité.
- 31 à 60 : niveau intermédiaire ou modéré.
- 61 à 100 : niveau avancé, élevé ou fort.

## Recommandation commerciale

La recommandation applique des règles simples :

1. Si le risque / gouvernance domine strictement les autres scores, recommander `Gouvernance IA & IA Act`.
2. Si la maturité IA est faible et les objectifs encore flous, recommander `Diagnostic IA & Opportunités`.
3. Si des usages IA existent mais que les équipes ne sont pas formées ou cadrées, recommander `Adoption IA & Transformation des équipes`.
4. Si les objectifs portent sur le gain de temps, les coûts ou l’automatisation, recommander `Automatisation & Agents IA`.
5. En cas d’égalité ou de situation ambiguë, recommander par prudence `Diagnostic IA & Opportunités`.

Le JSON détaillé des réponses est enregistré en CRM (`raw_answers_json`) mais n’est pas affiché côté public.