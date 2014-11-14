<?php

/**
 * @file
 * Theme callbacks for simplequiz.module.
 */

use Drupal\simplequiz\Stats\SimpleQuizAccountQuizStats;
use Drupal\simplequiz\Stats\SimpleQuizResultsQuizStats;

/**
 * Preprocess for simplequiz_overview.
 *
 * @param array $variables
 *   Passed theme variables.
 */
function template_preprocess_simplequiz_overview(&$variables) {

  // Check if quiz results wasn't passed.
  if (is_null($variables['account_quiz_stats'])) {

    // Set to current node and current user.
    $variables['account_quiz_stats'] = new SimpleQuizAccountQuizStats();
  }
}

/**
 * Theme callback for simplequiz_overview.
 *
 * @param array $variables
 *   Passed theme variables.
 *
 * @return string
 *   The rendered content to display.
 */
function theme_simplequiz_overview(&$variables) {

  /* @var SimpleQuizAccountQuizStats $account_quiz_stats */
  $account_quiz_stats = $variables['account_quiz_stats'];

  // Store the renderable content.
  $build = array(
    'table' => array(
      '#theme' => 'table',
      '#rows' => array(
        array(t('Number of Questions'), $account_quiz_stats->getNumQuestions()),
        array(t('Pass Percentage'), $account_quiz_stats->getPassPercent() . '%'),
        array(t('Attempts Allowed'), $account_quiz_stats->getAttemptsAllowed()),
        array(t('Attempts Made'), $account_quiz_stats->getAttemptsMade()),
        array(t('Attempts Remaining'), $account_quiz_stats->getAttemptsRemaining()),
      ),
      '#attributes' => array(
        'class' => array('simplequiz-table', 'simplequiz-overview'),
      ),
    ),
  );

  // Check if user has passed account.
  if ($account_quiz_stats->getAccountHasPassed()) {
    // Yay!
    drupal_set_message(variable_get(
      'simplequiz_user_passed',
      t('Congratulations!  You have already passed this course.')
    ));

    // Are they an admin.
    if ($account_quiz_stats->getAccountIsAdministrator()) {

      // More info.
      drupal_set_message(variable_get(
        'simplequiz_user_is_admin',
        t('Since you are an administrator, you can still take the quiz again.')
      ), 'warning');
    }
  }

  // If the user can take the quiz.
  if ($account_quiz_stats->getAccountCanTakeSimpleQuiz()) {
    $build['take'] = array(
      '#theme' => 'link',
      '#text' => t('Start Quiz'),
      '#path' => $account_quiz_stats->getSimpleQuizUri(),
      '#options' => array(
        'html' => FALSE,
        'attributes' => array(
          'class' => array('btn', 'simplequiz-start'),
        ),
      ),
    );
  }

  // Return the renderable content.
  return render($build);
}

/**
 * Preprocess functionality for theme_simplequiz_results.
 *
 * @param array $variables
 *   Passed theme variables.
 */
function template_preprocess_simplequiz_results(&$variables) {

  // Check if stats not set.
  if (is_null($variables['results_quiz_stats'])) {

    // Set stats.
    $variables['results_quiz_stats'] = new SimpleQuizResultsQuizStats();
  }
}

/**
 * Theme callback for viewing an aggregate list of quiz submissions.
 *
 * @param array $variables
 *   The passed theme variables.
 *
 * @return string
 *   The rendered content to display.
 */
function theme_simplequiz_results(&$variables) {

  /* @var SimpleQuizResultsQuizStats $results_quiz_stats */
  $results_quiz_stats = $variables['results_quiz_stats'];

  // Store the renderable content.
  $build = array(
    '#theme' => 'table',
    '#header' => array('', t('User'), t('Score'), t('Date')),
    '#rows' => array(),
    '#empty' => t('There are no results to show for this quiz yet.'),
    '#attributes' => array(
      'class' => array('simplequiz-table', 'simplequiz-results'),
    ),
  );

  // Each submission.
  /* @var SimpleQuizAccountQuizStats $account_stats */
  foreach ($results_quiz_stats->getResults() as $account_stats) {

    // Each submission on the account stats.
    /* @var EntityMetadataWrapper $submission */
    foreach ($account_stats->getSubmissions() as $submission) {

      // Submission link.
      $uri = entity_uri('simplequiz_submission', $submission->value());

      // Add the row.
      $build['#rows'][] = array(
        array(
          'data' => $submission->user_passed->value() ? '✓' : '×',
          'class' => 'correct',
        ),
        l($account_stats->getAccount()->name, $uri['path']),
        $submission->user_percent->value() . '%',
        format_date($submission->created->value()),
      );
    }
  }

  // Return the rendered content.
  return render($build);
}
