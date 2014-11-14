// <?php

/**
 * @file
 * Results table filtering.
 *
 * Weird php tags to appease coder_review until #1834598 is committed.
 *
 * @see https://www.drupal.org/node/1834598
 */

// ?>

(function ($) {

  var simplequizTableFilter = {
    init: function(context) {

      if ($(".simplequiz-table", context).length) {

        // Set vars.
        var $el = {};
        $el.table = $(".simplequiz-table", context);
        $el.rows = $("tbody tr", $el.table);

        // Check if we're under the results threshold.
        if ($el.rows.length < Drupal.settings.simplequizUx.quizResultsJsCount) {

          // Bail out.
          return null;
        }

        // More vars.
        $el.searchField = $('<input type="text" class="simplequiz-search" placeholder="' + Drupal.t('Filter') + '"/>');
        $el.clear = $('<a class="btn" href="#">' + Drupal.t('Clear') + '</a>');
        $el.table.before($el.searchField).before($el.clear);

        // Clear on click.
        $el.clear.on('click', function(event) {
          event.preventDefault();
          $el.searchField.val('').keyup();
        });

        // Filter on type.
        $el.searchField.on('keyup', function() {
          $el.rows.toggle(this.value.length < 1);
          $.each(this.value.split(" "), function(i, v) {
            $el.rows.filter(":contains('" + v + "')").show();
          });
        });
      }
    }
  };

  Drupal.behaviors.simplequizTableFilter = {
    // Run when DOM is loaded.
    attach: function (context) {
      simplequizTableFilter.init(context)
    }
  };

})(jQuery);
