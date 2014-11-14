// <?php

/**
 * @file
 * SimpleQuiz form functionality.
 *
 * Weird php tags to appease coder_review until #1834598 is committed.
 *
 * @see https://www.drupal.org/node/1834598
 */

// ?>

(function ($) {

  var simplequizFormHandler = {

    // Stored elements.
    el: {
      simplequizForm: null,
      simplequizAnswers: null,
      simplequizGroups: []
    },

    // On DOM load.
    init: function(context) {

      // If the simplequiz isn't on this page (should be because #attached).
      if ($(".simplequiz-form", context).length === 0) {
        return null;
      }

      // Add the form.
      this.el.simplequizForm = $(".simplequiz-form", context);

      // Add all the answers.
      this.el.simplequizAnswers
        = $(".simplequiz-questions input:radio", this.el.simplequizForm);

      // Add all the radio groups.
      this.el.simplequizAnswers.each(function() {

        // Get the radio button group name.
        var name = $(this).attr('name').toString();

        // Not in list yet.
        if ($.inArray(name, simplequizFormHandler.el.simplequizGroups) < 0) {

          // Add the name to the list.
          simplequizFormHandler.el.simplequizGroups.push(name);
        }
      });

      // Intercept form submission.
      this.el.simplequizForm.on('submit', function() {

        // Check if the form should be submitted.
        return simplequizFormHandler.checkUnanswered();

      });
    },

    // Checks if any radio groups are unanswered.
    checkUnanswered: function() {

      // Save unanswered questions.
      var unanswered = [];

      // All questions.
      var questions = simplequizFormHandler.el.simplequizAnswers;

      // Each question group.
      $.each(this.el.simplequizGroups, function(index, value) {

        // If the group has no selection.
        if (questions.filter('[name="' + value + '"]:checked').length < 1) {

          // Add group question number to unanswered.
          unanswered.push(index + 1);
        }
      });

      // If there were unanswered question.
      if (unanswered.length) {

        // Warn the user.
        alert(Drupal.t(
          "Please answer the following questions: @unanswered",
          { '@unanswered' : unanswered.join(", ") + "." },
          {}
        ));

        // Don't submit the form.
        return false;
      }

      // All answered, submit the form.
      return confirm(Drupal.settings.simplequizUx.quizFormConfirmation);
    }
  };

  Drupal.behaviors.simplequizFormHandler = {
    // Run when DOM is loaded.
    attach: function (context) {
      simplequizFormHandler.init(context);
    }
  };

})(jQuery);
