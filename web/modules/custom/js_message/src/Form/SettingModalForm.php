<?php

namespace Drupal\js_message\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingModalForm extends ConfigFormBase{

  public function getFormId()
  {
    return "setting_modal_form";
  }
  protected function getEditableConfigNames() {
    return [
      'js_message.settings',
    ];
  }

  function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('js_message.settings');

    $form['title'] = array(
      '#title' => $this->t('Change title form'),
      '#type' => 'textfield',
      '#default_value' => $config->get('form.title_form'),
    );
    $form['width'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Change width form'),
      '#default_value' => $config->get('form.width'),
    );
    $form['background'] = array(
      '#type' => 'color',
      '#title' => $this->t('Select you color for background form'),
      '#default_value' => $config->get('form.background_color'),
    );
    $form['background_head'] = array(
      '#type' => 'color',
      '#title' => $this->t('Select you color for background form head'),
      '#default_value' => $config->get('form.background_color_head'),
    );
    return parent::buildForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('js_message.settings')->set('form.title_form', $values['title'])->save();
    $this->config('js_message.settings')->set('form.background_color', $values['background'])->save();
    $this->config('js_message.settings')->set('form.width', $values['width'])->save();
    $this->config('js_message.settings')->set('form.background_color_head', $values['background_head'])->save();
    drupal_set_message($this->t('Change succesful!!!'));
  }

}