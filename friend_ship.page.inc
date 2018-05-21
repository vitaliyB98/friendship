<?php

/**
 * @file
 * Contains friend_ship.page.inc.
 *
 * Page callback for Friend ship entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Friend ship templates.
 *
 * Default template: friend_ship.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_friend_ship(array &$variables) {
  // Fetch FriendShip Entity Object.
  $friend_ship = $variables['elements']['#friend_ship'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
