<?php

/**
 * @file
 * Contains \Drupal\config_quickedit\Routing\RouteSubscriber.
 */

namespace Drupal\config_quickedit\Routing;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 *
 * @package Drupal\config_quickedit\Routing
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $manager;
  /**
   * Constructor.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->manager = $entity_type_manager;
  }
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    return;
    /**
     * @var  $entity_type_id
     * @var  EntityTypeInterface $entity_type
     */
    foreach ($this->manager->getDefinitions() as $entity_type_id => $entity_type) {
      $interfaces = class_implements($entity_type->getClass());
      if ($interfaces && in_array('ContentEntityInterface',$interfaces)) {
        $route = new Route(
          "config-quick-edit/fields/{$entity_type_id}/order",
          [
            '_controller' => '\Drupal\config_quickedit\Controller\FieldController::order',
            '_title' => 'Order fields',
          ],
          array('_permission' => 'administer ' . $entity_type_id . ' fields')
        );
        $collection->add("config_quickedit.fields.{$entity_type_id}.order", $route);
      }
    }
  }
}
