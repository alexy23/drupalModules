<?php

namespace Drupal\send_queue\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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

    $queue = \Drupal::queue('email_queue');
    $queue->createQueue();

    $uids = \Drupal::entityQuery('user')
      ->execute();
    if ($uids["0"] != NULL) {
      unset($uids["0"]);
    }
    $start_time = microtime(TRUE);
    foreach ($uids as $uid) {
      $queue->createItem([
        'uid' => $uid,
      ]);
    }
    $end_time = microtime(TRUE);
    drupal_set_message(round(($end_time - $start_time), 5) . " сек");
  }

}
