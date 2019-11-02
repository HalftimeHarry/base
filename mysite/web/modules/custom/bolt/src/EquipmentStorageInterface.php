<?php

namespace Drupal\bolt;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface EquipmentStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Equipment revision IDs for a specific Equipment.
   *
   * @param \Drupal\bolt\Entity\EquipmentInterface $entity
   *   The Equipment entity.
   *
   * @return int[]
   *   Equipment revision IDs (in ascending order).
   */
  public function revisionIds(EquipmentInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Equipment author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Equipment revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\bolt\Entity\EquipmentInterface $entity
   *   The Equipment entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(EquipmentInterface $entity);

  /**
   * Unsets the language for all Equipment with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
