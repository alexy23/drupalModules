<?php

namespace Drupal\send_queue\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Class MessageQueue.
 *
 * @package Drupal\send_queue\Form.
 */
class MessageQueue extends FormBase {

  /**
   * Standart function return Form id.
   */
  public function getFormId() {
    return 'queue_message_form_id';
  }

  /**
   * Standart function create Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('send_queue.settings');
    $form['edit_message'] = [
      '#title' => 'Editing message',
      '#type' => 'textarea',
      '#default_value' => $config->get('mail.message'),
    ];
    $form['submit'] = [
      '#title' => 'Sending',
      '#type' => 'submit',
      '#value' => 'Send',
    ];
    return $form;
  }

  /**
   * Standart function send data form to server.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    \Drupal::configFactory()
      ->getEditable('send_queue.settings')
      ->set('mail.message', $values['edit_message'])
      ->save();

    $queue = \Drupal::queue('send_message');
    $users = User::loadMultiple();
    if ($users['0']) {
      unset($users['0']);
    }
    foreach ($users as $user) {
      $queue->createItem([
        'name' => $user->getDisplayName(),
        'email' => $user->getEmail(),
      ]);
    }
  }

}
