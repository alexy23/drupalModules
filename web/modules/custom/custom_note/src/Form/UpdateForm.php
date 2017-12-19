<?php

namespace Drupal\custom_note\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Component\Utility\SafeMarkup;

class UpdateForm extends FormBase
{
  public function getFormId()
  {
    return 'update_form';
  }

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

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'note')
      ->execute();
    $foo = new batchUpdateNode();
    if($form['choice']['#value'] == 0) {
      foreach ($nids as $id) {
        $operations[] = array(array($foo, "customNoteClearStatus"), array($id));
      }
      batch_set(array(
        'operations' => $operations,
        'finished' => '\Drupal\custom_note\Form\batchUpdateNode::customNoteFinished',
        'title' => 'Обновление дат',
        'init_message' => 'Подготовка данных',
        'progress_message' => 'Выполнено @current из @total.',
        'error_message' => 'Произошла ошибка.',
      ));
    }
    if($form['choice']['#value'] == 1){
      foreach ($nids as $id) {
        $operations[] = array(array($foo, "customNoteUpdateStatus"), array($id));
      }
      batch_set(array(
        'operations' => $operations,
        'finished' => '\Drupal\custom_note\Form\batchUpdateNode::customNoteFinished',
        'title' => 'Обновление дат',
        'init_message' => 'Подготовка данных',
        'progress_message' => 'Выполнено @current из @total.',
        'error_message' => 'Произошла ошибка.',
      ));
    }
  }

}
class batchUpdateNode
{
  private $results;

  public function customNoteClearStatus($nid, &$context){
    $node = Node::load($nid);
    $results[] = $node->set('field_status', NULL);
    $node->save();
    $context['results'][] = $results;
  }

  public function customNoteUpdateStatus($nid, &$context) {
    $config = \Drupal::config('custom_note.settings');
    $node = Node::load($nid);
    $timestamp = strtotime($config->get('status.date'));
    $dateCreatedNode = $node->getCreatedTime();
    $var1 = date("Y-m-d H:i:s", $timestamp);
    $var2 = date("Y-m-d H:i:s", $dateCreatedNode);
    if ($timestamp > $dateCreatedNode) {
      $results[] = $node->set('field_status', '0');
    }
    else {
      $results[] = $node->set('field_status', '1');
    }
    $node->save();
    $context['results'][] = $results;
  }

  static function customNoteFinished($success, $results, $operations) {
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