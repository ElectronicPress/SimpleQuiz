<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Installer\SimpleQuizInstaller
 */

namespace Drupal\simplequiz\Installer;

/**
 * Class SimpleQuizInstaller
 * @package Drupal\simplequiz\Installer
 */
class SimpleQuizInstaller {

  // Instance variables.
  public $bundles = [];
  public $fields = [];
  public $submodules = [];

  /**
   * Class constructor.
   *
   * Bundles, fields, submodules defined by the module.
   */
  public function __construct() {
    $this->setBundles();
    $this->setFields();
    $this->setSubmodules();
  }

  /**
   * Magic getter.
   *
   * @param string $property
   *   The property to get.
   *
   * @return mixed
   *   The property or FALSE if none.
   */
  public function __get($property) {
    return isset($this->$property) ? $this->property : FALSE;
  }

  /**
   * Magic Setter.
   *
   * @param string $property
   *   The property to set.
   *
   * @param mixed $value
   *   The value to give to the property.
   */
  public function __set($property, $value) {
    $this->$property = $value;
  }

  /**
   * Create the fields and instances defined by the module.
   */
  protected function createFields() {

    // Each simplequiz field.
    foreach ($this->fields as $field_id => $field_definitions) {

      // Each field or instance for this definition.
      foreach ($field_definitions as $type => $field) {
        $function = 'field_create_' . $type;
        $function($field);
      }
    }
  }

  /**
   * Deletes the fields (and instances) defined by the module.
   */
  protected function deleteFields() {
    foreach ($this->fields as $field_id => $field_definitions) {
      field_delete_field($field_id);
    }
  }

  /**
   * Sets the bundles defined by the module.
   */
  protected function setBundles() {
    $this->bundles = array(
      'simplequiz_answer',
      'simplequiz_question',
      'simplequiz',
    );
  }

  /**
   * Sets the submodules defined by the module.
   */
  protected function setSubmodules() {
    $this->submodules = array(
      'simplequiz_response',
      'simplequiz_submission',
    );
  }

  /**
   * Sets the fields defined by the module.
   */
  protected function setFields() {

    $this->fields = array(
      'field_randomize_order' => array(
        'field' => array(
          'type' => 'list_boolean',
          'field_name' => 'field_randomize_order',
          'cardinality' => 1,
          'module' => 'list',
          'locked' => TRUE,
          'settings' => array(
            'allowed_values' => array(
              0 => '',
              1 => '',
            ),
          ),
        ),
        'instance' => array(
          'field_name' => 'field_randomize_order',
          'bundle' => 'simplequiz',
          'entity_type' => 'node',
          'label' => t('Randomize Question Order'),
          'default_value' => array(
            0 => array(
              'value' => variable_get('simplequiz_randomize_order', 0),
            ),
          ),
          'display' => array(
            'default' => array(
              'type' => 'hidden',
            ),
          ),
          'widget' => array(
            'module' => 'options',
            'settings' => array(
              'display_label' => 1,
            ),
            'type' => 'options_onoff',
          ),
        ),
      ),
      'field_attempts_allowed' => array(
        'field' => array(
          'type' => 'number_integer',
          'field_name' => 'field_attempts_allowed',
          'cardinality' => 1,
          'module' => 'number',
        ),
        'instance' => array(
          'field_name' => 'field_attempts_allowed',
          'entity_type' => 'node',
          'bundle' => 'simplequiz',
          'label' => t('Attempts allowed'),
          'description' => t('Enter 0 for unlimited.'),
          'required' => TRUE,
          'settings' => array(
            'min' => 0,
          ),
          'display' => array(
            'default' => array(
              'type' => 'hidden',
            ),
          ),
          'default_value' => array(
            0 => array(
              'value' => variable_get('simplequiz_attempts_allowed', 0),
            ),
          ),
        ),
      ),
      'field_pass_percent' => array(
        'field' => array(
          'type' => 'number_integer',
          'field_name' => 'field_pass_percent',
          'cardinality' => 1,
          'module' => 'number',
        ),
        'instance' => array(
          'field_name' => 'field_pass_percent',
          'entity_type' => 'node',
          'bundle' => 'simplequiz',
          'label' => t('Pass Percentage'),
          'required' => TRUE,
          'widget' => array(
            'active' => 0,
            'module' => 'number',
          ),
          'settings' => array(
            'max' => 100,
            'min' => 0,
            'suffix' => '%',

          ),
          'display' => array(
            'default' => array(
              'type' => 'hidden',
            ),
          ),
          'default_value' => array(
            0 => array(
              'value' => variable_get('simplequiz_pass_percentage', 70),
            ),
          ),
        ),
      ),
      'field_question' => array(
        'field' => array(
          'type' => 'entityreference',
          'field_name' => 'field_question',
          'cardinality' => FIELD_CARDINALITY_UNLIMITED,
          'module' => 'entityreference',
          'settings' => array(
            'target_type' => 'node',
            'handler_settings' => array(
              'target_bundles' => array(
                'question' => 'simplequiz_question',
              ),
            ),
          ),
        ),
        'instance' => array(
          'field_name' => 'field_question',
          'entity_type' => 'node',
          'bundle' => 'simplequiz',
          'label' => t('Question'),
          'required' => TRUE,
          'widget' => array(
            'module' => 'inline_entity_form',
            'type' => 'inline_entity_form',
            'settings' => array(
              'type_settings' => array(
                'delete_references' => 1,
                'label_plural' => 'questions',
                'label_singular' => 'question',
                'override_labels' => 1,
              ),
            ),
          ),
          'display' => array(
            'default' => array(
              'type' => 'hidden',
            ),
          ),
        ),
      ),
      'field_answer' => array(
        'field' => array(
          'type' => 'entityreference',
          'field_name' => 'field_answer',
          'cardinality' => FIELD_CARDINALITY_UNLIMITED,
          'module' => 'entityreference',
          'settings' => array(
            'target_type' => 'node',
            'handler_settings' => array(
              'target_bundles' => array(
                'answer' => 'simplequiz_answer',
              ),
            ),
          ),
        ),
        'instance' => array(
          'field_name' => 'field_answer',
          'entity_type' => 'node',
          'bundle' => 'simplequiz_question',
          'label' => t('Answer'),
          'required' => TRUE,
          'widget' => array(
            'module' => 'inline_entity_form',
            'type' => 'inline_entity_form',
            'settings' => array(
              'type_settings' => array(
                'delete_references' => 1,
                'label_plural' => 'answers',
                'label_singular' => 'answer',
                'override_labels' => 1,
              ),
            ),
          ),
          'display' => array(
            'default' => array(
              'label' => 'hidden',
              'type' => 'entityreference_label',
              'settings' => array(
                'link' => FALSE,
              ),
            ),
          ),
        ),
      ),
      'field_solution' => array(
        'field' => array(
          'type' => 'list_boolean',
          'field_name' => 'field_solution',
          'cardinality' => 1,
          'module' => 'list',
          'settings' => array(
            'allowed_values' => array(
              0 => 'No',
              1 => 'Yes',
            ),
          ),
        ),
        'instance' => array(
          'field_name' => 'field_solution',
          'entity_type' => 'node',
          'bundle' => 'simplequiz_answer',
          'label' => t('Solution'),
          'widget' => array(
            'module' => 'options',
            'type' => 'options_onoff',
            'settings' => array(
              'display_label' => 1,
            ),
          ),
        ),
      ),
    );
  }
}
