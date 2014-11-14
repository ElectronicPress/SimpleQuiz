<?php

/**
 * @file
 * Contains
 * \Drupal\simplequiz\Entity\SimpleQuizSubmissionEntity.
 */

namespace Drupal\simplequiz_submission\Entity;
use Entity;

/**
 * Class SimpleQuizSubmissionEntity
 * @package Drupal\simplequiz_submission\Entity
 */
class SimpleQuizSubmissionEntity extends Entity {

  /**
   * {@inheritdoc}
   */
  protected function defaultUri() {
    return array('path' => 'simplequiz/submission/' . $this->identifier());
  }
}
