<?php

namespace Drupal\bolt;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Equipment entity.
 *
 * @see \Drupal\bolt\Entity\Equipment.
 */
class EquipmentAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bolt\Entity\EquipmentInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished equipment entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published equipment entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit equipment entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete equipment entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add equipment entities');
  }


}
