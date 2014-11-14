<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Stats\SimpleQuizResultsQuizStats.
 */

namespace Drupal\simplequiz\Stats;

/**
 * Class SimpleQuizAccountQuizStats
 * @package Drupal\simplequiz\Stats
 */
class SimpleQuizResultsQuizStats extends SimpleQuizStats {

  // Instance variables.
  private $results;

  /**
   * Construct the object.
   *
   * @param \EntityMetadataWrapper|int $node_wrapper
   *   The loaded node wrapper or the nid.
   */
  public function __construct($node_wrapper = NULL) {
    parent::__construct($node_wrapper);
    $this->setResults();
  }

  /**
   * Get the list of results.
   *
   * @return array
   *   The list of results.
   */
  public function getResults() {
    return is_array($this->results) ? $this->results : array();
  }

  /**
   * Sets all the submissions on a quiz.
   */
  private function setResults() {

    // Start with just their own submissions.
    $uids = array($GLOBALS['user']->uid);

    // User is simplequiz administrator.
    if ($this->getAccountIsAdministrator()) {

      // They can see all user submissions on this quiz.
      $uids = db_select('simplequiz_submission', 's')
        ->fields('s', array('uid'))
        ->condition('snid', $this->getNodeWrapper()->nid->value())
        ->distinct()
        ->execute()
        ->fetchCol();
    }

    // Accounts that have submissions the user can view.
    foreach ($uids as $uid) {

      // Add the stats to the result.
      $this->results[$uid] = new SimpleQuizAccountQuizStats($this->getNodeWrapper(), $uid);
    }
  }
}
