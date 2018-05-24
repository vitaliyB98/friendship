<?php

namespace Drupal\friendship\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("friendship_process_link")
 */
class ProcessFriendLink extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    /** @var \Drupal\user\Entity\User $target_user */
    $target_user = $values->_entity;

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

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid query in this field.
  }

  /**
   * Define the available options.
   *
   * @return array
   *   Return options.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $options = parent::buildOptionsForm($form, $form_state);

    return $options;
  }

}
