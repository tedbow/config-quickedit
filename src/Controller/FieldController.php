<?php
/**
 * @file
 * Contains \Drupal\config_quickedit\Controller\FieldController.
 */


namespace Drupal\config_quickedit\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FieldController extends ControllerBase{

  /**
   * FieldController constructor.
   *
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $displayRepository
   */
  public function __construct(EntityDisplayRepositoryInterface $displayRepository) {
    $this->displayRepository = $displayRepository;

  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_display.repository')
    );
  }



  public function listField($entity_type_id, $bundle, $view_mode_name) {
    $entity_view_display = $this->getEntityViewDisplay($entity_type_id, $bundle, $view_mode_name);
    $comps = $entity_view_display->getComponents();
    $field_links = [];
    $fields = $this->entityManager()->getFieldDefinitions($entity_type_id, $bundle);
    foreach ($comps as $field_name => $comp) {
      if (isset($fields[$field_name])) {
        $field = $fields[$field_name];
        if ($field->isDisplayConfigurable('view')) {
          $url = Url::fromRoute(
            'config_quickedit.field_formatter',
            [
              'entity_type_id' => $entity_type_id,
              'bundle' => $bundle,
              'view_mode_name' => $view_mode_name,
              'field_name' => $field_name,
            ],
            [
              'attributes' => [
                'data-config-quick-edit-route' => 'config_quickedit.field_formatter',
              ],
            ]
          );
          $field_links[] = [
            'url' => $url,
            'title' => $field->getLabel(),
          ];
        }
      }
    }
    return [
      '#theme' => 'links',
      '#links' => $field_links,
    ];
  }

  /**
   * @param $entity_type_id
   * @param $bundle
   * @param $view_mode_name
   *
   * @return EntityViewDisplayInterface
   */
  protected function getEntityViewDisplay($entity_type_id, $bundle, $view_mode_name) {
    return  $this->entityTypeManager()
      ->getStorage('entity_view_display')
      ->load($entity_type_id . '.' . $bundle . '.' . $view_mode_name);
  }
}
