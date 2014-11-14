<?php

/**
 * @file
 * Menu, access, load, property, page, etc. callbacks.
 */

use Drupal\simplequiz\Stats\SimpleQuizAccountQuizStats;
use Drupal\simplequiz\Stats\SimpleQuizResultsQuizStats;

/**
 * Access callback for the simplequiz.module quiz form.
 *
 * Determine in order of performance hit.
 *
 * @param EntityDrupalWrapper|int $node_wrapper
 *   The loaded node wrapper or the nid.
 *
 * @return bool
 *   If the user can view the form.
 */
function _simplequiz_quiz_form_access($node_wrapper) {

  // If it's not a simplequiz.
  if ($node_wrapper->get('type')->value() != 'simplequiz') {

    // Bail out.
    return FALSE;
  }

  // Load the user results.
  $account_quiz_results = new SimpleQuizAccountQuizStats($node_wrapper);

  // Check if they can take the quiz.
  return $account_quiz_results && $account_quiz_results->getAccountCanTakeSimpleQuiz();
}

/**
 * Access callback for the simplequiz.module quiz results table.
 *
 * Determine in order of performance hit.
 *
 * @param EntityDrupalWrapper|int $node_wrapper
 *   The loaded node wrapper or the nid.
 *
 * @return bool
 *   If the user can view the form.
 */
function _simplequiz_quiz_results_access($node_wrapper) {

  // If it's not a simplequiz.
  if ($node_wrapper->get('type')->value() != 'simplequiz') {

    // Bail out.
    return FALSE;
  }

  // Load the user results.
  $account_quiz_results = new SimpleQuizAccountQuizStats($node_wrapper);

  // Check if they can view results.
  return $account_quiz_results && $account_quiz_results->getAccountCanViewSimpleQuizResults();

}

/**
 * Auto load function to convert an entity to a metadata wrapper.
 *
 * Backwards params because of wildcard loader (always sends menu arg first).
 *
 * @param int|object $id
 *   The ID of the entity.
 *
 * @param string $entity_type
 *   The entity type.
 *
 * @return EntityMetadataWrapper
 *   The wrapper.
 */
function simplequiz_emw_load($id, $entity_type) {

  // Convert ID to array if it's just an integer.
  $id = is_numeric($id) ? entity_load_single($entity_type, $id) : $id;

  // Load and return the wrapper.
  return entity_metadata_wrapper($entity_type, $id);
}

/**
 * Menu callback to load a single entity.
 *
 * @param int $id
 *   The entity id.
 *
 * @param string $entity_type
 *   The entity type.
 *
 * @return \Entity
 *   The loaded entity.
 */
function simplequiz_entity_object_load($id, $entity_type) {

  // Load and return the entity.
  return entity_load_single($entity_type, $id);
}

/**
 * Page callback for viewing an aggregate list of submissions on a simplequiz.
 *
 * @param \EntityMetadataWrapper $node_wrapper
 *   The quiz node wrapper.
 *
 * @return string
 *   The rendered content to display.
 */
function simplequiz_results_table($node_wrapper) {

  // Store the renderable content.
  $build = array(
    '#theme' => 'simplequiz_results',
    '#results_quiz_stats' => new SimpleQuizResultsQuizStats($node_wrapper),
  );

  // Return the rendered content.
  return render($build);
}
