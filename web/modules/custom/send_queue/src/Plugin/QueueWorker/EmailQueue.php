<?php

namespace Drupal\send_queue\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\user\Entity\User;

/**
 * @file
 * Contains \Drupal\send_queue\Plugin\QueueWorker\EmailQueue.
 */

/**
 * Processes Tasks for Learning.
 *
 * @QueueWorker(
 *   id = "email_queue",
 *   title = @Translation("Task worker: email queue"),
 *   cron = {"time" = 60}
 * )
 */
class EmailQueue extends QueueWorkerBase {

  public function processItem($uid) {
    $langcode = 'en';
    $token = \Drupal::token();
    $user = User::load($uid['uid']);
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'send_queue';
    $key = 'send_queue';
    $to = $user->get('mail')->value;
    $config = \Drupal::config('send_queue.settings');
    $text = $config->get('mail.message');
    $string = $token->replace($text, ['user' => $user]);
    $params = [
      'message' => $string,
    ];
    $mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);
  }
}