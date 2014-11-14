<?php

/**
 * @file
 * Contains
 * Drupal\simplequiz_submission\Installer\SimpleQuizCertificateInstaller
 */

namespace Drupal\simplequiz_certificate\Installer;
use Drupal\simplequiz\Installer\SimpleQuizInstaller;

/**
 * Class SimpleQuizCertificateInstaller
 * @package Drupal\simplequiz_certificate\Installer
 */
class SimpleQuizCertificateInstaller extends SimpleQuizInstaller {

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
      'field_file_simplequiz_private' => array(
        'field' => array(
          'type' => 'file',
          'field_name' => 'field_file_simplequiz_private',
          'cardinality' => FIELD_CARDINALITY_UNLIMITED,
          'module' => 'file',
          'active' => 1,
          'locked' => TRUE,
          'settings' => array(
            'display_default' => 1,
            'display_field' => 1,
            'uri_scheme' => 'private',
          ),
          'foreign keys' => array(
            'fid' => array(
              'columns' => array(
                'fid' => 'fid',
              ),
              'table' => 'file_managed',
            ),
          ),
          'indexes' => array(
            'fid' => array(
              0 => 'fid',
            ),
          ),
        ),
        'instance' => array(
          'field_name' => 'field_file_simplequiz_private',
          'entity_type' => 'node',
          'bundle' => 'simplequiz',
          'label' => t('Certificate'),
          'display' => array(
            'default' => array(
              'label' => 'hidden',
              'module' => 'file',
              'type' => 'file_table',
            ),
          ),
          'settings' => array(
            'file_directory' => 'private_course_files',
            'file_extensions' => 'txt pdf doc docx xls xlsx rdf png jpg jpeg',
          ),
        ),
      ),
    );
  }
}
