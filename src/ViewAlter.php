<?php

/**
 * @file
 * Contains \Drupal\config_quickedit\ViewAlter.
 */

namespace Drupal\config_quickedit;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Class ViewAlter.
 *
 * @package Drupal\config_quickedit
 */
class ViewAlter implements ViewAlterInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $displayRepo;

  /**
   * ViewAlter constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $display_repo
   */
  public function __construct(EntityTypeManager $entity_type_manager, EntityDisplayRepositoryInterface $display_repo) {
    $this->entityTypeManager = $entity_type_manager;
    $this->displayRepo = $display_repo;
  }

  /**
   * @param array $build
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   */
  public function addLinks(array &$build, ContentEntityInterface $entity) {
    $entity_type = $entity->getEntityTypeId();
    // For now hard code for nodes. @see config_quickedit.links.contextual.yml
    if ($entity_type == 'node') {
      $view_mode_options = $this->displayRepo->getViewModeOptionsByBundle($entity_type, $entity->bundle());
      $current_view_ids = array_keys($view_mode_options);

      $view_mode = $build['#view_mode'];
      if (!in_array($view_mode, $current_view_ids)) {
        $view_mode = 'default';
      }

      $build['#contextual_links']['config_quickedit'] = [
        'route_parameters' => [
          'entity_type_id' => $entity->getEntityTypeId(),
          'bundle' => $entity->bundle(),
          'view_mode_name' => $view_mode,
        ],
      ];
      $build['#attached']['library'][] = 'config_quickedit/config_quickedit';

    }
  }

  /**
   * Adds Contextual links for each field.
   *
   * @param array $build
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   */
  public function addFieldLinks(&$variables) {
    $element = &$variables['element'];
    /** @var ContentEntityInterface $entity */
    $entity = $element['#object'];
    $field_name = $element['#field_name'];
    $field_definition = $entity->getFieldDefinition($field_name);
    if ($field_definition->isDisplayConfigurable('view')) {
      $element['#contextual_links']['config_quickedit_field'] = [
        'route_parameters' => [
          'entity_type_id' => $entity->getEntityTypeId(),
          'bundle' => $entity->bundle(),
          'view_mode_name' => $element['#view_mode'],
          'field_name' => $field_name,
        ],
      ];
      $element['contextual_links'] = array(
        '#type' => 'contextual_links_placeholder',
        '#id' => _contextual_links_to_id($element['#contextual_links']),
      );
      $element['#attached']['library'][] = 'config_quickedit/config_quickedit';
    }
  }

  /**
   * Determines if a field is compatible with showing its formatter settings.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param array $build
   *
   * @return boolean
   */
  protected function isFormatterCompatible(FieldDefinitionInterface $field_definition, array $build) {
    if ($field_definition->isDisplayConfigurable('view')) {
      return isset($build[$field_definition->getName()]);
    }
    return FALSE;
  }

}
