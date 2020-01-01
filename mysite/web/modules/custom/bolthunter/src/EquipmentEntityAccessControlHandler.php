<?php

namespace Drupal\bolthunter;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Equipment entity entity.
 *
 * @see \Drupal\bolthunter\Entity\EquipmentEntity.
 */
class EquipmentEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bolthunter\Entity\EquipmentEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished equipment entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published equipment entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit equipment entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete equipment entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add equipment entity entities');
  }


}
