<?php

/**
 * Implements hook_views_data_alter().
 */
function friendship_views_data_alter(&$data) {
  $data['users_field_data']['friendship_id'] = [
    'title' => t('Friendship'),
    'help' => t('Friendship relation table.'),
    'relationship' => [
      'label' => t('Friendship'),
      'id' => 'standard',
      'base' => 'friendship',
      'base field' => 'uid',
      'relationship field' => 'uid',
    ],
  ];
}

/**
 * Implements hook_views_data().
 */
function friendship_views_data() {
  $data = [];

  $data['friendship'] = [];
  $data['friendship']['table'] = [];
  $data['friendship']['table']['group'] = t('Friendship');

  $data['friendship']['table']['provider'] = 'friendship';

  $data['friendship']['id'] = [
    'title' => t('Friendship ID'),
    'help' => t('The Friendship ID of current user.'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  $data['friendship']['uid'] = [
    'title' => t('Current UID'),
    'help' => t('The current user id.'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'relationship' => [
      'title' => t('Uid'),
      'label' => t('Friendship uid'),
      'id' => 'standard',
      'base' => 'users_field_data',
      'base field' => 'uid',
      'relationship field' => 'uid',
    ],
  ];

  $data['friendship']['requested_uid'] = [
    'title' => t('Requested UID'),
    'help' => t('The requested user id.'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'relationship' => [
      'title' => t('Requested uid'),
      'label' => t('Requested uid'),
      'id' => 'standard',
      'base' => 'users_field_data',
      'base field' => 'uid',
      'relationship field' => 'requested_uid',
    ],
  ];

  $data['friendship']['status'] = [
    'title' => t('Status'),
    'help' => t('1 if friends, 0 if followers.'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  $data['friendship']['friendship_process_link'] = [
    'title' => t('Friendship action link'),
    'help' => t('Friendship link.'),
    'field' => [
      'id' => 'friendship_process_link',
    ],
  ];

  $data['friendship']['friendship_status_filter'] = [
    'title' => t('Friendship status filter'),
    'filter' => [
      'title' => t('Friendship status filter'),
      'help' => t('Friendship status filter.'),
      'field' => 'status',
      'id' => 'friendship_status_filter',
    ],
  ];

  return $data;
}
