<?php
/**
 * @file
 * Contains \Drupal\config_quickedit\AccessChecker.
 */


namespace Drupal\config_quickedit;


use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;

class AccessChecker {
  public function checkEntityViewDisplay(AccountInterface $account, $entity_type_id, $bundle) {
    return AccessResult::allowedIfHasPermission($account,'administer ' . $entity_type_id . ' display');
  }

}
