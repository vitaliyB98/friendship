<?php

namespace Drupal\friendship;

use Drupal\Core\Url;
use Drupal\user\Entity\User;

/**
 * Class FriendshipService.
 *
 * @package Drupal\friendship
 */
class FriendshipService implements FriendshipInterface {

  /**
   * Current logged user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $currentUser;

  /**
   * DB connection.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $connection;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->currentUser = User::load(\Drupal::currentUser()->id());
    $this->connection = \Drupal::database();
  }

  /**
   * {@inheritdoc}
   */
  public function follow(User $target_user) {
    $this->connection->insert('friendship')
      ->fields([
        'uid' => $this->currentUser->id(),
        'requested_uid' => $target_user->id(),
        'status' => 0,
      ])
      ->execute();

    $this->connection->insert('friendship')
      ->fields([
        'uid' => $target_user->id(),
        'requested_uid' => $this->currentUser->id(),
        'status' => -1,
      ])
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function unfollow(User $target_user) {
    $this->connection->delete('friendship')
      ->condition('uid', $this->currentUser->id())
      ->condition('requested_uid', $target_user->id())
      ->execute();

    $this->connection->delete('friendship')
      ->condition('uid', $target_user->id())
      ->condition('requested_uid', $this->currentUser->id())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function accept(User $target_user) {
    $this->connection->update('friendship')
      ->fields([
        'status' => 1,
      ])
      ->condition('uid', $target_user->id())
      ->condition('requested_uid', $this->currentUser->id())
      ->execute();

    $this->connection->update('friendship')
      ->fields([
        'status' => 1,
      ])
      ->condition('uid', $this->currentUser->id())
      ->condition('requested_uid', $target_user->id())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function removeFriend(User $target_user) {
    $this->connection->update('friendship')
      ->fields([
        'status' => -1,
      ])
      ->condition('uid', $this->currentUser->id())
      ->condition('requested_uid', $target_user->id())
      ->execute();

    $this->connection->update('friendship')
      ->fields([
        'status' => 0,
      ])
      ->condition('uid', $target_user->id())
      ->condition('requested_uid', $this->currentUser->id())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function isRequestSend(User $target_user) {
    $result = $this->getFriendshipRow($this->currentUser, $target_user);

    // Because if users is friends we have two records.
    // @todo made it more elegance.
    if (isset($result[0]->status) && $result[0]->status == 0) {
      $result = $this->getFriendshipRow($target_user, $this->currentUser);
      // @todo made it more elegance.
      if (isset($result[0]->status) && $result[0]->status == -1) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isFriend(User $target_user) {
    $result = $this->getFriendshipRow($this->currentUser, $target_user);

    // Because if users is friends we have two records.
    // @todo made it more elegance.
    if (isset($result[0]->status) && $result[0]->status == 1) {
      $result = $this->getFriendshipRow($target_user, $this->currentUser);
      // @todo made it more elegance.
      if (isset($result[0]->status) && $result[0]->status == 1) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isFollowedYou(User $target_user) {
    $result = $this->getFriendshipRow($this->currentUser, $target_user);

    // Because if users is friends we have two records.
    // @todo made it more elegance.
    if (isset($result[0]->status) && $result[0]->status == -1) {
      $result = $this->getFriendshipRow($target_user, $this->currentUser);
      // @todo made it more elegance.
      if (isset($result[0]->status) && $result[0]->status == 0) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Select all friends row.
   *
   * @param \Drupal\user\Entity\User $current_user
   *   Current user.
   * @param \Drupal\user\Entity\User $target_user
   *   Target user.
   *
   * @return object
   *   Result object.
   */
  protected function getFriendshipRow(User $current_user, User $target_user) {
    /** @var \Drupal\Core\Database\Driver\mysql\Select $query */
    $query = $this->connection->select('friendship', 'fr')
      ->fields('fr', ['status'])
      ->condition('fr.uid', $current_user->id())
      ->condition('fr.requested_uid', $target_user->id());

    $result = $query->execute()->fetchAll();

    return $result;
  }

  /**
   * Get link attributes.
   *
   * @param \Drupal\user\Entity\User $target_user
   *   Target user.
   *
   * @return array
   *   Return link attributes.
   */
  public function getLinkAttributes(User $target_user) {
    $config = \Drupal::config('friendship.settings');

    if ($this->isRequestSend($target_user)) {
      $link_attributes = [
        '#title' => $config->get('button.unfollow_text'),
        '#url' => Url::fromRoute('friendship.unfollow', [
          'uid' => $target_user->id(),
        ]),
      ];
    }
    elseif ($this->isFollowedYou($target_user)) {
      $link_attributes = [
        '#title' => $config->get('button.accept_text'),
        '#url' => Url::fromRoute('friendship.accept', [
          'uid' => $target_user->id(),
        ]),
      ];
    }
    elseif ($this->isFriend($target_user)) {
      $link_attributes = [
        '#title' => $config->get('button.remove_friend_text'),
        '#url' => Url::fromRoute('friendship.removeFriend', [
          'uid' => $target_user->id(),
        ]),
      ];
    }
    else {
      $link_attributes = [
        '#title' => $config->get('button.follow_text'),
        '#url' => Url::fromRoute('friendship.follow', [
          'uid' => $target_user->id(),
        ]),
      ];
    }

    return $link_attributes;
  }

}
