<?php

/**
 * @file
 * Menu, access, load, property, etc. callbacks.
 */

/**
 * Gets the score a user received on a simplequiz.
 *
 * @param \Drupal\simplequiz_submission\Entity\SimpleQuizSubmissionEntity $data
 *   The submission entity.
 *
 * @return int
 *   The score the user received on the simplequiz.
 */
function simplequiz_submission_property_user_score_get($data) {

  // Load the submission wrapper.
  $submission_wrapper = simplequiz_emw_load($data, 'simplequiz_submission');

  // Save the score.
  $score = 0;

  // Each response.
  foreach ($submission_wrapper->field_response as $response_wrapper) {

    // Add the points.
    $score += $response_wrapper->points->value();
  }

  // Return the score.
  return $score;
}

/**
 * Checks if a user passed the quiz on this submission.
 *
 * @param \Drupal\simplequiz_submission\Entity\SimpleQuizSubmissionEntity $data
 *   The submission entity.
 *
 * @return bool
 *   If the user passed.
 */
function simplequiz_submission_property_user_passed_get($data) {

  // Load the submission wrapper.
  $submission_wrapper = simplequiz_emw_load($data, 'simplequiz_submission');

  // See if the percent is at or above the threshold..
  return
    $submission_wrapper->user_percent->value() >=
    $submission_wrapper->pass_percent->value();
}

/**
 * Gets the score in percent a user received on a simplequiz.
 *
 * @param \Drupal\simplequiz_submission\Entity\SimpleQuizSubmissionEntity $data
 *   The submission entity.
 *
 * @return int
 *   The score in percent the user received on the simplequiz.
 */
function simplequiz_submission_property_user_percent_get($data) {

  // Load the submission wrapper.
  $submission_wrapper = simplequiz_emw_load($data, 'simplequiz_submission');

  // Return the percentage.
  return
    $submission_wrapper->user_score->value() /
    $submission_wrapper->score_max->value() *
    100;
}

/**
 * Access callback for SimpleQuizSubmissionEntity.
 *
 * @todo: this function
 *
 * @return bool
 *   If the user has access.
 */
function simplequiz_submission_access_callback() {
  return TRUE;
}
