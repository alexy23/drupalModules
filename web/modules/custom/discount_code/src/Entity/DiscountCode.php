<?php

namespace Drupal\discount_code\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;
/**
 * Defines the discount_code entity.
 *
 *
 * @ContentEntityType(
 *   id = "discount_code",
 *   label = @Translation("Discount Code"),
 *   base_table = "discount_code",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *     "uid" = "uid",
 *     "code" = "code",
 *   },
 * )
 */

/**
 * Class DiscountCode.
 *
 * @package Drupal\discount_code\Entity
 */
class DiscountCode extends ContentEntityBase implements ContentEntityInterface {
  /**
   * Extends class baseFieldDefinition and create enitity discount_code.
   * @param EntityTypeInterface $entity_type
   * @return array|\Drupal\Core\Field\FieldDefinitionInterface[]
   */

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setDescription(t('The user UUID.'));

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('UID'))
      ->setDescription(t('The UID of the  reference user id entity.'))
      ->setReadOnly(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');

    $fields['code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Code'))
      ->setDescription('10-signed string of the user for  promo')
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 16,
        'text_processing' => 0,
      ));

    return $fields;
  }

  /**
   * Generate promo code user after register.
   *
   * @return string
   *   Return string generate code.
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
   * Function request field code in entity of database.
   *
   * @return mixed
   *   Return object entity in db.
   */
  //  public function getCode() {
  //    return $this->getEntityKey('code');
  //  }
  //
  //  /**
  //   * Function request field Uid in table discount_code of database.
  //   *
  //   * @return mixed
  //   *   Return object entity in db.
  //   */
  //  public function getUid() {
  //    return $this->getEntityKey('uid');
  //  }
  //
  //  /**
  //   * Function request name of user in entity promo code.
  //   *
  //   * @return array|\Drupal\Component\Render\MarkupInterface|mixed|null|string
  //   *   Return name of object user which relation with discount code.
  //   */
  //  public function getOwnerName() {
  //    return User::load($this->getUid())->getDisplayName();
  //  }


}
