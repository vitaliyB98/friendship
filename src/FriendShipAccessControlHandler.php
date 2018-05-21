<?php

namespace Drupal\friendship;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Friend ship entity.
 *
 * @see \Drupal\friendship\Entity\FriendShip.
 */
class FriendShipAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\friendship\Entity\FriendShipInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished friend ship entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published friend ship entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit friend ship entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete friend ship entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add friend ship entities');
  }

}
