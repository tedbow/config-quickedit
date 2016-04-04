<?php
/**
 * @file
 * Contains \Drupal\config_quickedit\Form\BlockForm.
 */


namespace Drupal\config_quickedit\Form;

use Drupal\block\BlockForm as CoreBlockForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Block Form for Quick Edit Context.
 */
class BlockForm extends CoreBlockForm {
  /**
   * {@inheritdoc}
   */
  protected function actionsElement(array $form, FormStateInterface $form_state) {
    $actions_element = parent::actionsElement($form, $form_state);
    $actions_element['submit']['#attributes']['class'][] = 'use-ajax-submit';
    return $actions_element;
  }

  /**
   * {@inheritdoc}
   */
  protected function buildVisibilityInterface(array $form, FormStateInterface $form_state) {
    $elements = parent::buildVisibilityInterface($form, $form_state);
    unset($elements['visibility_tabs']);
    foreach ($elements as &$element) {
      unset($element['#group']);
    }
    return $elements;
  }

}
