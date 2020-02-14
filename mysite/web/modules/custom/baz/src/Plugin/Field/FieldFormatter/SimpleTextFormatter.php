<?php

/**
 * @file
 * Contains Drupal\baz\Plugin\Field\FieldFormatter\SimpleTextFormatter.
 */

namespace Drupal\baz\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of our "sticky-note" formatter.
 *
 * @FieldFormatter(
 *   id = "baz_simple_formatter",
 *   module = "baz",
 *   label = @Translation("Simple text-based formatter"),
 *   field_types = {
 *     "baz_fieldnote"
 *   }
 * )
 */
class SimpleTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($items as $delta => $item) {
      $elements[$delta] = array(
        // We wrap the fieldnote content up in a div tag.
        '#type' => 'html_tag',
        '#tag' => 'div',
        // This text is auto-XSS escaped.  See docs for the html_tag element.
        '#value' => $item->value,
        // Let's give the note a nice sticky-note CSS appearance.
        '#attributes' => array(
          'class' => 'stickynote',
        ),
        // ..And this is the CSS for the stickynote.
        '#attached' => array(
          'library' => array('baz/fieldnote_sticky'),
        ),
      );
    }

    return $elements;
  }

}
