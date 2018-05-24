<?php

namespace Drupal\friendship\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;

/**
 * Filter by friendship status.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("friendship_status_filter")
 */
class FriendshipStatusFilter extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Friendship status');
    $this->definition['options callback'] = [$this, 'generateOptions'];
  }

  /**
   * Override the query so that no filtering takes place if
   * the user doesn`t select options.
   */
  public function query() {
    if (!empty($this->value) && !in_array('all', $this->value)) {
      parent::query();

      if (isset($this->value['-1'])) {
        $id = 1;
      }
    }
  }

  /**
   * Skip validation if no options have been chosen so we
   * can use it as non-filter.
   */
  public function validate() {
    if (!empty($this->value) && !in_array('all', $this->value)) {
      parent::validate();
    }
  }

  /**
   * Helper which generate options.
   */
  public function generateOptions() {
    return [
      0 => 'follower',
      1 => 'friend',
      -1 => 'following',
    ];
  }

}