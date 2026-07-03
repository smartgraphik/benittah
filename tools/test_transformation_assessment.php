<?php
require __DIR__ . '/../includes/transformation_assessment.php';

$base = array(
  'vision_transformation'=>'partagee',
  'priorites_comprises'=>'largement',
  'sponsor_direction'=>'codir',
  'mesure_resultats'=>'business',
  'autonomie_equipes'=>'forte',
  'collaboration_metier_tech'=>'integree',
  'decision_rapide'=>'cadence',
  'livraison_reguliere'=>'continue',
  'usages_ia'=>'structures',
  'outils_ia'=>array('copilot','metiers'),
  'cas_usage_ia'=>'pilotes',
  'mesure_valeur_ia'=>'industrialisee',
  'comptes_personnels'=>'interdit',
  'donnees_confidentielles'=>'interdit',
  'politique_ia'=>'diffusee',
  'cartographie_usages'=>'pilotee',
  'responsabilites_obligations'=>'formalisees',
  'comprehension_objectifs'=>'forte',
  'managers_outilles'=>'solide',
  'formation_collaborateurs'=>'continue',
  'adoption_durable'=>'ancree',
  'taches_repetitives'=>'limitees',
  'processus_manuels'=>'peu',
  'outils_connectes'=>'majorite',
  'potentiel_agents'=>'idees',
  'objectifs_business'=>array('qualite'),
  'temporalite_action'=>'six_mois',
  'priorite_principale'=>'cas_usage',
  'budget'=>'aucun',
  'taille_organisation'=>'50_249',
);

$scenarios = array(
  'Organisation sans feuille de route claire' => array(
    'expected'=>'Diagnostic Transformation 360°',
    'answers'=>array_merge($base, array(
      'vision_transformation'=>'absente',
      'priorites_comprises'=>'floues',
      'sponsor_direction'=>'aucun',
      'mesure_resultats'=>'non',
      'autonomie_equipes'=>'faible',
      'collaboration_metier_tech'=>'silos',
      'decision_rapide'=>'lente',
      'livraison_reguliere'=>'rare',
      'usages_ia'=>'tests',
      'outils_ia'=>array('chatgpt'),
      'cas_usage_ia'=>'idees',
      'mesure_valeur_ia'=>'non',
      'comprehension_objectifs'=>'faible',
      'managers_outilles'=>'non',
      'formation_collaborateurs'=>'non',
      'adoption_durable'=>'fragile',
      'taches_repetitives'=>'nombreuses',
      'processus_manuels'=>'plusieurs',
      'outils_connectes'=>'partiel',
      'potentiel_agents'=>'idees',
      'objectifs_business'=>array('qualite','decision','equipes','performance_projets'),
      'temporalite_action'=>'trois_mois',
      'priorite_principale'=>'cas_usage',
    )),
  ),
  'Organisation exposée aux risques IA' => array(
    'expected'=>'Gouvernance IA et IA Act',
    'answers'=>array_merge($base, array(
      'comptes_personnels'=>'frequent',
      'donnees_confidentielles'=>'possible',
      'politique_ia'=>'non',
      'cartographie_usages'=>'aucune',
      'responsabilites_obligations'=>'inconnues',
      'priorite_principale'=>'securiser_ia',
    )),
  ),
  'Organisation équipée mais adoption insuffisante' => array(
    'expected'=>'Adoption IA et transformation des équipes',
    'answers'=>array_merge($base, array(
      'usages_ia'=>'reguliers',
      'outils_ia'=>array('chatgpt','copilot','gemini'),
      'cas_usage_ia'=>'priorises',
      'comprehension_objectifs'=>'variable',
      'managers_outilles'=>'non',
      'formation_collaborateurs'=>'non',
      'adoption_durable'=>'inegale',
      'priorite_principale'=>'equipes',
    )),
  ),
  'Potentiel élevé d’automatisation' => array(
    'expected'=>'Automatisation et agents IA',
    'answers'=>array_merge($base, array(
      'taches_repetitives'=>'nombreuses',
      'processus_manuels'=>'nombreux',
      'outils_connectes'=>'non',
      'potentiel_agents'=>'cas_priorises',
      'objectifs_business'=>array('temps','couts','automatisation','agents_ia','connecter_outils'),
      'temporalite_action'=>'mois',
      'priorite_principale'=>'automatiser',
    )),
  ),
  'Difficultés organisationnelles' => array(
    'expected'=>'Transformation des organisations et agilité',
    'answers'=>array_merge($base, array(
      'autonomie_equipes'=>'faible',
      'collaboration_metier_tech'=>'silos',
      'decision_rapide'=>'lente',
      'livraison_reguliere'=>'rare',
      'priorite_principale'=>'organisation',
    )),
  ),
  'Enjeu de direction et d’alignement' => array(
    'expected'=>'Accompagnement de dirigeants et leadership',
    'answers'=>array_merge($base, array(
      'vision_transformation'=>'absente',
      'priorites_comprises'=>'floues',
      'sponsor_direction'=>'aucun',
      'mesure_resultats'=>'quelques',
      'priorite_principale'=>'dirigeants',
    )),
  ),
);

$failed = 0;
foreach ($scenarios as $name => $scenario) {
  $result = transformation_assess($scenario['answers']);
  $actual = $result['recommandation_principale'] ?? '';
  $ok = $actual === $scenario['expected'];
  echo ($ok ? 'OK' : 'KO') . ' - ' . $name . ' => ' . $actual . PHP_EOL;
  if (!$ok) {
    echo '  Attendu : ' . $scenario['expected'] . PHP_EOL;
    $failed++;
  }
}

if ($failed > 0) {
  exit(1);
}

echo 'Tous les scénarios de scoring sont conformes.' . PHP_EOL;
