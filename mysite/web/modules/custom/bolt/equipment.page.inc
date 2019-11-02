<?php

/**
 * @file
 * Contains equipment.page.inc.
 *
 * Page callback for Equipment entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Equipment templates.
 *
 * Default template: equipment.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_equipment(array &$variables) {
  // Fetch Equipment Entity Object.
  $equipment = $variables['elements']['#equipment'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
