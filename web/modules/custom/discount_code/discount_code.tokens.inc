<?php
use Drupal\discount_code\Entity;


function discount_code_token_info() {
  return array(
    'types' => array(
      'discount_code' => array(
        'name' => t('Discount code'),
        'description' => t('Discount code token'),
      ),
    ),
    'tokens' => array(
      'discount_code' => array(
        'code' => array(
          'name' => t('Code'),
          'description' => t('Code token'),
        ),
        'name' => array(
          'name' => t('uid'),
          'description' => t('id user of discount code'),
        ),
      ),
    ),
  );
}
function discount_code_tokens($type, $tokens, array $data, array $options) {
  $replacements = [];
  if ($type == 'discount_code' && !empty($data['code'])) {
    $code = $data['code'];

    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'code':
          $replacements[$original] = $code->getCode();
          break;
        case 'user_name':
          $replacements[$original] = $code->getOwnerName();
      }
    }

  }
    return $replacements;
}
