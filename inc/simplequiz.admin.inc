<?php

/**
 * @file
 * Administration functionality for simplequiz.module.
 */

/**
 * Form builder / page callback for the simplequiz.module administration form.
 *
 * {@inheritdoc}
 */
function simplequiz_admin_form($form) {

  // Display messages.
  $form['messages'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Messages'),

    // User has passed quiz.
    'simplequiz_user_passed' => array(
      '#title' => t('User passed - overview page'),
      '#type' => 'textfield',
      '#default_value' => variable_get(
        'simplequiz_user_passed',
        t('Congratulations!  You have already passed this course.')
      ),
    ),

    // User has passed but is administrator.
    'simplequiz_user_is_admin' => array(
      '#title' => t('User passed but is administrator - overview page'),
      '#type' => 'textfield',
      '#default_value' => variable_get(
        'simplequiz_user_is_admin',
        t('Since you are an administrator, you can still take the quiz again.')
      ),
    ),
  );

  // SimpleQuiz quiz node defaults.
  $form['simplequiz_node_defaults'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('SimpleQuiz Node Defaults'),

    // Randomize order.
    'simplequiz_randomize_order' => array(
      '#type' => 'checkbox',
      '#title' => t('Randomize question order'),
      '#default_value' => variable_get('simplequiz_randomize_order', 0),
    ),

    // Attempts allowed.
    'simplequiz_attempts_allowed' => array(
      '#type' => 'textfield',
      '#title' => t('Attempts Allowed'),
      '#description' => t('Enter 0 for unlimited'),
      '#cols' => 3,
      '#default_value' => variable_get('simplequiz_attempts_allowed', 0),
      '#element_validate' => array('element_validate_integer'),
    ),

    // Pass percentage.
    'simplequiz_pass_percentage' => array(
      '#type' => 'textfield',
      '#title' => t('Pass percentage'),
      '#cols' => 3,
      '#default_value' => variable_get('simplequiz_pass_percentage', 70),
      '#element_validate' => array('element_validate_integer'),
    ),
  );

  // Return the renderable system settings form.
  return system_settings_form($form);
}
