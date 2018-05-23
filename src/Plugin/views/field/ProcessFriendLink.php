<?php

namespace Drupal\friendship\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * A process link for friendship workflow.
 *
 * - format: (REQUIRED) Either a string format id to use for this field or an
 *           array('field' => {$field}) where $field is the field in this table
 *           used to control the format such as the 'format' field in the node,
 *           which goes with the 'body' field.
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
    return [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => 'Test',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {}

}
