<?php
require_once __DIR__ . '/helpers.php';

function diagnostic_choice_options($name) {
  $options = array(
    'taille_entreprise' => array(
      'solo' => 'Indépendant / TPE',
      'pme' => 'PME',
      'eti' => 'ETI',
      'grand_compte' => 'Grand compte',
      'public' => 'Secteur public / association',
    ),
    'outils_ia' => array(
      'aucun' => 'Non, pas encore',
      'tests' => 'Oui, quelques tests ponctuels',
      'regulier' => 'Oui, des usages réguliers existent',
      'structure' => 'Oui, avec des usages déjà structurés',
    ),
    'equipes_ia' => array(
      'non' => 'Non ou très peu',
      'quelques' => 'Quelques personnes les utilisent',
      'plusieurs' => 'Plusieurs équipes les utilisent',
    ),
    'politique_ia' => array(
      'oui' => 'Oui',
      'partiel' => 'Partiellement',
      'non' => 'Non',
    ),
    'cas_usage' => array(
      'aucun' => 'Non, pas encore',
      'idees' => 'Oui, quelques idées',
      'priorises' => 'Oui, des cas d’usage priorisés',
      'pilotes' => 'Oui, des pilotes sont lancés',
    ),
    'automatisation' => array(
      'aucune' => 'Non',
      'quelques' => 'Oui, quelques automatisations',
      'avancee' => 'Oui, plusieurs processus automatisés',
    ),
    'donnees' => array(
      'faible' => 'Peu structurées',
      'partiel' => 'Partiellement structurées',
      'bon' => 'Structurées et accessibles',
    ),
    'responsable_ia' => array(
      'oui' => 'Oui',
      'non' => 'Non',
    ),
    'risques_ia' => array(
      'oui' => 'Oui',
      'partiel' => 'Partiellement',
      'non' => 'Non',
    ),
    'ia_act' => array(
      'oui' => 'Oui',
      'incertain' => 'Je ne sais pas',
      'non' => 'Non',
    ),
    'confidentialite' => array(
      'oui' => 'Oui',
      'partiel' => 'Partiellement',
      'non' => 'Non',
    ),
    'formation_ia' => array(
      'oui' => 'Oui',
      'partiel' => 'Partiellement',
      'non' => 'Non',
    ),
    'urgence' => array(
      'reflexion' => 'Simple réflexion',
      'trois_six_mois' => 'Projet à lancer dans les 3 à 6 mois',
      'trente_jours' => 'Besoin prioritaire dans les 30 jours',
      'urgent' => 'Situation urgente ou risque identifié',
    ),
  );
  return $options[$name] ?? array();
}

function diagnostic_objective_options() {
  return array(
    'temps' => 'Gagner du temps',
    'couts' => 'Réduire les coûts',
    'qualite' => 'Améliorer la qualité',
    'automatisation' => 'Automatiser des tâches répétitives',
    'equipes' => 'Accompagner les équipes',
    'strategie' => 'Structurer une stratégie IA',
    'securiser' => 'Sécuriser les usages IA',
    'roi' => 'Identifier des cas d’usage à ROI rapide',
  );
}

function diagnostic_choice_label($name, $value) {
  $options = diagnostic_choice_options($name);
  return $options[$value] ?? '';
}

function diagnostic_objective_labels($codes) {
  $labels = array();
  $options = diagnostic_objective_options();
  foreach ((array)$codes as $code) {
    if (isset($options[$code])) { $labels[] = $options[$code]; }
  }
  return $labels;
}

function diagnostic_clean_choice($value, $name) {
  $value = clean_text($value, 80);
  $options = diagnostic_choice_options($name);
  return isset($options[$value]) ? $value : '';
}

function diagnostic_clean_objectives($value) {
  $allowed = diagnostic_objective_options();
  $clean = array();
  foreach ((array)$value as $item) {
    $code = clean_text($item, 80);
    if (isset($allowed[$code]) && !in_array($code, $clean, true)) {
      $clean[] = $code;
    }
  }
  return $clean;
}

function diagnostic_score_level($score, $type) {
  $score = max(0, min(100, (int)$score));
  if ($type === 'maturite') {
    if ($score <= 30) { return 'Maturité faible'; }
    if ($score <= 60) { return 'Maturité intermédiaire'; }
    return 'Maturité avancée';
  }
  if ($type === 'risque') {
    if ($score <= 30) { return 'Risque faible'; }
    if ($score <= 60) { return 'Risque modéré'; }
    return 'Risque élevé';
  }
  if ($type === 'opportunite') {
    if ($score <= 30) { return 'Potentiel limité ou encore flou'; }
    if ($score <= 60) { return 'Potentiel intéressant'; }
    return 'Potentiel fort';
  }
  if ($score <= 30) { return 'Faible priorité'; }
  if ($score <= 60) { return 'Priorité moyenne'; }
  return 'Priorité élevée';
}

function diagnostic_offer_catalog() {
  return array(
    'diagnostic' => array('label' => 'Diagnostic IA & Opportunités', 'url' => '/diagnostic-adoption-ia-transformation/'),
    'gouvernance' => array('label' => 'Gouvernance IA & IA Act', 'url' => '/gouvernance-ia-ia-act/'),
    'adoption' => array('label' => 'Adoption IA & Transformation des équipes', 'url' => '/accompagnements/acculturation-ia/'),
    'automatisation' => array('label' => 'Automatisation & Agents IA', 'url' => '/contact/#calendly-widget'),
  );
}

function diagnostic_offer_label($key) {
  $catalog = diagnostic_offer_catalog();
  return $catalog[$key]['label'] ?? $catalog['diagnostic']['label'];
}

function diagnostic_score_answers($answers) {
  $answers = array_merge(array(
    'outils_ia' => '',
    'equipes_ia' => '',
    'politique_ia' => '',
    'cas_usage' => '',
    'automatisation' => '',
    'donnees' => '',
    'responsable_ia' => '',
    'risques_ia' => '',
    'ia_act' => '',
    'confidentialite' => '',
    'formation_ia' => '',
    'objectifs_business' => array(),
    'urgence' => '',
  ), (array)$answers);

  $maturite = 0;
  $maturite += array('aucun'=>0,'tests'=>15,'regulier'=>25,'structure'=>30)[$answers['outils_ia']] ?? 0;
  $maturite += array('non'=>0,'quelques'=>10,'plusieurs'=>20)[$answers['equipes_ia']] ?? 0;
  $maturite += array('aucun'=>0,'idees'=>10,'priorises'=>20,'pilotes'=>25)[$answers['cas_usage']] ?? 0;
  $maturite += array('aucune'=>0,'quelques'=>10,'avancee'=>15)[$answers['automatisation']] ?? 0;
  $maturite += array('faible'=>0,'partiel'=>5,'bon'=>10)[$answers['donnees']] ?? 0;

  // Plus le score est haut, plus le manque de cadre, de conformité ou de gouvernance est fort.
  $risque = 0;
  $risque += array('oui'=>0,'partiel'=>10,'non'=>20)[$answers['politique_ia']] ?? 10;
  $risque += array('oui'=>0,'non'=>15)[$answers['responsable_ia']] ?? 8;
  $risque += array('oui'=>0,'partiel'=>10,'non'=>20)[$answers['risques_ia']] ?? 10;
  $risque += array('oui'=>20,'incertain'=>12,'non'=>0)[$answers['ia_act']] ?? 6;
  $risque += array('oui'=>0,'partiel'=>8,'non'=>15)[$answers['confidentialite']] ?? 8;
  $risque += array('oui'=>0,'partiel'=>5,'non'=>10)[$answers['formation_ia']] ?? 5;

  $objectives = is_array($answers['objectifs_business']) ? $answers['objectifs_business'] : array();
  $opportunite = min(48, count($objectives) * 8);
  $opportunite += array('aucune'=>0,'quelques'=>10,'avancee'=>20)[$answers['automatisation']] ?? 0;
  $opportunite += array('aucun'=>0,'idees'=>8,'priorises'=>16,'pilotes'=>20)[$answers['cas_usage']] ?? 0;
  $opportunite += array('faible'=>0,'partiel'=>5,'bon'=>12)[$answers['donnees']] ?? 0;

  $urgence = array('reflexion'=>15,'trois_six_mois'=>45,'trente_jours'=>75,'urgent'=>100)[$answers['urgence']] ?? 15;
  if ($risque >= 70 && $urgence < 85) { $urgence += 10; }

  return array(
    'score_maturite_ia' => min(100, $maturite),
    'score_gouvernance_risque' => min(100, $risque),
    'score_opportunite_business' => min(100, $opportunite),
    'score_urgence' => min(100, $urgence),
  );
}

function diagnostic_recommend_offer($answers, $scores) {
  $answers = array_merge(array(
    'outils_ia' => '',
    'equipes_ia' => '',
    'cas_usage' => '',
    'formation_ia' => '',
    'objectifs_business' => array(),
  ), (array)$answers);

  $objectives = is_array($answers['objectifs_business']) ? $answers['objectifs_business'] : array();
  $usagePresent = in_array($answers['outils_ia'], array('regulier','structure'), true)
    || in_array($answers['equipes_ia'], array('quelques','plusieurs'), true)
    || in_array($answers['cas_usage'], array('priorises','pilotes'), true);
  $teamsNeedSupport = $usagePresent && $answers['formation_ia'] !== 'oui';
  $automationNeed = in_array('automatisation', $objectives, true)
    || in_array('temps', $objectives, true)
    || in_array('couts', $objectives, true);
  $objectivesBlurred = count($objectives) <= 1 && $scores['score_opportunite_business'] <= 30;
  $maxOther = max($scores['score_maturite_ia'], $scores['score_opportunite_business'], $scores['score_urgence']);

  // En cas d’égalité, on reste prudent : le diagnostic initial reste la recommandation par défaut.
  if ($scores['score_gouvernance_risque'] > $maxOther && $scores['score_gouvernance_risque'] > 30) {
    return array('key' => 'gouvernance', 'reason' => 'Vos réponses montrent un enjeu prioritaire de cadre, de confidentialité, de risques ou de conformité. Un accompagnement gouvernance permet de sécuriser les usages avant d’élargir l’adoption.');
  }
  if ($scores['score_maturite_ia'] <= 30 && $objectivesBlurred) {
    return array('key' => 'diagnostic', 'reason' => 'Votre maturité IA semble encore émergente. Le meilleur premier pas est d’identifier les cas d’usage utiles, d’évaluer la faisabilité et de prioriser une feuille de route claire.');
  }
  if ($teamsNeedSupport) {
    return array('key' => 'adoption', 'reason' => 'Des usages IA existent déjà, mais les réponses indiquent un besoin de cadrage, de formation ou d’accompagnement des équipes pour rendre l’adoption plus sûre et plus utile.');
  }
  if ($automationNeed) {
    return array('key' => 'automatisation', 'reason' => 'Vos objectifs mettent en avant le gain de temps, l’automatisation ou l’industrialisation de processus. Un cadrage Automatisation & Agents IA permettra d’identifier les workflows à plus fort impact.');
  }
  return array('key' => 'diagnostic', 'reason' => 'Vos réponses méritent un cadrage initial pour clarifier les priorités, comparer les opportunités et choisir le bon niveau d’accompagnement sans surdimensionner la démarche.');
}

function diagnostic_score_lead($answers) {
  $scores = diagnostic_score_answers($answers);
  $recommendation = diagnostic_recommend_offer($answers, $scores);
  return array_merge($scores, array(
    'niveau_maturite' => diagnostic_score_level($scores['score_maturite_ia'], 'maturite'),
    'niveau_risque' => diagnostic_score_level($scores['score_gouvernance_risque'], 'risque'),
    'niveau_opportunite' => diagnostic_score_level($scores['score_opportunite_business'], 'opportunite'),
    'niveau_urgence' => diagnostic_score_level($scores['score_urgence'], 'urgence'),
    'offre_recommandee' => diagnostic_offer_label($recommendation['key']),
    'offre_recommandee_key' => $recommendation['key'],
    'explication_recommandation' => $recommendation['reason'],
  ));
}

function diagnostic_public_answers($answers) {
  return array(
    'taille_entreprise' => diagnostic_choice_label('taille_entreprise', $answers['taille_entreprise']),
    'secteur_activite' => $answers['secteur_activite'],
    'outils_ia' => diagnostic_choice_label('outils_ia', $answers['outils_ia']),
    'equipes_ia' => diagnostic_choice_label('equipes_ia', $answers['equipes_ia']),
    'politique_ia' => diagnostic_choice_label('politique_ia', $answers['politique_ia']),
    'cas_usage' => diagnostic_choice_label('cas_usage', $answers['cas_usage']),
    'automatisation' => diagnostic_choice_label('automatisation', $answers['automatisation']),
    'donnees' => diagnostic_choice_label('donnees', $answers['donnees']),
    'responsable_ia' => diagnostic_choice_label('responsable_ia', $answers['responsable_ia']),
    'risques_ia' => diagnostic_choice_label('risques_ia', $answers['risques_ia']),
    'ia_act' => diagnostic_choice_label('ia_act', $answers['ia_act']),
    'confidentialite' => diagnostic_choice_label('confidentialite', $answers['confidentialite']),
    'formation_ia' => diagnostic_choice_label('formation_ia', $answers['formation_ia']),
    'objectifs_business' => diagnostic_objective_labels($answers['objectifs_business']),
    'urgence' => diagnostic_choice_label('urgence', $answers['urgence']),
  );
}
