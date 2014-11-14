<?php

/**
 * @file
 * Contains Drupal\simplequiz_submission\Installer\SimpleQuizSubmissionInstaller
 */

namespace Drupal\simplequiz_submission\Installer;
use Drupal\simplequiz\Installer\SimpleQuizInstaller;

/**
 * Class SimpleQuizSubmissionInstaller
 * @package Drupal\simplequiz_submission\Installer
 */
class SimpleQuizSubmissionInstaller extends SimpleQuizInstaller {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->setFields();
  }

  /**
   * Creates the fields defined by the module.
   */
  public function install() {
    node_types_rebuild();
    $this->createFields();
  }

  /**
   * Deletes the fields defined by the module.
   */
  public function uninstall() {
    $this->deleteFields();
  }

  /**
   * {@inheritdoc}
   */
  protected function setFields() {
    $this->fields = array(
      'field_response' => array(
        'field' => array(
          'type' => 'entityreference',
          'field_name' => 'field_response',
          'cardinality' => FIELD_CARDINALITY_UNLIMITED,
          'module' => 'entityreference',
          'settings' => array(
            'target_type' => 'simplequiz_response',
          ),
        ),
        'instance' => array(
          'field_name' => 'field_response',
          'entity_type' => 'simplequiz_submission',
          'bundle' => 'simplequiz_submission',
          'label' => t('Response'),
          'display' => array(
            'default' => array(
              'type' => 'hidden',
            ),
          ),
        ),
      ),
    );
  }
}
