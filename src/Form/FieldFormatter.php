<?php
/**
 * @file
 * Contains \Drupal\config_quickedit\Form\FieldFormatter.
 */


namespace Drupal\config_quickedit\Form;


use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\field_ui\Form\EntityDisplayFormBase;
use Drupal\field_ui\Form\EntityViewDisplayEditForm;

class FieldFormatter extends EntityViewDisplayEditForm {
  protected $field_name;
  public function form(array $form, FormStateInterface $form_state) {

    $elements = [];
    // Get the corresponding plugin object.
    $plugin = $this->entity->getRenderer($this->field_name);
    $display_options = $this->entity->getComponent($this->field_name);
    $field_definitions = $this->getFieldDefinitions();
    $elements['container'] = [
      '#type' => 'container',
      '#prefix' => '<div id="config-quickedit-formatter-wrapper" >',
      '#suffix' => '</div>',
    ];
    $elements['container']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Formatter'),
      '#options' => $this->getPluginOptions($field_definitions[$this->field_name]),
      '#default_value' => $display_options ? $display_options['type'] : 'hidden',
      '#attributes' => array('class' => array('field-plugin-type')),
      '#ajax' => [
        // Could also use [ $this, 'colorCallback'].
        'callback' => '::formatterCallback',
        'wrapper' => 'config-quickedit-formatter-wrapper',
      ]
    ];
    $values = $form_state->cleanValues()->getValues();
    if ($display_options && $values['type'] != 'hidden') {
      if ($display_options['type'] == $values['type']) {
        $plugin->setSettings($display_options['settings']);
      }
      $elements['container']['formatter_settings'] = $plugin->settingsForm($form, $form_state);
    }

    return $elements;

  }

  public function formatterCallback(array &$form, FormStateInterface $form_state) {
    return $form['container'];
  }

  protected function actionsElement(array $form, FormStateInterface $form_state) {
    $actions = parent::actionsElement($form, $form_state); // TODO: Change the autogenerated stub
    $actions['submit']['#attributes']['class'][] = 'use-ajax-submit';
    return $actions;
  }


  protected function copyFormValuesToEntity(EntityInterface $entity, array $form, FormStateInterface $form_state) {
    $form_values = $form_state->cleanValues()->getValues();
    /** @var  EntityViewDisplay $entity */
    if ($form_values['type'] == 'hidden') {
      $entity->removeComponent($this->field_name);
    }
    else {
      $field_component = $entity->getComponent($this->field_name);
      $field_component['settings'] = $form_values;
      $entity->setComponent($this->field_name, $field_component);
    }

  }


  public function getEntityFromRouteMatch(RouteMatchInterface $route_match, $entity_type_id) {
    $route_parameters = $route_match->getParameters()->all();
    $this->field_name = $route_parameters['field_name'];
    return parent::getEntityFromRouteMatch($route_match, $entity_type_id); // TODO: Change the autogenerated stub
  }


}
