<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Controller\SimpleQuizController.
 */

namespace Drupal\simplequiz\Controller;
use EntityAPIController;

/**
 * Class SimpleQuizController
 * @package Drupal\simplequiz\Controller
 */
class SimpleQuizController extends EntityAPIController {

  /**
   * {@inheritdoc}
   */
  public function view($entities, $view_mode = 'full', $langcode = NULL, $page = NULL) {

    // Put the entities in an array if only one given.
    $entities = !is_array($entities) ? array($entities) : $entities;

    // Return the normal view function.
    return parent::view($entities, $view_mode, $langcode, $page);
  }
}
