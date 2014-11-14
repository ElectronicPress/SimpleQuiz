<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Controller\SimpleQuizSubmissionController.
 */

namespace Drupal\simplequiz_submission\Controller;
use DatabaseTransaction;
use Drupal\simplequiz\Controller\SimpleQuizController;
use Drupal\simplequiz\Stats\SimpleQuizAccountQuizStats;

/**
 * Class SimpleQuizSubmissionController
 * @package Drupal\simplequiz_submission\Controller
 */
class SimpleQuizSubmissionController extends SimpleQuizController {

  /**
   * {@inheritdoc}
   */
  public function save($entity, DatabaseTransaction $transaction = NULL) {

    // See if it's a new entity.
    if (isset($entity->is_new)) {

      // Set the created time.
      $entity->created = REQUEST_TIME;
    }

    // Set the updated time.
    $entity->changed = REQUEST_TIME;

    // Save the entity.
    return parent::save($entity, $transaction);
  }

  /**
   * {@inheritdoc}
   */
  public function buildContent($entity, $view_mode = 'default', $langcode = NULL, $content = array()) {

    // Inherit the build.
    $build = parent::buildContent($entity, $view_mode, $langcode, $content);

    // Wrap the entity.
    $submission_wrapper = simplequiz_emw_load($entity, 'simplequiz_submission');

    // Account quiz stats.
    $account_quiz_stats
      = new SimpleQuizAccountQuizStats($submission_wrapper->snid->value());

    // Custom content.
    $build['simplequiz_submission'] = array(

      // Add the simplequiz_submission overview.
      'overview' => array(
        '#theme' => 'simplequiz_submission_overview',
        '#submission_wrapper' => $submission_wrapper,
        '#account_quiz_stats' => $account_quiz_stats,
      ),
    );

    // If the user can take the quiz.
    if ($account_quiz_stats->getAccountCanViewSimpleQuizResults()) {

      // Add the results table.
      $build['simplequiz_submission']['results'] = array(
        '#theme' => 'simplequiz_submission_responses',
        '#submission_wrapper' => $submission_wrapper,
        '#account_results' => $account_quiz_stats,
      );
    }

    // Return the renderable content.
    return $build;
  }
}
