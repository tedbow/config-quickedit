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

  }
}
