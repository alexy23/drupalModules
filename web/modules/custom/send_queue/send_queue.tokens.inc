<?php

/**
 * Information of tokens.
 */
function send_queue_token_info() {
  return array(
    'types' => array(
      'queue' => array(
        'name' => t('Queue Message'),
        'description' => t('tokens of send message user'),
      ),
    ),
    'tokens' => array(
      'queue' => array(
        'username' => array(
          'name' => t('User name'),
          'description' => t('Name of user to send message'),
        ),
        'mail' => array(
          'name' => t('e-mail'),
          'description' => t('E-mail of user to send message'),
        ),
      ),
    ),
  );
}

/**
 * Replace tokens on data.
 */
function send_queue_tokens($type, $tokens, array $data, array $options) {
  $replacements = [];
  if ($type == 'queue' && !empty($data['user'])) {
    $user = $data['user'];
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'username':
          $replacements[$original] = $user->get('name')->value;
          break;

        case 'mail':
          $replacements[$original] = $user->get('mail')->value;
      }
    }

  }
  return $replacements;
}
