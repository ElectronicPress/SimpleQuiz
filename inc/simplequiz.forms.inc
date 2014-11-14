<?php

/**
 * @file
 * Form definition & handling for simplequiz.module.
 */

/**
 * Page callback / form builder for the simplequiz.module quiz form.
 *
 * @param array $form
 *   The renderable form array.
 *
 * @param array $form_state
 *   The form state.
 *
 * @return array
 *   The renderable form array.
 */
function simplequiz_quiz_form($form, &$form_state) {

  // Get the node.
  $node_wrapper = $form_state['build_info']['args'][0];

  // Set the page title, since we want the local task to just say Quiz.
  drupal_set_title($node_wrapper->title->value());

  // Create the renderable form structure.
  $form += array(
    '#attributes' => array(
      'class' => array('simplequiz-form'),
    ),
    'questions' => array(
      '#tree' => TRUE,
      '#prefix' => '<div class="simplequiz-questions">',
      '#suffix' => '</div>',
    ),
  );

  // Save the question and answer deltas.
  $form_state['storage']['deltas'] = array();

  // Are we supposed to randomize.
  if ($node_wrapper->field_randomize_order->value()) {

    // Get the questions.
    $questions = $node_wrapper->field_question->value();

    // Shuffle them.
    shuffle($questions);

    // Re-set the wrapper.
    $node_wrapper->field_question = $questions;
  }

  // Each question.
  foreach ($node_wrapper->field_question as $qdelta => $question) {

    // Question nid.
    $qnid = $question->nid->value();

    // Save question structure.
    $build = array(
      '#type' => 'radios',
      '#title' => t('@title', array('@title' => $question->title->value())),
      '#options' => array(),
      '#required' => TRUE,
      '#attributes' => array(
        'class' => array('quiz-answers'),
      ),
    );

    // Save the question delta.
    $form_state['storage']['deltas'][$qnid] = $qdelta;

    // Each answer.
    foreach ($question->field_answer as $adelta => $answer) {

      // Answer nid.
      $anid = $answer->nid->value();

      // Add the answer to the radio options.
      $build['#options'][$anid] = $answer->title->value();

      // Save the answer delta.
      $form_state['storage']['deltas'][$anid] = $adelta;
    }

    // Add the question structure to the form.
    $form['questions'][$qnid] = $build;
  }

  // Add form actions.
  $form['action'] = array(
    '#type' => 'actions',
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Submit and Grade Quiz'),
      '#name' => 'submit',
    ),
  );

  // Return the renderable form array.
  return $form;
}

/**
 * Form submission for the simplequiz.module quiz form.
 *
 * @param array $form
 *   The renderable form array.
 *
 * @param array $form_state
 *   The form state.
 */
function simplequiz_quiz_form_submit(&$form, &$form_state) {

  // Get the quiz node.
  $node_wrapper = $form_state['build_info']['args'][0];

  // If a submission wrapper does not exist yet.
  if (empty($form_state['storage']['submission_wrapper'])) {

    // SimpleQuizSubmissionEntity values.
    $values = array(
      'snid' => $node_wrapper->nid->value(),
      'uid' => $GLOBALS['user']->uid,
      'pass_percent' => $node_wrapper->field_pass_percent->value(),
      // @todo: maybe allow for questions to have a different score value
      // (Right now it's 1 point per question).
      'score_max' => count($node_wrapper->field_question),
    );

    // Create the submission.
    $submission = entity_create('simplequiz_submission', $values);

    // Wrap it.
    $submission_wrapper
      = simplequiz_emw_load($submission, 'simplequiz_submission');

    // Save to get the sid.
    $submission_wrapper->save();

    // Store in the form state.
    $form_state['storage']['submission_wrapper'] = $submission_wrapper;
  }

  // Get the submission entity.
  $submission_wrapper = $form_state['storage']['submission_wrapper'];

  // Get the deltas.
  $deltas = $form_state['storage']['deltas'];

  // Get the responses.
  $responses = $form_state['values']['questions'];

  // Each response.
  foreach ($responses as $qnid => $anid) {

    // Get the question wrapper.
    $question_wrapper = $node_wrapper->field_question->get($deltas[$qnid]);

    // Get the answer wrapper.
    $answer_wrapper = $question_wrapper->field_answer->get($deltas[$anid]);

    // Save the solution string.
    $solution = array();

    // Each possible solution.
    foreach ($question_wrapper->field_answer as $solution_wrapper) {

      // Check if it's a solution.
      if ($solution_wrapper->field_solution->value()) {

        // Update the solution string.
        $solution[] = $solution_wrapper->title->value();
      }
    }

    // Convert the solution titles to a string.
    $solution = implode("<br /><br />", $solution);

    // Store the response variables.
    $values = array(
      'sid' => $submission_wrapper->sid->value(),
      'qnid' => $question_wrapper->nid->value(),
      'question' => $question_wrapper->title->value(),
      'response' => $answer_wrapper->title->value(),
      'solution' => $solution,
      // @todo: maybe change to a field and let user specify points per q.
      'points' => (int) $answer_wrapper->field_solution->value(),
    );

    // Create the response.
    $response = entity_create('simplequiz_response', $values);

    // Create the wrapper.
    $response_wrapper = simplequiz_emw_load($response, 'simplequiz_response');

    // Save the response to make sure it has an ID.
    $response_wrapper->save();

    // Add the response to the submission.
    $submission_wrapper->field_response[] = $response_wrapper;
  }

  // Save the submission.
  $submission_wrapper->save();

  // Store it for when we enable multiple pages.
  $form_state['storage']['submission_wrapper'] = $submission_wrapper;

  // @todo: after multi page functionality added, check that it's the last page.

  // Invoke complete hook.
  module_invoke_all('simplequiz_submission_complete', $submission_wrapper, $node_wrapper);

  // Invoke pass / fail hooks.
  $submission_wrapper->user_passed->value() ?
    module_invoke_all('simplequiz_submission_passed', $submission_wrapper, $node_wrapper) :
    module_invoke_all('simplequiz_submission_failed', $submission_wrapper, $node_wrapper);

  // Redirect to the results page.  No need for a drupal_alter since modules can
  // override using hook_form_FORM_ID_alter() and add their own #submit.
  $form_state['redirect'] = entity_uri(
    'simplequiz_submission',
    $submission_wrapper->value()
  );
}
