<?php

namespace Drupal\bolthunter;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\bolthunter\Entity\EquipmentEntityInterface;

/**
 * Defines the storage handler class for Equipment entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Equipment entity entities.
 *
 * @ingroup bolthunter
 */
class EquipmentEntityStorage extends SqlContentEntityStorage implements EquipmentEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(EquipmentEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {equipment_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {equipment_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(EquipmentEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {equipment_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('equipment_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
