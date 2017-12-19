<?php

namespace Drupal\discount_code\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class EditForm extends ConfigFormBase {

  public function getFormId()
  {
    return "setting_discount_code_form";
  }
  protected function getEditableConfigNames() {
    return [
      'discount_code.settings',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('discount_code.settings');
    $form['change_message'] = array(
      '#title' => $this->t('Change message'),
      '#type' => 'textfield',
      '#default_value' => $config->get('form.message'),
    );
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('discount_code.settings')->set('form.message', $values['change_message'])->save();
    drupal_set_message($this->t('Change succesful!!!'));
  }
}