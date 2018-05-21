<?php

namespace Drupal\friendship\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Friend ship entities.
 *
 * @ingroup friendship
 */
interface FriendShipInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Friend ship name.
   *
   * @return string
   *   Name of the Friend ship.
   */
  public function getName();

  /**
   * Sets the Friend ship name.
   *
   * @param string $name
   *   The Friend ship name.
   *
   * @return \Drupal\friendship\Entity\FriendShipInterface
   *   The called Friend ship entity.
   */
  public function setName($name);

  /**
   * Gets the Friend ship creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Friend ship.
   */
  public function getCreatedTime();

  /**
   * Sets the Friend ship creation timestamp.
   *
   * @param int $timestamp
   *   The Friend ship creation timestamp.
   *
   * @return \Drupal\friendship\Entity\FriendShipInterface
   *   The called Friend ship entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Friend ship published status indicator.
   *
   * Unpublished Friend ship are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Friend ship is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Friend ship.
   *
   * @param bool $published
   *   TRUE to set this Friend ship to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\friendship\Entity\FriendShipInterface
   *   The called Friend ship entity.
   */
  public function setPublished($published);

}
