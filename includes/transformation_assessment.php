<?php
require_once __DIR__ . '/helpers.php';

function transformation_assessment_question_config() {
  return array(
    'vision_transformation' => array('dimension'=>'strategie_leadership','label'=>'Votre organisation dispose-t-elle d’une vision claire de sa transformation ?','weight'=>1.2,'options'=>array(
      'absente'=>array('label'=>'Pas encore formalisée','score'=>20),
      'partielle'=>array('label'=>'Partielle ou portée par quelques personnes','score'=>45),
      'claire'=>array('label'=>'Claire et connue des principaux décideurs','score'=>70),
      'partagee'=>array('label'=>'Claire, partagée et reliée aux priorités business','score'=>90),
    )),
    'priorites_comprises' => array('dimension'=>'strategie_leadership','label'=>'Les priorités de transformation sont-elles comprises par les équipes ?','weight'=>1,'options'=>array(
      'floues'=>array('label'=>'Elles restent floues','score'=>20),
      'direction'=>array('label'=>'Surtout au niveau de la direction','score'=>45),
      'majorite'=>array('label'=>'Par la majorité des équipes concernées','score'=>70),
      'largement'=>array('label'=>'Oui, avec des arbitrages lisibles','score'=>90),
    )),
    'sponsor_direction' => array('dimension'=>'strategie_leadership','label'=>'Un membre de la direction porte-t-il activement les projets de transformation ?','weight'=>1,'options'=>array(
      'aucun'=>array('label'=>'Pas de sponsor identifié','score'=>20),
      'ponctuel'=>array('label'=>'Un appui ponctuel existe','score'=>45),
      'actif'=>array('label'=>'Un sponsor actif est identifié','score'=>75),
      'codir'=>array('label'=>'La direction arbitre et suit régulièrement','score'=>90),
    )),
    'mesure_resultats' => array('dimension'=>'strategie_leadership','label'=>'Les résultats attendus sont-ils mesurés ?','weight'=>1,'options'=>array(
      'non'=>array('label'=>'Pas vraiment','score'=>20),
      'quelques'=>array('label'=>'Quelques indicateurs existent','score'=>45),
      'suivis'=>array('label'=>'Des indicateurs sont suivis','score'=>75),
      'business'=>array('label'=>'Ils sont reliés aux impacts business','score'=>90),
    )),
    'autonomie_equipes' => array('dimension'=>'organisation_agilite','label'=>'Les équipes disposent-elles d’une autonomie adaptée pour avancer ?','weight'=>1,'options'=>array(
      'faible'=>array('label'=>'Faible autonomie','score'=>25),
      'variable'=>array('label'=>'Variable selon les équipes','score'=>50),
      'bonne'=>array('label'=>'Globalement bonne','score'=>75),
      'forte'=>array('label'=>'Forte, avec un cadre clair','score'=>90),
    )),
    'collaboration_metier_tech' => array('dimension'=>'organisation_agilite','label'=>'Métiers et équipes technologiques travaillent-ils efficacement ensemble ?','weight'=>1.1,'options'=>array(
      'silos'=>array('label'=>'Encore beaucoup en silos','score'=>20),
      'ponctuelle'=>array('label'=>'Collaboration ponctuelle','score'=>45),
      'reguliere'=>array('label'=>'Collaboration régulière','score'=>75),
      'integree'=>array('label'=>'Co-construction fluide et intégrée','score'=>90),
    )),
    'decision_rapide' => array('dimension'=>'organisation_agilite','label'=>'Les décisions sont-elles prises assez rapidement ?','weight'=>1,'options'=>array(
      'lente'=>array('label'=>'Souvent trop lentement','score'=>20),
      'variable'=>array('label'=>'Cela dépend des sujets','score'=>50),
      'rapide'=>array('label'=>'Plutôt rapidement','score'=>75),
      'cadence'=>array('label'=>'Avec une cadence d’arbitrage claire','score'=>90),
    )),
    'livraison_reguliere' => array('dimension'=>'organisation_agilite','label'=>'Les projets produisent-ils régulièrement des résultats visibles ?','weight'=>1.1,'options'=>array(
      'rare'=>array('label'=>'Rarement','score'=>20),
      'irreguliere'=>array('label'=>'De manière irrégulière','score'=>45),
      'reguliere'=>array('label'=>'Oui, régulièrement','score'=>75),
      'continue'=>array('label'=>'Oui, avec amélioration continue','score'=>90),
    )),
    'usages_ia' => array('dimension'=>'maturite_ia','label'=>'Vos collaborateurs utilisent-ils déjà des outils d’IA générative ?','weight'=>1,'options'=>array(
      'aucun'=>array('label'=>'Pas encore','score'=>15),
      'tests'=>array('label'=>'Quelques tests individuels','score'=>40),
      'reguliers'=>array('label'=>'Des usages réguliers existent','score'=>65),
      'structures'=>array('label'=>'Des usages organisés et cadrés existent','score'=>85),
    )),
    'outils_ia' => array('dimension'=>'maturite_ia','label'=>'Quels outils IA sont utilisés ?','weight'=>.8,'type'=>'multi','options'=>array(
      'chatgpt'=>'ChatGPT','copilot'=>'Microsoft Copilot','gemini'=>'Gemini','claude'=>'Claude','mistral'=>'Mistral','metiers'=>'Outils métiers intégrant de l’IA','interne'=>'Solutions développées en interne','aucun'=>'Aucun outil','autre'=>'Autre',
    )),
    'cas_usage_ia' => array('dimension'=>'maturite_ia','label'=>'Des cas d’usage IA prioritaires ont-ils été identifiés ?','weight'=>1.1,'options'=>array(
      'aucun'=>array('label'=>'Pas encore','score'=>20),
      'idees'=>array('label'=>'Quelques idées existent','score'=>45),
      'priorises'=>array('label'=>'Des cas d’usage sont priorisés','score'=>70),
      'pilotes'=>array('label'=>'Des pilotes ou preuves de concept sont lancés','score'=>85),
    )),
    'mesure_valeur_ia' => array('dimension'=>'maturite_ia','label'=>'Mesurez-vous les gains générés par les usages IA ?','weight'=>1,'options'=>array(
      'non'=>array('label'=>'Non','score'=>20),
      'estimee'=>array('label'=>'De façon estimative','score'=>45),
      'suivie'=>array('label'=>'Avec quelques indicateurs suivis','score'=>70),
      'industrialisee'=>array('label'=>'Oui, avec mesure régulière de la valeur','score'=>85),
    )),
    'comptes_personnels' => array('dimension'=>'gouvernance','label'=>'Des comptes personnels sont-ils utilisés pour des usages professionnels ?','weight'=>1,'risk'=>array('frequent'=>90,'parfois'=>65,'encadre'=>30,'interdit'=>10),'options'=>array(
      'frequent'=>array('label'=>'Oui, fréquemment','score'=>20),'parfois'=>array('label'=>'Parfois','score'=>45),'encadre'=>array('label'=>'Seulement dans un cadre défini','score'=>70),'interdit'=>array('label'=>'Non, c’est interdit ou maîtrisé','score'=>90),
    )),
    'donnees_confidentielles' => array('dimension'=>'gouvernance','label'=>'Des données confidentielles peuvent-elles être envoyées à des outils IA publics ?','weight'=>1.1,'risk'=>array('possible'=>95,'limite'=>70,'encadre'=>25,'interdit'=>10),'options'=>array(
      'possible'=>array('label'=>'Oui, c’est possible','score'=>20),'limite'=>array('label'=>'Le risque existe mais reste limité','score'=>45),'encadre'=>array('label'=>'Un cadre de confidentialité existe','score'=>75),'interdit'=>array('label'=>'Non, les règles sont claires','score'=>90),
    )),
    'politique_ia' => array('dimension'=>'gouvernance','label'=>'Disposez-vous d’une charte ou politique interne consacrée à l’IA ?','weight'=>1,'risk'=>array('non'=>85,'brouillon'=>55,'oui'=>25,'diffusee'=>10),'options'=>array(
      'non'=>array('label'=>'Non','score'=>20),'brouillon'=>array('label'=>'En cours de préparation','score'=>45),'oui'=>array('label'=>'Oui, elle existe','score'=>75),'diffusee'=>array('label'=>'Oui, diffusée et comprise','score'=>90),
    )),
    'cartographie_usages' => array('dimension'=>'gouvernance','label'=>'Les usages IA sont-ils recensés ou cartographiés ?','weight'=>1,'risk'=>array('aucune'=>80,'partielle'=>55,'tenue'=>25,'pilotee'=>10),'options'=>array(
      'aucune'=>array('label'=>'Non','score'=>20),'partielle'=>array('label'=>'Partiellement','score'=>50),'tenue'=>array('label'=>'Oui, une cartographie existe','score'=>75),'pilotee'=>array('label'=>'Oui, elle est tenue à jour et pilotée','score'=>90),
    )),
    'responsabilites_obligations' => array('dimension'=>'gouvernance','label'=>'Les responsabilités et obligations IA applicables sont-elles maîtrisées ?','weight'=>1,'risk'=>array('inconnues'=>80,'identifiees'=>55,'responsable'=>25,'formalisees'=>10),'options'=>array(
      'inconnues'=>array('label'=>'Pas encore','score'=>20),'identifiees'=>array('label'=>'Partiellement identifiées','score'=>45),'responsable'=>array('label'=>'Un responsable et un cadre existent','score'=>75),'formalisees'=>array('label'=>'Responsabilités, IA Act et fournisseurs sont suivis','score'=>90),
    )),
    'comprehension_objectifs' => array('dimension'=>'adoption','label'=>'Les collaborateurs comprennent-ils les raisons des transformations engagées ?','weight'=>1,'options'=>array(
      'faible'=>array('label'=>'Pas suffisamment','score'=>25),'variable'=>array('label'=>'De manière variable','score'=>50),'bonne'=>array('label'=>'Globalement oui','score'=>75),'forte'=>array('label'=>'Oui, avec un récit clair','score'=>90),
    )),
    'managers_outilles' => array('dimension'=>'adoption','label'=>'Les managers ont-ils les moyens d’accompagner leurs équipes ?','weight'=>1,'options'=>array(
      'non'=>array('label'=>'Pas assez','score'=>20),'partiel'=>array('label'=>'Partiellement','score'=>45),'oui'=>array('label'=>'Oui, pour les principaux changements','score'=>75),'solide'=>array('label'=>'Oui, avec un dispositif solide','score'=>90),
    )),
    'formation_collaborateurs' => array('dimension'=>'adoption','label'=>'Les collaborateurs sont-ils formés aux nouveaux outils et pratiques ?','weight'=>1.1,'options'=>array(
      'non'=>array('label'=>'Non','score'=>20),'pilotes'=>array('label'=>'Quelques populations pilotes','score'=>45),'plan'=>array('label'=>'Un plan de formation est engagé','score'=>75),'continue'=>array('label'=>'Formation continue et ancrage terrain','score'=>90),
    )),
    'adoption_durable' => array('dimension'=>'adoption','label'=>'Les nouvelles pratiques sont-elles réellement adoptées dans la durée ?','weight'=>1,'options'=>array(
      'fragile'=>array('label'=>'L’adoption reste fragile','score'=>25),'inegale'=>array('label'=>'Elle est inégale','score'=>50),'reguliere'=>array('label'=>'Elle progresse régulièrement','score'=>75),'ancree'=>array('label'=>'Elle est ancrée et mesurée','score'=>90),
    )),
    'taches_repetitives' => array('dimension'=>'automatisation','label'=>'Quelle est la part des tâches répétitives à faible valeur ajoutée ?','weight'=>1,'value'=>array('nombreuses'=>90,'certaines'=>65,'limitees'=>35,'automatisees'=>20),'options'=>array(
      'nombreuses'=>array('label'=>'Nombreuses','score'=>25),'certaines'=>array('label'=>'Certaines tâches sont concernées','score'=>50),'limitees'=>array('label'=>'Elles sont limitées','score'=>75),'automatisees'=>array('label'=>'Elles sont déjà largement automatisées','score'=>90),
    )),
    'processus_manuels' => array('dimension'=>'automatisation','label'=>'Vos processus reposent-ils encore sur des traitements manuels ou doubles saisies ?','weight'=>1,'value'=>array('nombreux'=>90,'plusieurs'=>70,'peu'=>35,'optimises'=>15),'options'=>array(
      'nombreux'=>array('label'=>'Oui, beaucoup','score'=>25),'plusieurs'=>array('label'=>'Oui, plusieurs processus','score'=>50),'peu'=>array('label'=>'Quelques irritants seulement','score'=>75),'optimises'=>array('label'=>'Les processus clés sont optimisés','score'=>90),
    )),
    'outils_connectes' => array('dimension'=>'automatisation','label'=>'Vos outils sont-ils correctement connectés entre eux ?','weight'=>1,'value'=>array('non'=>85,'partiel'=>60,'majorite'=>30,'integres'=>15),'options'=>array(
      'non'=>array('label'=>'Non, beaucoup d’outils restent isolés','score'=>20),'partiel'=>array('label'=>'Partiellement','score'=>50),'majorite'=>array('label'=>'La majorité des outils clés est connectée','score'=>75),'integres'=>array('label'=>'Les workflows sont bien intégrés','score'=>90),
    )),
    'potentiel_agents' => array('dimension'=>'automatisation','label'=>'Avez-vous identifié des usages possibles d’agents IA ou d’automatisations avancées ?','weight'=>1,'value'=>array('non_identifie'=>30,'idees'=>55,'cas_priorises'=>80,'pilote'=>90),'options'=>array(
      'non_identifie'=>array('label'=>'Pas encore','score'=>25),'idees'=>array('label'=>'Quelques idées existent','score'=>50),'cas_priorises'=>array('label'=>'Des cas sont priorisés','score'=>75),'pilote'=>array('label'=>'Un pilote ou une solution existe déjà','score'=>90),
    )),
  );
}

function transformation_assessment_dimensions() {
  return array(
    'strategie_leadership' => array('label'=>'Stratégie et leadership','weight'=>.18),
    'organisation_agilite' => array('label'=>'Organisation et agilité','weight'=>.17),
    'maturite_ia' => array('label'=>'Maturité IA et innovation','weight'=>.14),
    'gouvernance' => array('label'=>'Gouvernance et maîtrise des risques','weight'=>.17),
    'adoption' => array('label'=>'Adoption et compétences','weight'=>.17),
    'automatisation' => array('label'=>'Processus, automatisation et performance','weight'=>.17),
  );
}

function transformation_choice_options($name) {
  if ($name === 'taille_organisation' || $name === 'taille_entreprise') {
    return array('independant'=>'Indépendant','1_9'=>'1 à 9 salariés','10_49'=>'10 à 49 salariés','50_249'=>'50 à 249 salariés','250_999'=>'250 à 999 salariés','1000_plus'=>'1 000 salariés et plus');
  }
  if ($name === 'temporalite_action' || $name === 'urgence') {
    return array('immediatement'=>'Immédiatement','mois'=>'Dans le mois','trois_mois'=>'Dans les trois prochains mois','six_mois'=>'Dans les six prochains mois','annee'=>'Dans l’année','veille'=>'Simple réflexion ou veille');
  }
  if ($name === 'priorite_principale') {
    return array('strategie'=>'Clarifier la stratégie','cas_usage'=>'Identifier les bons cas d’usage','securiser_ia'=>'Sécuriser les usages IA','equipes'=>'Accompagner les équipes','automatiser'=>'Automatiser des processus','organisation'=>'Améliorer l’organisation','projet_difficulte'=>'Accélérer un projet en difficulté','dirigeants'=>'Accompagner un dirigeant ou un comité de direction');
  }
  if ($name === 'budget') {
    return array('aucun'=>'Aucun budget défini','moins_5000'=>'Moins de 5 000 €','5000_15000'=>'5 000 à 15 000 €','15000_30000'=>'15 000 à 30 000 €','30000_75000'=>'30 000 à 75 000 €','plus_75000'=>'Plus de 75 000 €');
  }
  $questions = transformation_assessment_question_config();
  if (isset($questions[$name]['options'])) {
    $options = array();
    foreach ($questions[$name]['options'] as $code => $option) {
      $options[$code] = is_array($option) ? $option['label'] : $option;
    }
    return $options;
  }
  return array();
}

function transformation_objective_options() {
  return array(
    'temps'=>'Gagner du temps','couts'=>'Réduire les coûts','automatisation'=>'Automatiser les tâches répétitives','qualite'=>'Améliorer la qualité','erreurs'=>'Réduire les erreurs','experience_client'=>'Améliorer l’expérience client','managers'=>'Aider les managers','decision'=>'Accélérer la prise de décision','gouvernance'=>'Structurer la gouvernance','securiser_ia'=>'Sécuriser les usages de l’IA','equipes'=>'Accompagner les équipes','agents_ia'=>'Développer des agents IA','connecter_outils'=>'Connecter différents outils','performance_projets'=>'Améliorer la performance des projets'
  );
}

function transformation_step_config() {
  return array(
    array('key'=>'identite','title'=>'Identité du prospect','intro'=>'Quelques informations pour contextualiser la restitution et préparer un échange utile.','fields'=>array()),
    array('key'=>'strategie','title'=>'Stratégie et leadership','intro'=>'Vision, priorités, sponsoring et mesure des résultats business.','fields'=>array('vision_transformation','priorites_comprises','sponsor_direction','mesure_resultats')),
    array('key'=>'organisation','title'=>'Organisation, agilité et exécution','intro'=>'Autonomie, coopération, arbitrage et capacité à produire des résultats visibles.','fields'=>array('autonomie_equipes','collaboration_metier_tech','decision_rapide','livraison_reguliere')),
    array('key'=>'ia','title'=>'Maturité IA et innovation','intro'=>'Usages, outils, cas d’usage et mesure de la valeur générée par l’IA.','fields'=>array('usages_ia','outils_ia','cas_usage_ia','mesure_valeur_ia')),
    array('key'=>'gouvernance','title'=>'Gouvernance, risques et conformité','intro'=>'Données, confidentialité, charte IA, cartographie et responsabilités.','fields'=>array('comptes_personnels','donnees_confidentielles','politique_ia','cartographie_usages','responsabilites_obligations')),
    array('key'=>'adoption','title'=>'Adoption, compétences et conduite du changement','intro'=>'Compréhension, managers, formation et ancrage réel des nouvelles pratiques.','fields'=>array('comprehension_objectifs','managers_outilles','formation_collaborateurs','adoption_durable')),
    array('key'=>'processus','title'=>'Processus, automatisation et performance','intro'=>'Irritants opérationnels, doubles saisies, outils connectés et potentiel agents IA.','fields'=>array('taches_repetitives','processus_manuels','outils_connectes','potentiel_agents')),
    array('key'=>'priorites','title'=>'Priorités et temporalité','intro'=>'Derniers éléments pour orienter la recommandation et le niveau d’urgence.','fields'=>array()),
  );
}

function transformation_label($name, $value) {
  $options = transformation_choice_options($name);
  return $options[$value] ?? '';
}

function transformation_objective_labels($codes) {
  $labels = array();
  $options = transformation_objective_options();
  foreach ((array)$codes as $code) { if (isset($options[$code])) { $labels[] = $options[$code]; } }
  return $labels;
}

function transformation_clean_choice($value, $name) {
  $value = clean_text($value, 80);
  $options = transformation_choice_options($name);
  return isset($options[$value]) ? $value : '';
}

function transformation_clean_multi($value, $name) {
  $allowed = transformation_choice_options($name);
  $clean = array();
  foreach ((array)$value as $item) {
    $code = clean_text($item, 80);
    if (isset($allowed[$code]) && !in_array($code, $clean, true)) { $clean[] = $code; }
  }
  if (in_array('aucun', $clean, true) && count($clean) > 1) {
    $clean = array_values(array_filter($clean, function($code) { return $code !== 'aucun'; }));
  }
  return $clean;
}

function transformation_clean_objectives($value) {
  $allowed = transformation_objective_options();
  $clean = array();
  foreach ((array)$value as $item) {
    $code = clean_text($item, 80);
    if (isset($allowed[$code]) && !in_array($code, $clean, true)) { $clean[] = $code; }
  }
  return $clean;
}

function transformation_default_answers() {
  $answers = array();
  foreach (transformation_assessment_question_config() as $field => $config) {
    $answers[$field] = (($config['type'] ?? 'select') === 'multi') ? array() : '';
  }
  $answers['taille_organisation'] = '';
  $answers['objectifs_business'] = array();
  $answers['temporalite_action'] = '';
  $answers['priorite_principale'] = '';
  $answers['budget'] = '';
  return $answers;
}

function transformation_clean_answers($post) {
  $answers = transformation_default_answers();
  foreach (transformation_assessment_question_config() as $field => $config) {
    if (($config['type'] ?? 'select') === 'multi') { $answers[$field] = transformation_clean_multi($post[$field] ?? array(), $field); }
    else { $answers[$field] = transformation_clean_choice($post[$field] ?? '', $field); }
  }
  $answers['taille_organisation'] = transformation_clean_choice($post['taille_organisation'] ?? ($post['taille_entreprise'] ?? ''), 'taille_organisation');
  $answers['objectifs_business'] = transformation_clean_objectives($post['objectifs_business'] ?? array());
  $answers['temporalite_action'] = transformation_clean_choice($post['temporalite_action'] ?? ($post['urgence'] ?? ''), 'temporalite_action');
  $answers['priorite_principale'] = transformation_clean_choice($post['priorite_principale'] ?? '', 'priorite_principale');
  $answers['budget'] = transformation_clean_choice($post['budget'] ?? '', 'budget');
  return $answers;
}
function transformation_answer_score($field, $answers) {
  if ($field === 'outils_ia') {
    $tools = (array)($answers['outils_ia'] ?? array());
    if (!$tools || in_array('aucun', $tools, true)) { return 15; }
    $score = 35 + min(30, count($tools) * 8);
    if (array_intersect($tools, array('metiers','interne'))) { $score += 15; }
    if (array_intersect($tools, array('copilot','mistral'))) { $score += 5; }
    return min(90, $score);
  }
  $questions = transformation_assessment_question_config();
  $value = $answers[$field] ?? '';
  return isset($questions[$field]['options'][$value]['score']) ? (int)$questions[$field]['options'][$value]['score'] : 0;
}

function transformation_dimension_score($dimension, $answers) {
  $total = 0;
  $weightTotal = 0;
  foreach (transformation_assessment_question_config() as $field => $config) {
    if (($config['dimension'] ?? '') !== $dimension) { continue; }
    $weight = (float)($config['weight'] ?? 1);
    $total += transformation_answer_score($field, $answers) * $weight;
    $weightTotal += $weight;
  }
  return $weightTotal > 0 ? (int)round($total / $weightTotal) : 0;
}

function transformation_dimension_level($score) {
  $score = max(0, min(100, (int)$score));
  if ($score <= 24) { return 'Initial'; }
  if ($score <= 49) { return 'En structuration'; }
  if ($score <= 74) { return 'Maîtrisé'; }
  return 'Avancé';
}

function transformation_global_level($score) {
  $score = max(0, min(100, (int)$score));
  if ($score <= 24) { return 'Transformation à initier'; }
  if ($score <= 49) { return 'Transformation en structuration'; }
  if ($score <= 74) { return 'Transformation en accélération'; }
  return 'Transformation maîtrisée';
}

function transformation_risk_level($score) {
  $score = max(0, min(100, (int)$score));
  if ($score <= 24) { return 'Risque faible'; }
  if ($score <= 49) { return 'Risque modéré'; }
  if ($score <= 74) { return 'Risque important'; }
  return 'Risque critique';
}

function transformation_value_level($score) {
  $score = max(0, min(100, (int)$score));
  if ($score <= 24) { return 'Potentiel à préciser'; }
  if ($score <= 49) { return 'Potentiel intéressant'; }
  if ($score <= 74) { return 'Potentiel élevé'; }
  return 'Potentiel stratégique';
}

function transformation_urgency_level($score) {
  $score = max(0, min(100, (int)$score));
  if ($score <= 24) { return 'Veille'; }
  if ($score <= 49) { return 'À planifier'; }
  if ($score <= 74) { return 'Prioritaire'; }
  return 'Urgent';
}

function transformation_urgency_score($value) {
  $map = array('immediatement'=>100,'mois'=>85,'trois_mois'=>70,'six_mois'=>50,'annee'=>30,'veille'=>15);
  return $map[$value] ?? 15;
}

function transformation_risk_score($answers) {
  $total = 0;
  $weightTotal = 0;
  foreach (transformation_assessment_question_config() as $field => $config) {
    if (empty($config['risk'])) { continue; }
    $value = $answers[$field] ?? '';
    $weight = (float)($config['weight'] ?? 1);
    $total += (int)($config['risk'][$value] ?? 50) * $weight;
    $weightTotal += $weight;
  }
  $score = $weightTotal > 0 ? (int)round($total / $weightTotal) : 0;
  $tools = (array)($answers['outils_ia'] ?? array());
  if ($tools && !in_array('aucun', $tools, true) && in_array($answers['politique_ia'] ?? '', array('non','brouillon'), true)) { $score += 8; }
  return max(0, min(100, $score));
}

function transformation_creation_value_score($answers, $scores) {
  $signals = array();
  $questions = transformation_assessment_question_config();
  foreach (array('taches_repetitives','processus_manuels','outils_connectes','potentiel_agents') as $field) {
    $value = $answers[$field] ?? '';
    if (isset($questions[$field]['value'][$value])) { $signals[] = (int)$questions[$field]['value'][$value]; }
  }
  $objectiveBoost = min(90, count((array)($answers['objectifs_business'] ?? array())) * 12);
  if ($objectiveBoost > 0) { $signals[] = $objectiveBoost; }
  $signals[] = (int)round(transformation_urgency_score($answers['temporalite_action'] ?? 'veille') * .65);
  $signals[] = (int)round(($scores['score_organisation_agilite'] ?? 0) * .45);
  return (int)round(array_sum($signals) / max(1, count($signals)));
}
function transformation_recommendation_catalog() {
  return array(
    'diagnostic_360'=>array('label'=>'Diagnostic Transformation 360°','url'=>'/diagnostic-adoption-ia-transformation/'),
    'gouvernance_ia'=>array('label'=>'Gouvernance IA et IA Act','url'=>'/gouvernance-ia-ia-act/'),
    'adoption_ia'=>array('label'=>'Adoption IA et transformation des équipes','url'=>'/accompagnements/acculturation-ia/'),
    'automatisation_agents'=>array('label'=>'Automatisation et agents IA','url'=>'/contact/#calendly-widget'),
    'organisation_agilite'=>array('label'=>'Transformation des organisations et agilité','url'=>'/accompagnements/'),
    'leadership_dirigeants'=>array('label'=>'Accompagnement de dirigeants et leadership','url'=>'/contact/#calendly-widget'),
  );
}

function transformation_recommendation_label($key) {
  $catalog = transformation_recommendation_catalog();
  return $catalog[$key]['label'] ?? $catalog['diagnostic_360']['label'];
}

function transformation_recommendation_explanation($key) {
  $texts = array(
    'diagnostic_360'=>'Vos réponses montrent des enjeux transverses ou encore insuffisamment priorisés. Le Diagnostic Transformation 360° permet d’objectiver la situation, d’identifier les leviers majeurs et de construire une feuille de route claire avant d’investir.',
    'gouvernance_ia'=>'Le niveau d’exposition aux risques IA appelle un cadrage prioritaire : données, usages, responsabilités, fournisseurs et obligations applicables doivent être sécurisés avant d’étendre les pratiques.',
    'adoption_ia'=>'Des outils ou usages IA existent déjà, mais l’adoption et l’accompagnement humain doivent être renforcés pour créer de la valeur durablement et éviter des pratiques dispersées.',
    'automatisation_agents'=>'Le potentiel de gain opérationnel est élevé. Un cadrage automatisation et agents IA permettra d’identifier les workflows à plus fort impact et les conditions de retour sur investissement.',
    'organisation_agilite'=>'Les réponses signalent un enjeu d’organisation, de coordination ou d’exécution. Un accompagnement agilité et transformation permet de stabiliser les priorités, fluidifier les décisions et accélérer les résultats visibles.',
    'leadership_dirigeants'=>'La transformation demande un alignement plus fort de la direction, des arbitrages et un récit managérial clair. Un accompagnement dirigeants aide à clarifier la vision et à embarquer les équipes.',
  );
  return $texts[$key] ?? $texts['diagnostic_360'];
}

function transformation_next_steps($key) {
  $steps = array(
    'diagnostic_360'=>array('Clarifier les objectifs de transformation avec les décideurs clés.','Cartographier les forces, irritants, risques et opportunités prioritaires.','Construire une feuille de route 30 / 60 / 90 jours réaliste.'),
    'gouvernance_ia'=>array('Recenser les usages IA existants et les données manipulées.','Définir les règles internes, responsabilités et critères fournisseurs.','Prioriser les risques à traiter avant généralisation.'),
    'adoption_ia'=>array('Identifier les populations à former et les managers relais.','Structurer quelques cas d’usage utiles et encadrés.','Mettre en place un accompagnement terrain et des indicateurs d’adoption.'),
    'automatisation_agents'=>array('Lister les tâches répétitives et processus manuels à fort volume.','Qualifier les données, outils et dépendances nécessaires.','Sélectionner un pilote à ROI rapide avant industrialisation.'),
    'organisation_agilite'=>array('Stabiliser les priorités et les règles d’arbitrage.','Réduire les dépendances qui ralentissent l’exécution.','Installer une cadence de livraison et d’amélioration continue.'),
    'leadership_dirigeants'=>array('Aligner les décideurs sur la vision et les arbitrages clés.','Clarifier le rôle du sponsor et le récit de transformation.','Outiller les managers pour porter le changement dans la durée.'),
  );
  return $steps[$key] ?? $steps['diagnostic_360'];
}

function transformation_recommend($answers, $scores) {
  $weakCount = 0;
  foreach (array('score_strategie_leadership','score_organisation_agilite','score_maturite_ia','score_gouvernance','score_adoption','score_automatisation') as $key) {
    if (($scores[$key] ?? 0) < 50) { $weakCount++; }
  }
  $priority = $answers['priorite_principale'] ?? '';
  $objectives = (array)($answers['objectifs_business'] ?? array());
  $hasTools = !empty($answers['outils_ia']) && !in_array('aucun', (array)$answers['outils_ia'], true);
  $signals = array(
    'diagnostic_360'=>($weakCount * 18) + max(0, 60 - ($scores['score_transformation_global'] ?? 0)) + (count($objectives) >= 4 ? 12 : 0),
    'gouvernance_ia'=>($scores['score_risque'] ?? 0) + max(0, 70 - ($scores['score_gouvernance'] ?? 0)) + ($priority === 'securiser_ia' ? 25 : 0),
    'adoption_ia'=>max(0, 85 - ($scores['score_adoption'] ?? 0)) + ((($scores['score_maturite_ia'] ?? 0) > 40 || $hasTools) ? 25 : 0) + ($priority === 'equipes' ? 25 : 0),
    'automatisation_agents'=>($scores['score_creation_valeur'] ?? 0) + max(0, 65 - ($scores['score_automatisation'] ?? 0)) + (array_intersect($objectives, array('temps','couts','automatisation','agents_ia','connecter_outils')) ? 25 : 0) + ($priority === 'automatiser' ? 25 : 0),
    'organisation_agilite'=>max(0, 90 - ($scores['score_organisation_agilite'] ?? 0)) + (in_array($priority, array('organisation','projet_difficulte'), true) ? 30 : 0),
    'leadership_dirigeants'=>max(0, 90 - ($scores['score_strategie_leadership'] ?? 0)) + (in_array($priority, array('strategie','dirigeants'), true) ? 30 : 0),
  );

  if (($scores['score_risque'] ?? 0) >= 75) { $primary = 'gouvernance_ia'; }
  elseif ($weakCount >= 4 || (($scores['score_transformation_global'] ?? 0) < 50 && max($signals) - min($signals) < 55)) { $primary = 'diagnostic_360'; }
  elseif (($scores['score_creation_valeur'] ?? 0) >= 70 && ($signals['automatisation_agents'] >= $signals['adoption_ia'])) { $primary = 'automatisation_agents'; }
  elseif (($scores['score_adoption'] ?? 0) < 55 && (($scores['score_maturite_ia'] ?? 0) >= 40 || $hasTools)) { $primary = 'adoption_ia'; }
  elseif (($scores['score_organisation_agilite'] ?? 0) < 50 || in_array($priority, array('organisation','projet_difficulte'), true)) { $primary = 'organisation_agilite'; }
  elseif (($scores['score_strategie_leadership'] ?? 0) < 50 || in_array($priority, array('strategie','dirigeants'), true)) { $primary = 'leadership_dirigeants'; }
  else {
    arsort($signals);
    $primary = key($signals) ?: 'diagnostic_360';
    if (count(array_filter($signals, function($v) use ($signals) { return $v >= max($signals) - 8; })) > 1) { $primary = 'diagnostic_360'; }
  }

  arsort($signals);
  $secondary = array();
  foreach ($signals as $key => $value) {
    if ($key === $primary || $key === 'diagnostic_360') { continue; }
    if (count($secondary) < 2 && $value >= 55) { $secondary[] = transformation_recommendation_label($key); }
  }
  return array('key'=>$primary,'label'=>transformation_recommendation_label($primary),'secondaries'=>$secondary,'reason'=>transformation_recommendation_explanation($primary),'next_steps'=>transformation_next_steps($primary),'signals'=>$signals);
}

function transformation_find_constats($scores) {
  $dimensions = transformation_assessment_dimensions();
  $items = array();
  foreach ($dimensions as $key => $dimension) { $items[$dimension['label']] = $scores['score_' . $key] ?? 0; }
  asort($items);
  $constats = array();
  foreach (array_slice($items, 0, 2, true) as $label => $score) { $constats[] = $label . ' ressort comme un axe prioritaire à travailler.'; }
  if (($scores['score_risque'] ?? 0) >= 70) { $constats[] = 'Le niveau de risque mérite un cadrage rapide.'; }
  if (($scores['score_creation_valeur'] ?? 0) >= 70) { $constats[] = 'Le potentiel de création de valeur est élevé si les efforts sont bien priorisés.'; }
  return array_slice($constats, 0, 4);
}

function transformation_assess($answers) {
  $answers = array_merge(transformation_default_answers(), (array)$answers);
  $scores = array();
  foreach (transformation_assessment_dimensions() as $key => $dimension) { $scores['score_' . $key] = transformation_dimension_score($key, $answers); }
  $weighted = 0; $totalWeight = 0;
  foreach (transformation_assessment_dimensions() as $key => $dimension) { $weighted += $scores['score_' . $key] * (float)$dimension['weight']; $totalWeight += (float)$dimension['weight']; }
  $scores['score_transformation_global'] = (int)round($weighted / max(.01, $totalWeight));
  $scores['score_risque'] = transformation_risk_score($answers);
  $scores['score_creation_valeur'] = transformation_creation_value_score($answers, $scores);
  $scores['score_urgence'] = transformation_urgency_score($answers['temporalite_action']);
  $recommendation = transformation_recommend($answers, $scores);
  $dimensionLevels = array();
  foreach (transformation_assessment_dimensions() as $key => $dimension) { $dimensionLevels['niveau_' . $key] = transformation_dimension_level($scores['score_' . $key]); }
  return array_merge($scores, $dimensionLevels, array(
    'niveau_transformation'=>transformation_global_level($scores['score_transformation_global']),
    'niveau_risque'=>transformation_risk_level($scores['score_risque']),
    'niveau_creation_valeur'=>transformation_value_level($scores['score_creation_valeur']),
    'niveau_urgence'=>transformation_urgency_level($scores['score_urgence']),
    'priorite_principale'=>transformation_label('priorite_principale', $answers['priorite_principale']),
    'recommandation_principale'=>$recommendation['label'],
    'recommandation_principale_key'=>$recommendation['key'],
    'recommandations_secondaires'=>$recommendation['secondaries'],
    'recommandations_secondaires_json'=>json_encode($recommendation['secondaries'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    'explication_recommandation'=>$recommendation['reason'],
    'prochaines_etapes'=>$recommendation['next_steps'],
    'prochaines_etapes_json'=>json_encode($recommendation['next_steps'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    'constats'=>transformation_find_constats($scores),
  ));
}

function transformation_public_answers($answers) {
  $public = array();
  foreach (transformation_assessment_question_config() as $field => $config) {
    if (($config['type'] ?? 'select') === 'multi') {
      $labels = array();
      foreach ((array)($answers[$field] ?? array()) as $code) { $label = transformation_label($field, $code); if ($label !== '') { $labels[] = $label; } }
      $public[$field] = $labels;
    } else { $public[$field] = transformation_label($field, $answers[$field] ?? ''); }
  }
  $public['taille_organisation'] = transformation_label('taille_organisation', $answers['taille_organisation'] ?? '');
  $public['objectifs_business'] = transformation_objective_labels($answers['objectifs_business'] ?? array());
  $public['temporalite_action'] = transformation_label('temporalite_action', $answers['temporalite_action'] ?? '');
  $public['priorite_principale'] = transformation_label('priorite_principale', $answers['priorite_principale'] ?? '');
  $public['budget'] = transformation_label('budget', $answers['budget'] ?? '');
  return $public;
}
