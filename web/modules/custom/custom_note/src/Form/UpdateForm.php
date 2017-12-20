<?php

namespace Drupal\custom_note\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Class UpdateForm.
 *
 * @package Drupal\custom_note\Form
 */
class UpdateForm extends FormBase {

  /**
   * Method get Id Form.
   *
   * @return string
   *   Return Id form.
   */
  public function getFormId() {
    return 'update_form';
  }

  /**
   * Create Form with field.
   *
   * @param array $form
   *   Get link to array create form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Get All data in form.
   *
   * @return array
   *   Return complete Form with field
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['choice'] = array(
      '#type' => 'radios',
      '#title' => t('select button to change date'),
      '#options' => array(
        t('clear date to all "Note" contents'),
        t('update date to all "Note" contents'),
      ),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#name' => 'clear',
      '#value' => 'apply changes',
    );
    return $form;
  }

  /**
   * Send data of form on work script.
   *
   * @param array $form
   *   Get link to array $form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Get All data in form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'note')
      ->execute();
    $foo = new BatchUpdateNode();
    $operations = array();
    if ($form['choice']['#value'] == 0) {
      foreach ($nids as $id) {
        $operations[] = array(array($foo, "customNoteClearStatus"), array($id));
      }
      batch_set(array(
        'operations' => $operations,
        'finished' => '\Drupal\custom_note\Form\BatchUpdateNode::customNoteFinished',
        'title' => 'Обновление дат',
        'init_message' => 'Подготовка данных',
        'progress_message' => 'Выполнено @current из @total.',
        'error_message' => 'Произошла ошибка.',
      ));
    }
    if ($form['choice']['#value'] == 1) {
      foreach ($nids as $id) {
        $operations[] = array(array($foo, "customNoteUpdateStatus"), array($id));
      }
      batch_set(array(
        'operations' => $operations,
        'finished' => '\Drupal\custom_note\Form\BatchUpdateNode::customNoteFinished',
        'title' => 'Обновление дат',
        'init_message' => 'Подготовка данных',
        'progress_message' => 'Выполнено @current из @total.',
        'error_message' => 'Произошла ошибка.',
      ));
    }
  }

}

/**
 * Class BatchUpdateNode.
 *
 * @package Drupal\custom_note\Form
 */
class BatchUpdateNode {

  /**
   * Clear field status of Note and rewrite N/A.
   *
   * @param string $nid
   *   Node Id for operation.
   * @param object $context
   *   Help variable for send context.
   */
  public function customNoteClearStatus($nid, &$context) {
    $node = Node::load($nid);
    $results[] = $node->set('field_status', NULL);
    $node->save();
    $context['results'][] = $results;
  }

  /**
   * Callback function of update field "Status".
   *
   * @param string $nid
   *   Id Node for operation.
   * @param string $context
   *   Help variable for send context.
   */
  public function customNoteUpdateStatus($nid, &$context) {
    $config = \Drupal::config('custom_note.settings');
    $node = Node::load($nid);
    $timestamp = strtotime($config->get('status.date'));
    $dateCreatedNode = $node->getCreatedTime();
    if ($timestamp > $dateCreatedNode) {
      $results[] = $node->set('field_status', '0');
    }
    else {
      $results[] = $node->set('field_status', '1');
    }
    $node->save();
    $context['results'][] = $results;
  }

  /**
   * Finished batch callback function.
   *
   * @param bool $success
   *   Successful or not successful complete operations.
   * @param $results
   *   Count successful operations.
   */
  public static function customNoteFinished($success, $results) {
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One post processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    drupal_set_message($message);
  }

}
