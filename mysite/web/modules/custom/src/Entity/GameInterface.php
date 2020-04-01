<?php

namespace Drupal\bliz_odds\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Game entities.
 *
 * @ingroup bliz_odds
 */
interface GameInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Game name.
   *
   * @return string
   *   Name of the Game.
   */
  public function getName();

  /**
   * Sets the Game name.
   *
   * @param string $name
   *   The Game name.
   *
   * @return \Drupal\bliz_odds\Entity\GameInterface
   *   The called Game entity.
   */
  public function setName($name);

  /**
   * Gets the Game creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Game.
   */
  public function getCreatedTime();

  /**
   * Sets the Game creation timestamp.
   *
   * @param int $timestamp
   *   The Game creation timestamp.
   *
   * @return \Drupal\bliz_odds\Entity\GameInterface
   *   The called Game entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Game revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Game revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\bliz_odds\Entity\GameInterface
   *   The called Game entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Game revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Game revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\bliz_odds\Entity\GameInterface
   *   The called Game entity.
   */
  public function setRevisionUserId($uid);

}
