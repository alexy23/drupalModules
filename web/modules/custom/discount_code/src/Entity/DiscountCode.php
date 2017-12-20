<?php

namespace Drupal\discount_code\Entity;

/**
 * @file
 * Contains \Drupal\discount_code\Entity\DiscountCode.
 */


use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\Entity\User;

/**
 * Defines the DiscountCode entity.
 *
 * @ingroup discount_code
 *
 * @ContentEntityType(
 *   id = "discount_code",
 *   label = @Translation("Discount code"),
 *   base_table = "discount_code",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *     "code" = "code",
 *   },
 * )
 */
class DiscountCode extends ContentEntityBase implements ContentEntityInterface {

  /**
   * Return id of register user.
   */
  public function getUid() {
    return $this->getEntityKey('uid');
  }

  /**
   * Return generate promocode of register user.
   */
  public function getCode() {
    $test = $this->getEntityKey('code');
    return $test;
  }

  /**
   * Return Name register account.
   */
  public function getOwnerName() {
    return User::load($this->getUid())->getDisplayName();
  }

  /**
   * Generate Promocode for users.
   */
  public static function generateDiscountCode() {
    $temp_array_gen = array();
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $count_gen_liter = 10;
    for ($i = 0; $i < $count_gen_liter; $i++) {
      $n = rand(0, strlen($alphabet) - 1);
      $temp_array_gen[] = $alphabet[$n];
    }
    return implode('', $temp_array_gen);
  }

  /**
   * Determines the schema for the base_table property defined above.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Discount Code entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Discount Code entity.'))
      ->setReadOnly(TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('UID'))
      ->setDescription(t('The UID of the  reference user id entity.'))
      ->setReadOnly(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');

    $fields['code'] = BaseFieldDefinition::create('string')
      ->setLabel(t("The Promo code"))
      ->setDescription(t('The code of user.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ));

    return $fields;
  }

}
