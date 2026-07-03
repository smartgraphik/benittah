<?php
require_once __DIR__ . '/transformation_assessment.php';

function diagnostic_choice_options($name) {
  $map = array(
    'taille_entreprise' => 'taille_organisation',
    'outils_ia' => 'usages_ia',
    'urgence' => 'temporalite_action',
  );
  return transformation_choice_options($map[$name] ?? $name);
}

function diagnostic_objective_options() { return transformation_objective_options(); }
function diagnostic_choice_label($name, $value) { return transformation_label($name, $value); }
function diagnostic_objective_labels($codes) { return transformation_objective_labels($codes); }
function diagnostic_clean_choice($value, $name) { return transformation_clean_choice($value, $name); }
function diagnostic_clean_objectives($value) { return transformation_clean_objectives($value); }

function diagnostic_score_level($score, $type) {
  if ($type === 'risque') { return transformation_risk_level($score); }
  if ($type === 'opportunite') { return transformation_value_level($score); }
  if ($type === 'urgence') { return transformation_urgency_level($score); }
  return transformation_dimension_level($score);
}

function diagnostic_offer_catalog() { return transformation_recommendation_catalog(); }
function diagnostic_offer_label($key) { return transformation_recommendation_label($key); }
function diagnostic_score_answers($answers) { return transformation_assess($answers); }
function diagnostic_recommend_offer($answers, $scores) { return array('key'=>'diagnostic_360','reason'=>transformation_recommendation_explanation('diagnostic_360')); }
function diagnostic_score_lead($answers) { return transformation_assess($answers); }
function diagnostic_public_answers($answers) { return transformation_public_answers($answers); }