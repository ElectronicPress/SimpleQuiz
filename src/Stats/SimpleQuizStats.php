<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Stats\SimpleQuizStats
 */

namespace Drupal\simplequiz\Stats;

/**
 * Class SimpleQuizStats
 * @package Drupal\simplequiz\Stats
 */
class SimpleQuizStats {

  // Instance variables.
  private $account;
  private $accountIsAdministrator;
  private $nodeWrapper;

  /**
   * Construct the object.
   *
   * @param \EntityMetadataWrapper|int $node_wrapper
   *   The loaded node wrapper or the nid.
   *
   * @param int|null $uid
   *   The account user ID, or NULL for current.
   */
  public function __construct($node_wrapper = NULL, $uid = NULL) {
    $this->setNodeWrapper($node_wrapper);
    $this->setAccount($uid);
    $this->setAccountIsAdministrator();
  }

  /**
   * Get the uri of the quiz.
   *
   * @return int
   *   The number of questions in the quiz.
   */
  public function getSimpleQuizUri() {
    return 'node/' . $this->nodeWrapper->nid->value() . '/simplequiz';
  }

  /**
   * Get the number of questions in the quiz.
   *
   * @return int
   *   The number of questions in the quiz.
   */
  public function getNumQuestions() {
    return count($this->nodeWrapper->field_question);
  }

  /**
   * Get the pass percentage for the quiz.
   *
   * @return int
   *   The number of questions in the quiz.
   */
  public function getPassPercent() {
    return $this->nodeWrapper->field_pass_percent->value();
  }

  /**
   * Get the number of attempts allowed for the quiz.
   *
   * @return int
   *   The number of questions in the quiz.
   */
  public function getAttemptsAllowed() {

    // No value or 0
    if (
      !property_exists($this->getNodeWrapper(), 'field_attempts_allowed') ||
      $this->nodeWrapper->field_attempts_allowed->value() < 1
    ) {

      // Unlimited attempts.
      return t('Unlimited');
    }

    // Node-specific.
    return $this->nodeWrapper->field_attempts_allowed->value();
  }

  /**
   * Get the user account object.
   *
   * @return object
   *   The user account object.
   */
  public function getAccount() {
    return $this->account;
  }

  /**
   * Check if the account is a SimpleQuiz admin.
   *
   * @return bool
   *   If the account is an administrator.
   */
  public function getAccountIsAdministrator() {
    return $this->accountIsAdministrator;
  }

  /**
   * Get the SimpleQuiz node wrapper.
   *
   * @return \EntityMetadataWrapper
   *   The node wrapper.
   */
  public function getNodeWrapper() {
    return $this->nodeWrapper;
  }

  /**
   * Checks if the SimpleQuiz node still exists.
   *
   * @return bool
   *   If the SimpleQuiz node still exists.
   */
  protected function getSimpleQuizExists() {
    $exists = $this->nodeWrapper->value();
    return !empty($exists);
  }

  /**
   * Sets the object's simplequiz node wrapper.
   *
   * @param \EntityDrupalWrapper|int $node_wrapper
   *   The loaded node wrapper or the nid.
   */
  private function setNodeWrapper($node_wrapper) {

    // Check if nothing was passed.
    if (is_null($node_wrapper)) {

      // Create the EMW from the current node.
      $node_wrapper = simplequiz_emw_load(menu_get_object(), 'node');
    }

    // If a number was passed, load and set.  Otherwise just set.
    $this->nodeWrapper = is_numeric($node_wrapper) ?
      simplequiz_emw_load(node_load($node_wrapper), 'node') : $node_wrapper;
  }

  /**
   * Sets the user account.
   *
   * @param int $uid
   *   The user ID.
   */
  private function setAccount($uid) {

    // Set to current user ID if null.
    $uid = is_null($uid) ? $GLOBALS['user']->uid : $uid;

    // Load or current.
    $this->account = $uid == $GLOBALS['user']->uid ?
      $GLOBALS['user'] : user_load($uid);
  }

  /**
   * Sets if the account is a SimpleQuiz administrator.
   */
  private function setAccountIsAdministrator() {
    $this->accountIsAdministrator = user_access('administer simplequiz', $this->getAccount());
  }
}
