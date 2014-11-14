<?php

/**
 * @file
 * Hooks provided by simplequiz.module.
 */

use Drupal\simplequiz_submission\Entity\SimpleQuizSubmissionEntity;
use Drupal\simplequiz\Stats\SimpleQuizAccountQuizStats;

/**
 * React to a quiz being submitted.
 *
 * @param SimpleQuizSubmissionEntity $submission_wrapper
 *   The submission entity wrapper..
 */
function hook_simplequiz_submission_complete($submission_wrapper) {

  // Do something because the simplequiz was completed.
}

/**
 * React to a quiz being passed.
 *
 * @param SimpleQuizSubmissionEntity $submission_wrapper
 *   The submission entity wrapper.
 */
function hook_simplequiz_submission_passed($submission_wrapper) {

  // Do something because the simplequiz was passed.
}

/**
 * React to a quiz being failed.
 *
 * @param SimpleQuizSubmissionEntity $submission_wrapper
 *   The submission entity wrapper..
 */
function hook_simplequiz_submission_failed($submission_wrapper) {

  // Do something because the simplequiz was failed.
}

/**
 * React to a quiz submission being viewed.
 *
 * @param array &$build
 *   The renderable content array.
 *
 * @param \EntityMetadataWrapper $submission_wrapper
 *   The EMW for the submission.
 *
 * @param SimpleQuizAccountQuizStats $account_quiz_stats
 *   The stats for this account / quiz.
 */
function hook_simplequiz_submission_overview_alter(&$build, $submission_wrapper, $account_quiz_stats) {

  // Add something to the page.
}

/**
 * Have a say if a given account can take a simplequiz.
 *
 * @param SimpleQuizAccountQuizStats $account_quiz_results
 *   The account quiz stats object.
 *
 * @return bool|NULL
 *   FALSE to deny access.
 */
function hook_simplequiz_account_can_take_simplequiz($account_quiz_results) {

  // Return FALSE to deny.
}

/**
 * Have a say if a given account can view simplequiz results.
 *
 * @param SimpleQuizAccountQuizStats $account_quiz_results
 *   The account quiz stats object.
 *
 * @return bool|NULL
 *   FALSE to deny access.
 */
function hook_simplequiz_account_can_view_simplequiz_results($account_quiz_results) {

  // Return FALSE to deny.
}
