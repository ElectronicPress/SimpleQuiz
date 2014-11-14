<?php

/**
 * @file
 * Contains \Drupal\simplequiz\Installer\SimpleQuizInstaller
 */

namespace Drupal\simplequiz\Installer;

/**
 * Class SimpleQuizInstallerUninstall
 * @package Drupal\simplequiz\Installer
 */
class SimpleQuizInstallerUninstall extends SimpleQuizInstaller {

  /**
   * Uninstall actions for simplequiz.module.
   */
  public function uninstall() {
    drupal_uninstall_modules($this->submodules);
    $this->deleteFields('delete');
    $this->deleteBundles();
    $this->deleteVariables();
  }

  /**
   * Deletes bundles defined by simplequiz.module.
   */
  private function deleteBundles() {
    foreach ($this->bundles as $bundle) {
      node_type_delete($bundle);
      field_attach_delete_bundle('comment', 'comment_node_' . $bundle);
    }
  }

  /**
   * Deletes variables defined by simplequiz.module.
   */
  private function deleteVariables() {

    // Bundle variables.
    foreach ($this->bundles as $bundle) {
      variable_del('comment_', $bundle);
      variable_del('node_options_' . $bundle);
      variable_del('node_submitted_' . $bundle);
    }

    // Other module setting variables.
    db_delete('variable')->condition('name', 'simplequiz_%', 'LIKE');
  }
}
