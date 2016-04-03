<?php

/**
 * @file
 * Contains \Drupal\config_quickedit\ViewAlterInterface.
 */

namespace Drupal\config_quickedit;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Interface ViewAlterInterface.
 *
 * @package Drupal\config_quickedit
 */
interface ViewAlterInterface {

  public function addLinks(array &$build, ContentEntityInterface $entity);


}
