<?php

/**
 * @file
 * Contains \Drupal\config_quickedit\Controller\DefaultController.
 */

namespace Drupal\config_quickedit\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class DefaultController.
 *
 * @package Drupal\config_quickedit\Controller
 */
class DefaultController extends ControllerBase {


  /**
   * Load.
   *
   * @return string
   *   Return Hello string.
   */
  public function load($entity_type, $entity_id) {
    $response = new AjaxResponse();
    $entity_view_display = $this->entityTypeManager()->getStorage('entity_view_display')->load('node.article.default');
    $form_class = 'Drupal\field_ui\Form\EntityViewDisplayEditForm';
    $form = $this->formBuilder()->getForm($form_class, $entity_view_display);
    $node_view = $this->entity_type_manager->getViewBuilder('node')->view(Node::load(1));
    $render_array = [
      '#theme' => 'config_quickedit',
      '#inner' => $form,
    ];
    $command = new ReplaceCommand('#config-quickedit-replace', $render_array);
    $response->addCommand($command);
    $response->expire();
    return $response;
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: load with parameter(s): !name', [
        '!name' => '$entity_type, $entity_id',
      ]),
    ];
  }

}
