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

    $response = $this->getAjaxResponse($target_user);
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

    if ($this->friendshipService->isRequestSend($target_user)) {
      $this->friendshipService->unfollow($target_user);

      return $this->getAjaxResponse($target_user);
    }

    return new AjaxResponse();
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

    if ($this->friendshipService->isFollowedYou($target_user)) {
      $this->friendshipService->accept($target_user);

      return $this->getAjaxResponse($target_user);
    }

    return new AjaxResponse();
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

    if ($this->friendshipService->isFriend($target_user)) {
      $this->friendshipService->removeFriend($target_user);

      return $this->getAjaxResponse($target_user);
    }

    return new AjaxResponse();
  }

  /**
   * Return ajax response for ajax link.
   *
   * @param \Drupal\user\Entity\User $target_user
   *   User object.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response for link.
   */
  protected function getAjaxResponse(User $target_user) {
    $response = new AjaxResponse();

    $link_attributes = $this->friendshipService->getLinkAttributes($target_user);
    $action_url = $link_attributes['#url']->toString();

    $target_user_id = $target_user->id();
    $response->addCommand(new HtmlCommand('.friendship-ajax-link-' . $target_user_id, $link_attributes['#title']));
    $response->addCommand(new InvokeCommand('.friendship-ajax-link-' . $target_user_id, 'attr', ['href', $action_url]));

    return $response;
  }

}
