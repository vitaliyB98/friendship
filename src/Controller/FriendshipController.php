<?php

namespace Drupal\friendship\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\friendship\FriendshipService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FriendshipController.
 */
class FriendshipController extends ControllerBase {

  /**
   * Friendship Service.
   *
   * @var \Drupal\friendship\FriendshipService
   */
  protected $friendshipService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('friendship.friendship_service')
    );
  }

  /**
   * Constructor.
   *
   * @param \Drupal\friendship\FriendshipService $friendshipService
   *   Friendship service.
   */
  public function __construct(FriendshipService $friendshipService) {
    $this->friendshipService = $friendshipService;
  }

  /**
   * Follow user.
   *
   * @param string $uid
   *   User id.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response.
   */
  public function follow($uid) {
    $target_user = User::load($uid);
    $this->friendshipService->follow($target_user);

    $response = new AjaxResponse();

    $link_attributes = $this->friendshipService->getLinkAttributes($target_user);
    $action_url = $link_attributes['#url']->toString();
    $response->addCommand(new HtmlCommand('#friendship-ajax-link', $link_attributes['#title']));
    $response->addCommand(new InvokeCommand('#friendship-ajax-link', 'attr', ['href', $action_url]));

    return $response;
  }

  /**
   * Unfollow user.
   *
   * @param string $uid
   *   User id.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response.
   */
  public function unfollow($uid) {
    $target_user = User::load($uid);
    $this->friendshipService->unfollow($target_user);

    $response = new AjaxResponse();

    $response->addCommand(new HtmlCommand('#friendship-ajax-link', 'Some text'));
    $response->addCommand(new InvokeCommand('#friendship-ajax-link', 'attr', ['href', '#']));

    return $response;
  }

  /**
   * Accept user.
   *
   * @param string $uid
   *   User id.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response.
   */
  public function accept($uid) {
    $target_user = User::load($uid);
    $this->friendshipService->accept($target_user);

    $response = new AjaxResponse();

    $response->addCommand(new HtmlCommand('#friendship-ajax-link', 'Some text'));
    $response->addCommand(new InvokeCommand('#friendship-ajax-link', 'attr', ['href', '#']));

    return $response;
  }

  /**
   * Remove friend user.
   *
   * @param string $uid
   *   User id.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response.
   */
  public function removeFriend($uid) {
    $target_user = User::load($uid);
    $this->friendshipService->removeFriend($target_user);

    $response = new AjaxResponse();

    $response->addCommand(new HtmlCommand('#friendship-ajax-link', 'Some text'));
    $response->addCommand(new InvokeCommand('#friendship-ajax-link', 'attr', ['href', '#']));

    return $response;
  }

}
