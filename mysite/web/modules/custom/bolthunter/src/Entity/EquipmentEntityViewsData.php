<?php

namespace Drupal\bolthunter\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Equipment entity entities.
 */
class EquipmentEntityViewsData extends EntityViewsData {

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
