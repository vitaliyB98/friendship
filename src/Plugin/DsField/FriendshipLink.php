<?php

namespace Drupal\friendship\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Friendship link add/remove/follow.
 *
 * @DsField(
 *   id = "friendship_link",
 *   title = @Translation("Friendship link."),
 *   entity_type = "user"
 * )
 * @package Drupal\friendship\Plugin\DsField
 */
class FriendshipLink extends DsFieldBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var \Drupal\user\Entity\User $target_user */
    $target_user = $this->configuration['entity'];
    /** @var \Drupal\user\Entity\User $current_user */
    $current_user = \Drupal::currentUser();

    $build = [];

    if ($target_user->id() != $current_user->id()) {
      $friendship = \Drupal::service('friendship.friendship_service');

      $build = [
        '#type' => 'link',
        '#attributes' => [
          'class' => [
            'use-ajax',
            'friendship-ajax-link-' . $target_user->id(),
          ],
        ],
        '#attached' => [
          'library' => [
            'core/drupal.ajax',
          ],
        ],
        '#cache' => [
          'max-age' => 0,
        ],
      ];

      $link_attributes = $friendship->getLinkAttributes($target_user);
      $build += $link_attributes;
    }

    return $build;
  }

}
