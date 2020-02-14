<?php

/**
 * @file
 * Contains \Drupal\baz\Plugin\field\widget\TextWidget.
 */

namespace Drupal\baz\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'baz_widget' widget.
 *
 * @FieldWidget(
 *   id = "baz_widget",
 *   module = "baz",
 *   label = @Translation("Field Note Widget"),
 *   field_types = {
 *     "baz_fieldnote"
 *   }
 * )
 */
class TextWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += array(
      '#type' => 'textarea',
      '#default_value' => $value,
    );
    return array('value' => $element);
  }

}
