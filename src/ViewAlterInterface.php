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

  /**
   * Adds contextual links for loading our config forms.
   *
   * @param array $build
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *
   * @return mixed
   */
  public function addLinks(array &$build, ContentEntityInterface $entity);

  /**
   * Adds Contextual links for each field.
   *
   * @todo This currently does not work.
   *       Is this even a good idea? Too many links?
   *
   * @param array $build
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   */
  public function addFieldLinks(&$variables);


}
