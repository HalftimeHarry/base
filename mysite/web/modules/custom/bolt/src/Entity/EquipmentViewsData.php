<?php

namespace Drupal\bolt\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Equipment entities.
 */
class EquipmentViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
