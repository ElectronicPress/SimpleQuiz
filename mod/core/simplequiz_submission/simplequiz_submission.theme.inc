<?php

/**
 * @file
 * Theme callbacks for simplequiz_submission.module.
 */

/**
 * Theme callback for simplequiz_overview.
 *
 * @param array $variables
 *   Theme variables.
 *
 * @return string
 *   The rendered overview content.
 */
function theme_simplequiz_submission_overview(&$variables) {

  // Get the submission.
  $submission_wrapper = $variables['submission_wrapper'];

  /* @var Drupal\simplequiz\Stats\SimpleQuizAccountQuizStats $account_quiz_stats */
  $account_quiz_stats = $variables['account_quiz_stats'];

  // Build the renderable content.
  $build = array();

  // Add score message.
  $build['overview'] = array(
    '#prefix' => '<div class="simplequiz-overview">',
    '#suffix' => '</div>',
    'score' => array(
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      '#markup' => format_plural(
        $submission_wrapper->user_score->value(),
        'You responded correctly to %one question out of a possible %score_max, giving you a score of %user_percent%.',
        'You responded correctly to %count questions out of a possible %score_max, giving you a score of %user_percent%.',
        array(
          '%one' => 1,
          '%user_score' => $submission_wrapper->user_score->value(),
          '%score_max' => $submission_wrapper->score_max->value(),
          '%user_percent' => $submission_wrapper->user_percent->value(),
          '%count' => $submission_wrapper->user_score->value(),
        )
      ),
    ),
  );

  // The user passed the quiz.
  if ($submission_wrapper->user_passed->value()) {

    // Add the success message.
    $build['overview']['result'][] = array(
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      '#markup' => t(
        'Congratulations!  You have successfully passed this quiz.  You can view your results in the table below or go back to the <a href="@quiz_url">Quiz Homepage</a>.',
        array('@quiz_url' => url('node/' . $submission_wrapper->snid->value()))
      ),
    );
  }

  // The user failed the quiz.
  else {

    // Add the failed message.
    $build['overview']['result'][] = array(
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      '#markup' => t(
        'Sorry, you did not receive the minimum required score of %pass_percent%.',
        array(
          '%pass_percent' => $submission_wrapper->pass_percent->value(),
        )
      ),
    );

    // If the user can take the quiz again.
    if ($account_quiz_stats->getAccountCanTakeSimpleQuiz()) {

      // Add a message back to the quiz node.
      $build['overview']['result'][] = array(
        '#prefix' => '<p>',
        '#suffix' => '</p>',
        '#markup' => t(
          'However, you can still take this quiz again.  <a href="@quiz_url">Go to quiz homepage</a>.',
          array('@quiz_url' => url('node/' . $submission_wrapper->snid->value()))
        ),
      );
    }

    // User can't take the quiz again.
    else {
      $build['overview']['result'][] = array(
        '#prefix' => '<p>',
        '#suffix' => '</p>',
        '#markup' => t('Unfortunately, you are not able to take this quiz again.'),
      );
    }
  }

  // Allow modules to alter the output.
  drupal_alter(
    'simplequiz_submission_overview',
    $build,
    $submission_wrapper,
    $account_quiz_stats
  );

  // Return the rendered content.
  return render($build);
}

/**
 * Theme callback for the table of submission responses.
 *
 * @param array $variables
 *   The theme variables.
 *
 * @return string
 *   The rendered overview content.
 */
function theme_simplequiz_submission_responses(&$variables) {

  // Get the submission.
  $submission_wrapper = $variables['submission_wrapper'];

  // Store the renderable content.
  $build = array(
    '#theme' => 'table',
    '#header' => array(
      array('data' => '', 'class' => array('correct')),
      array('data' => t('Question'), 'class' => t('question')),
      array('data' => t('Response'), 'class' => array('response')),
      array('data' => t('Solution'), 'class' => array('solution')),
    ),
    '#rows' => array(),
    '#empty' => t('There are no questions to show for this quiz!'),
    '#attributes' => array(
      'class' => array('simplequiz-table', 'simplequiz-responses'),
    ),
  );

  // Each response.
  foreach ($submission_wrapper->field_response as $response) {

    // Add the response to the table.
    $build['#rows'][] = array(
      array(
        'data' => $response->points->value() > 0 ? '✓' : '×',
        'class' => array('correct'),
      ),
      array(
        'data' => $response->question->value(),
        'class' => array('question'),
      ),
      array(
        'data' => $response->response->value(),
        'class' => array('response'),
      ),
      array(
        'data' => $response->solution->value(),
        'class' => array('solution'),
      ),
    );
  }

  // Return the rendered content.
  return render($build);
}
