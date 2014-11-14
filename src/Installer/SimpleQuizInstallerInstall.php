<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Installer\SimpleQuizInstallerInstall
 */

namespace Drupal\simplequiz\Installer;

/**
 * Class SimpleQuizInstallerInstall
 * @package Drupal\simplequiz\Installer
 */
class SimpleQuizInstallerInstall extends SimpleQuizInstaller {

  /**
   * Install actions for simplequiz.module.
   */
  public function install() {
    node_types_rebuild();
    $this->createFields();
    $this->createVariables();
  }

  /**
   * Disables comments and sets node options for simplequiz.module bundles.
   */
  private function createVariables() {
    foreach ($this->bundles as $bundle) {
      variable_set('comment_' . $bundle, COMMENT_NODE_HIDDEN);
      variable_set('node_options_' . $bundle, array('status', 'revision'));
      variable_set('node_submitted_' . $bundle, 0);
    }
  }
}
