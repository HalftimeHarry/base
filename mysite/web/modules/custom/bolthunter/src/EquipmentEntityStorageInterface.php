<?php

namespace Drupal\bolthunter;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface EquipmentEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Equipment entity revision IDs for a specific Equipment entity.
   *
   * @param \Drupal\bolthunter\Entity\EquipmentEntityInterface $entity
   *   The Equipment entity entity.
   *
   * @return int[]
   *   Equipment entity revision IDs (in ascending order).
   */
  public function revisionIds(EquipmentEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Equipment entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Equipment entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\bolthunter\Entity\EquipmentEntityInterface $entity
   *   The Equipment entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(EquipmentEntityInterface $entity);

  /**
   * Unsets the language for all Equipment entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
