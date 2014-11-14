<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Stats\SimpleQuizAccountQuizStats.
 */

namespace Drupal\simplequiz\Stats;
use EntityFieldQuery;

/**
 * Class SimpleQuizAccountQuizStats
 * @package Drupal\simplequiz\Stats
 */
class SimpleQuizAccountQuizStats extends SimpleQuizStats {

  // Instance variables.
  private $accountHasPassed;
  private $submissions = array();

  /**
   * {@inheritdoc}
   */
  public function __construct($node_wrapper = NULL, $uid = NULL) {
    parent::__construct($node_wrapper, $uid);
    $this->setSubmissions();
    $this->setAccountHasPassed();
  }

  /**
   * Checks if the account can take this SimpleQuiz.
   *
   * @return bool
   *   If the account can take the SimpleQuiz.
   */
  public function getAccountCanTakeSimpleQuiz() {

    // Check for other module hooks.
    foreach (module_implements('simplequiz_account_can_take_simplequiz') as $module) {

      // Check if they return false.
      if (module_invoke($module, 'simplequiz_account_can_take_simplequiz', $this) === FALSE) {

        // Deny access.
        return FALSE;
      }
    }

    // Return the permission.
    return

      // Make sure they can take simplequiz.
      user_access('take simplequiz', $this->getAccount()) &&

      (
        // Account has not passed and has attempts.
        (!$this->getAccountHasPassed() && $this->getHasAttempts()) ||

        // SimpleQuiz node exists and the account is an administrator.
        ($this->getSimpleQuizExists() && $this->getAccountIsAdministrator())
      );
  }

  /**
   * Check if a user has permission to view simplequiz results.
   */
  public function getAccountCanViewSimpleQuizResults() {

    // Check for other module hooks.
    foreach (module_implements('simplequiz_account_can_view_simplequiz_results') as $module) {

      // Check if they return false.
      if (module_invoke($module, 'simplequiz_account_can_view_simplequiz_results', $this) === FALSE) {

        // Deny access.
        return FALSE;
      }
    }

    // Return the access.
    return

      // View any simplequiz results.
      user_access('view any simplequiz results', $this->getAccount()) ||

      // View own simplequiz results.
      (
        user_access('view own simplequiz results', $this->getAccount()) &&
        $this->accountHasPassed
      );


  }

  /**
   * Get the number of attempts made for the quiz.
   *
   * @return int
   *   The number of questions in the quiz.
   */
  public function getAttemptsMade() {
    return count($this->getSubmissions());
  }

  /**
   * Get the number of attempts remaining in the quiz.
   *
   * @return int
   *   The number of questions in the quiz.
   */
  public function getAttemptsRemaining() {
    return $this->getAttemptsAllowed() > 0 ?
      $this->getAttemptsAllowed() - $this->getAttemptsMade() : t('Unlimited');
  }

  /**
   * Checks if a user has passed the simplequiz.
   *
   * @return bool
   *   If the user has passed the simplequiz.
   */
  public function getAccountHasPassed() {
    return $this->accountHasPassed;
  }

  /**
   * Checks if a user has attempts left.
   *
   * @return bool
   *   If they have attempts.
   */
  private function getHasAttempts() {

    // If the node doesn't exist anymore.
    if (!$this->getSimpleQuizExists()) {

      // No attempts.
      return FALSE;
    }

    // Get the allowed attempts.
    $allowed = $this->getAttemptsAllowed();

    // See if they have less attempts than allowed.
    return $allowed == 0 || $this->getAttemptsMade() < $allowed;
  }

  /**
   * Get the submissions.
   *
   * @return array
   *   The array of submissions.
   */
  public function getSubmissions() {
    return $this->submissions;
  }

  /**
   * Sets the object's submissions the account has made on this simplequiz.
   */
  private function setSubmissions() {

    // New entity query.
    $sids = new EntityFieldQuery();

    // Get the submission entities for this user on the given simplequiz.
    $sids
      ->entityCondition('entity_type', 'simplequiz_submission')
      ->propertyCondition('uid', $this->getAccount()->uid)
      ->propertyCondition('snid', $this->getNodeWrapper()->nid->value())
      ->propertyOrderBy('created', 'DESC');

    // Execute the query.
    $sids = $sids->execute();

    // If there was  submissions.
    if (!empty($sids['simplequiz_submission'])) {

      // Each submission.
      foreach (array_keys($sids['simplequiz_submission']) as $sid) {

        // Add the submission wrapper.
        $this->submissions[$sid]
          = simplequiz_emw_load($sid, 'simplequiz_submission');
      }
    }
  }

  /**
   * Sets the instance variable for if an account has passed the quiz.
   */
  private function setAccountHasPassed() {

    // Start with false.
    $this->accountHasPassed = FALSE;

    // Each submission.
    foreach ($this->getSubmissions() as $submission) {

      // Check if they passed.
      if ($submission->user_passed->value()) {

        // Set as passed.
        $this->accountHasPassed = TRUE;

        // Bail out.
        break;
      }
    }
  }
}
