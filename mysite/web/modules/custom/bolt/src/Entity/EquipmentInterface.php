<?php

namespace Drupal\bolt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Equipment entities.
 *
 * @ingroup bolt
 */
interface EquipmentInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Equipment name.
   *
   * @return string
   *   Name of the Equipment.
   */
  public function getName();

  /**
   * Sets the Equipment name.
   *
   * @param string $name
   *   The Equipment name.
   *
   * @return \Drupal\bolt\Entity\EquipmentInterface
   *   The called Equipment entity.
   */
  public function setName($name);

  /**
   * Gets the Equipment creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Equipment.
   */
  public function getCreatedTime();

  /**
   * Sets the Equipment creation timestamp.
   *
   * @param int $timestamp
   *   The Equipment creation timestamp.
   *
   * @return \Drupal\bolt\Entity\EquipmentInterface
   *   The called Equipment entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Equipment revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Equipment revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\bolt\Entity\EquipmentInterface
   *   The called Equipment entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Equipment revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Equipment revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\bolt\Entity\EquipmentInterface
   *   The called Equipment entity.
   */
  public function setRevisionUserId($uid);

}
