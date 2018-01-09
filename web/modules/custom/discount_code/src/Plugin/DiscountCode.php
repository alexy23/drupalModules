<?php

namespace Drupal\discount_code\Plugin\views\wizard;

use Drupal\views\Plugin\views\wizard\WizardPluginBase;

/**
 * Tests creating managed files views with the wizard.
 *
 * @ViewsWizard(
 *   id = "discount_code",
 *   base_table = "discount_code",
 *   title = @Translation("Code")
 * )
 */
class DiscountCode extends WizardPluginBase {

  /**
   * Set the created column.
   *
   * @var string
   */
  protected $createdColumn = 'created';

  /**
   * {@inheritdoc}
   */
  protected function defaultDisplayOptions() {
    $display_options = parent::defaultDisplayOptions();

    // Add permission-based access control.
    $display_options['access']['type'] = 'perm';

    // Remove the default fields, since we are customizing them here.
    unset($display_options['fields']);

    /* Field: File: Name */
    $display_options['fields']['filename']['id'] = 'code';
    $display_options['fields']['filename']['table'] = 'discount_code';
    $display_options['fields']['filename']['field'] = 'code';
    $display_options['fields']['filename']['entity_type'] = 'discount_code';
    $display_options['fields']['filename']['entity_field'] = 'code';
    $display_options['fields']['filename']['label'] = '';
    $display_options['fields']['filename']['alter']['alter_text'] = 0;
    $display_options['fields']['filename']['alter']['make_link'] = 0;
    $display_options['fields']['filename']['alter']['absolute'] = 0;
    $display_options['fields']['filename']['alter']['trim'] = 0;
    $display_options['fields']['filename']['alter']['word_boundary'] = 0;
    $display_options['fields']['filename']['alter']['ellipsis'] = 0;
    $display_options['fields']['filename']['alter']['strip_tags'] = 0;
    $display_options['fields']['filename']['alter']['html'] = 0;
    $display_options['fields']['filename']['hide_empty'] = 0;
    $display_options['fields']['filename']['empty_zero'] = 0;
    $display_options['fields']['filename']['plugin_id'] = 'field';
    
    return $display_options;
  }

}
