<?php
namespace Drupal\internet_reception\Form;

use Drupal;
use Drupal\Core\Mail;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CustomForm.
 *
 * @package Drupal\internet_reception\Form
 */
class CustomForm extends FormBase {

  /**
   * Function get Id form.
   *
   * @return string
   *   Return Id form
   */
  public function getFormId() {
    return 'internet_reception_form';
  }

  /**
   * Create Form with field.
   * @param array $form
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   *   Return complete Form with field
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('E-Mail'),
      '#required' => TRUE,
    );
    $form['age'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Age'),
      '#required' => TRUE,
    );
    $form['subject'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#required' => TRUE,
    );
    $form['message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      'value size' => $this->t('30'),
    );
    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (Drupal::service('email.validator')->isValid('email')) {
      $form_state->setErrorByName('email', $this->t('That e-mail address is not valid.'));
    }
    if (!is_numeric($form_state->getValue('age'))) {
      $form_state->setErrorByName('age', $this->t('Field get only numeric'));
    }
    if ($form_state->getValue('age') < 0 || $form_state->getValue('age') > 101) {
      $form_state->setErrorByName('age', $this->t('Age must set 1-100'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $langcode = 'en';
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'internet_reception';
    $key = 'internet_reception';
    $to = \Drupal::config('system.site')->get('mail');
    $name = $form_state->getValue('name');
    $age = $form_state->getValue('age');
    $message = [
      'from' => $form_state->getValue('email'),
      'message' => $name . ' ' . $age . " year\n" . $form_state->getValue('message'),
      'title' => $form_state->getValue('subject'),
    ];
    $params = array(
      'message' => $message,
    );
    $mailManager->mail($module, $key, $to, $langcode,  $params, NULL, true);
    drupal_set_message('Mail has been sent.', 'status');
  }
}