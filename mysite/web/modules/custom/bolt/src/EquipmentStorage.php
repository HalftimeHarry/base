<?php

namespace Drupal\bolt;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\bolt\Entity\EquipmentInterface;

/**
 * Defines the storage handler class for Equipment entities.
 *
 * This extends the base storage class, adding required special handling for
 * Equipment entities.
 *
 * @ingroup bolt
 */
class EquipmentStorage extends SqlContentEntityStorage implements EquipmentStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(EquipmentInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {equipment_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {equipment_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(EquipmentInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {equipment_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('equipment_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
