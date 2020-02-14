<?php

/**
 * @file
 * Contains Drupal\baz\Plugin\Field\FieldType\FieldNote.
 */

namespace Drupal\baz\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'baz' field type.
 *
 * @FieldType(
 *   id = "baz_fieldnote",
 *   label = @Translation("Example FieldNote"),
 *   module = "baz",
 *   description = @Translation("Demonstrates a field simple field note type with permission-based access control."),
 *   default_widget = "baz_widget",
 *   default_formatter = "baz_simple_formatter"
 * )
 */
class FieldNote extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'value' => array(
          'type' => 'text',
          'size' => 'normal',
          'not null' => FALSE,
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Field Note'));

    return $properties;
  }

}
