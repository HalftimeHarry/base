<?php

/**
 * @file
 * Contains equipment_entity.page.inc.
 *
 * Page callback for Equipment entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Equipment entity templates.
 *
 * Default template: equipment_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_equipment_entity(array &$variables) {
  // Fetch EquipmentEntity Entity Object.
  $equipment_entity = $variables['elements']['#equipment_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
