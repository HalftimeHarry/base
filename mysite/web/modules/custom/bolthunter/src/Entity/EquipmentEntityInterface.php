<?php

namespace Drupal\bolthunter\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Equipment entity entities.
 *
 * @ingroup bolthunter
 */
interface EquipmentEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Equipment entity name.
   *
   * @return string
   *   Name of the Equipment entity.
   */
  public function getName();

  /**
   * Sets the Equipment entity name.
   *
   * @param string $name
   *   The Equipment entity name.
   *
   * @return \Drupal\bolthunter\Entity\EquipmentEntityInterface
   *   The called Equipment entity entity.
   */
  public function setName($name);

  /**
   * Gets the Equipment entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Equipment entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Equipment entity creation timestamp.
   *
   * @param int $timestamp
   *   The Equipment entity creation timestamp.
   *
   * @return \Drupal\bolthunter\Entity\EquipmentEntityInterface
   *   The called Equipment entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Equipment entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Equipment entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\bolthunter\Entity\EquipmentEntityInterface
   *   The called Equipment entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Equipment entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Equipment entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\bolthunter\Entity\EquipmentEntityInterface
   *   The called Equipment entity entity.
   */
  public function setRevisionUserId($uid);

}
