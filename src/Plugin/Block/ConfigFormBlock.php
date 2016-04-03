<?php

/**
 * @file
 * Contains \Drupal\config_quickedit\Plugin\Block\ConfigFormBlock.
 */

namespace Drupal\config_quickedit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ConfigFormBlock' block.
 *
 * @Block(
 *  id = "config_quickedit.form",
 *  admin_label = @Translation("Config form block"),
 * )
 */
class ConfigFormBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['config_quickedit.form']['#markup'] = '<div id="config-quick-edit-form"></div>';

    return $build;
  }

}
