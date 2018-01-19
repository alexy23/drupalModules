<?php

namespace Drupal\date_popup_timepicker\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldWidget\DateTimeWidgetBase;
use Drupal\Component\Utility\Html;

/**
 * Plugin implementation of the 'datetime_datelist' widget.
 *
 * @FieldWidget(
 *   id = "datetime_timepicker",
 *   label = @Translation("Timepicker"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class TimePickerWidget extends DateTimeWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $name = $items->getName();
    $name = $name . '[' . $delta . '][value][time]';
    $element['#attached']['library'][] = 'date_popup_timepicker/timepicker';
    $element['#attached']['drupalSettings']['datePopup'][$name] = array(
      'settings' => $this->processFieldSettings($this->getSettings()),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // The character to use to separate hours and minutes.
      'timeSeparator' => ':',
      // Define whether or not to show a leading zero for hours < 10.
      'showLeadingZero' => TRUE,
      // Define whether or not to show a leading zero for minutes < 10.
      'showMinutesLeadingZero' => TRUE,
      // Define whether or not to show AM/PM with selected time.
      'showPeriod' => FALSE,
      // Define if the AM/PM labels on the left are displayed.
      'showPeriodLabels' => TRUE,
      // The character to use to separate the time from the time period.
      'periodSeparator' => ' ',
      // Define an alternate input to parse selected time to.
      'altField' => '#alternate_input',
      // Used as default time when input field is empty or for inline
      // timePicker.
      // Set to 'now' for the current time, '' for no highlighted time.
      'defaultTime' => 'now',
      // Trigger options.
      // Define when the timepicker is shown.
      // 'focus': when the input gets focus, 'button' when the button
      // trigger element is clicked.
      // 'both': when the input gets focus and when the button is clicked.
      'showOn' => 'focus',
      // jQuery selector that acts as button trigger. ex: '#trigger_button'.
      'button' => NULL,
      // Localization.
      // Define the locale text for "Hours".
      'hourText' => 'Hour',
      // Define the locale text for "Minute".
      'minuteText' => 'Minute',
      // Define the locale text for periods.
      'amPmText' => array('AM', 'PM'),
      // Position.
      // Corner of the dialog to position, used with the jQuery UI Position
      // utility if present.
      'myPosition' => 'left top',
      // Corner of the input to position.
      'atPosition' => 'left bottom',
      // Events.
      // Callback function executed before the timepicker is
      // rendered and displayed.
      'beforeShow' => NULL,
      // Define a callback function when an hour / minutes is selected.
      'onSelect' => NULL,
      // Define a callback function when the timepicker is closed.
      'onClose' => NULL,
      // Define a callback to enable / disable certain hours.
      // ex: function onHourShow(hour).
      'onHourShow' => NULL,
      // Define a callback to enable / disable certain minutes. ex:
      // function onMinuteShow(hour, minute).
      'onMinuteShow' => NULL,
      // Custom hours and minutes.
      'hours' => array(
        // First displayed hour.
        'starts' => 0,
        // Last displayed hour.
        'ends' => 23,
      ),
      'minutes' => array(
        // First displayed minute.
        'starts' => 0,
        // Last displayed minute.
        'ends' => 55,
        // Interval of displayed minutes.
        'interval' => 5,
        // Optional extra entries for minutes.
        'manual' => array(),
      ),
      // Number of rows for the input tables, minimum 2,
      // makes more sense if you use multiple of 2.
      'rows' => 4,
      // Define if the hours section is displayed or not.
      // Set to false to get a minute only dialog.
      'showHours' => TRUE,
      // Define if the minutes section is displayed or not.
      // Set to false to get an hour only dialog.
      'showMinutes' => TRUE,

      // Min and Max time.
      // Set the minimum time selectable by the user, disable hours and minutes
      // previous to min time.
      'minTime' => array(
        'hour' => 0,
        'minute' => 0,
      ),
      // Set the minimum time selectable by the user, disable hours and minutes
      // after max time.
      'maxTime' => array(
        'hour' => 23,
        'minute' => 59,
      ),
      // Buttons.
      // Shows an OK button to confirm the edit.
      'showCloseButton' => FALSE,
      // Text for the confirmation button (ok button).
      'closeButtonText' => 'Done',
      // Shows the 'now' button.
      'showNowButton' => FALSE,
      // Text for the now button.
      'nowButtonText' => 'Now',
      // Shows the deselect time button.
      'showDeselectButton' => FALSE,
      // Text for the deselect button.
      'deselectButtonText' => 'Deselect',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $options = $this->getSettings();

    $element['timeSeparator'] = array(
      '#type' => 'textfield',
      '#title' => t('Time separator'),
      '#description' => t('The character to use to separate hours and minutes.'),
      '#default_value' => $options['timeSeparator'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showLeadingZero'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show leading zero'),
      '#description' => t('Define whether or not to show a leading zero for hours < 10.'),
      '#default_value' => $options['showLeadingZero'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showMinutesLeadingZero'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show minutes leading zero'),
      '#description' => t('Define whether or not to show a leading zero for minutes < 10.'),
      '#default_value' => $options['showMinutesLeadingZero'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showPeriod'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show period'),
      '#description' => t('Define whether or not to show AM/PM with selected time.'),
      '#default_value' => $options['showPeriod'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showPeriodLabels'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show period labels'),
      '#description' => t('Define if the AM/PM labels on the left are displayed.'),
      '#default_value' => $options['showPeriodLabels'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['periodSeparator'] = array(
      '#type' => 'textfield',
      '#title' => t('Period separator'),
      '#description' => t('The character to use to separate the time from the time period.'),
      '#default_value' => $options['periodSeparator'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['defaultTime'] = array(
      '#type' => 'textfield',
      '#title' => t('Default time'),
      '#description' => t("Used as default time when input field is empty or for inline timePicker. Set to 'now' for the current time, '' for no highlighted time."),
      '#default_value' => $options['defaultTime'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showOn'] = array(
      '#type' => 'select',
      '#title' => t('Show on'),
      '#description' => t("Define when the timepicker is shown."),
      '#options' => array(
        'focus' => t('Focus'),
        'button' => t('Button'),
        'both' => t('Both'),
      ),
      '#default_value' => $options['showOn'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['hourText'] = array(
      '#type' => 'textfield',
      '#title' => t('Hour text'),
      '#default_value' => $options['hourText'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['minuteText'] = array(
      '#type' => 'textfield',
      '#title' => t('Minute text'),
      '#default_value' => $options['minuteText'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['amPmText'] = array(
      '#type' => 'fieldset',
      '#title' => t('Periods text'),
      '#collapsible' => FALSE,
      0 => array(
        '#type' => 'textfield',
        '#title' => t('AM'),
        '#default_value' => $options['amPmText'][0],
      ),
      1 => array(
        '#type' => 'textfield',
        '#title' => t('PM'),
        '#default_value' => $options['amPmText'][1],
      ),
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['hours'] = array(
      '#type' => 'fieldset',
      '#title' => t('Hours'),
      '#collapsible' => FALSE,
      'starts' => array(
        '#type' => 'textfield',
        '#title' => t('Starts'),
        '#description' => t('First displayed hour.'),
        '#default_value' => $options['hours']['starts'],
      ),
      'ends' => array(
        '#type' => 'textfield',
        '#title' => t('Ends'),
        '#description' => t('Last displayed hour.'),
        '#default_value' => $options['hours']['ends'],
      ),
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['minutes'] = array(
      '#type' => 'fieldset',
      '#title' => t('Minutes'),
      '#collapsible' => FALSE,
      'starts' => array(
        '#type' => 'textfield',
        '#title' => t('Starts'),
        '#description' => t('First displayed minute.'),
        '#default_value' => $options['minutes']['starts'],
      ),
      'ends' => array(
        '#type' => 'textfield',
        '#title' => t('Ends'),
        '#description' => t('Last displayed minute.'),
        '#default_value' => $options['minutes']['ends'],
      ),
      'interval' => array(
        '#type' => 'textfield',
        '#title' => t('Interval'),
        '#description' => t('Interval of displayed minutes.'),
        '#default_value' => $options['minutes']['interval'],
      ),
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['rows'] = array(
      '#type' => 'textfield',
      '#title' => t('Rows'),
      '#description' => t('Number of rows for the input tables, minimum 2, makes more sense if you use multiple of 2.'),
      '#default_value' => $options['rows'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showHours'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show hours'),
      '#description' => t('Define if the hours section is displayed or not. Set to false to get a minute only dialog.'),
      '#default_value' => $options['showHours'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showMinutes'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show minutes'),
      '#description' => t('Define if the minutes section is displayed or not. Set to false to get an hour only dialog.'),
      '#default_value' => $options['showMinutes'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['minTime'] = array(
      '#type' => 'fieldset',
      '#title' => t('Min time'),
      '#description' => t('Set the minimum time selectable by the user, disable hours and minutes previous to min time.'),
      '#collapsible' => FALSE,
      'hour' => array(
        '#type' => 'textfield',
        '#title' => t('Min hour'),
        '#default_value' => $options['minTime']['hour'],
      ),
      'minute' => array(
        '#type' => 'textfield',
        '#title' => t('Min minute'),
        '#default_value' => $options['minTime']['minute'],
      ),
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['maxTime'] = array(
      '#type' => 'fieldset',
      '#title' => t('Max time'),
      '#description' => t('Set the minimum time selectable by the user, disable hours and minutes after max time.'),
      '#collapsible' => FALSE,
      'hour' => array(
        '#type' => 'textfield',
        '#title' => t('Max hour'),
        '#default_value' => $options['maxTime']['hour'],
      ),
      'minute' => array(
        '#type' => 'textfield',
        '#title' => t('Max minute'),
        '#default_value' => $options['maxTime']['minute'],
      ),
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showCloseButton'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show close button'),
      '#description' => t('Shows an OK button to confirm the edit.'),
      '#default_value' => $options['showCloseButton'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['closeButtonText'] = array(
      '#type' => 'textfield',
      '#title' => t('Close button text'),
      '#description' => t('Text for the confirmation button (ok button).'),
      '#default_value' => $options['closeButtonText'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showNowButton'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show now button'),
      '#description' => t('Shows the "now" button.'),
      '#default_value' => $options['showNowButton'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['nowButtonText'] = array(
      '#type' => 'textfield',
      '#title' => t('Now button text'),
      '#description' => t('Text for the now button.'),
      '#default_value' => $options['nowButtonText'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['showDeselectButton'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show deselect button'),
      '#description' => t('Shows the deselect time button.'),
      '#default_value' => $options['showDeselectButton'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    $element['deselectButtonText'] = array(
      '#type' => 'textfield',
      '#title' => t('Deselect button text'),
      '#description' => t('Text for the deselect button.'),
      '#default_value' => $options['deselectButtonText'],
      '#element_validate' => array(
        array($this, 'fieldSettingsFormValidate'),
      ),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function fieldSettingsFormValidate(&$element, FormStateInterface $form_state) {
    // Validate int hours and minutes settings.
    $copy_element_settings = $element['#parents'];
    unset($copy_element_settings[count($copy_element_settings) - 1]);
    $settings = &$form_state->getValue($copy_element_settings);
    foreach (array('hours', 'minutes', 'minTime', 'maxTime') as $key) {
      foreach ($settings[$key] as $subkey => $value) {
        // Init validation limits.
        if ($key == 'minutes' && $subkey == 'interval') {
          $limits = array(1, 59);
        }
        elseif ($key == 'hours' || $subkey == 'hour') {
          $limits = array(0, 23);
        }
        // Remaining things are minutes.
        else {
          $limits = array(0, 59);
        }
        // Validate.
        if ($value !== '') {
          if (!is_numeric($value) || intval($value) != $value || $value < $limits[0] || $value > $limits[1]) {
            $t_args = array(
              '%name' => $element[$key]['#title'],
              '@start' => $limits[0],
              '@end' => $limits[1],
            );
            $form_state->setErrorByName($element['timepicker_options'][$key][$subkey],
              t('%name must be an integer between @start and @end.', $t_args));
          }
          else {
            $form_state->set($settings[$key][$subkey], (int) $value);
          }
        }
        else {
          $form_state->set($settings[$key][$subkey], NULL);
        }
      }
    }

    // Validate rows part.
    $value = $settings['rows'];
    if ($value !== '') {
      if (!is_numeric($value) || intval($value) != $value || $value < 2) {
        $t_args = array(
          '%name' => $element['timepicker_options']['rows']['#title'],
        );
        $form_state->setErrorByName($element['timepicker_options']['rows'],
          t('%name must be an integer greater than 1.', $t_args));
      }
      else {
        $form_state->set($settings['rows'], (int) $value);
      }
    }
    else {
      $form_state->set($settings['rows'], NULL);
    }
  }

  /**
   * Function of typification options Timepicker.
   *
   * @param array $settings
   *   Settings for JS Timepicker.
   *
   * @return array
   *   arrChanged settings after typefications of all parameters.
   */
  public function processFieldSettings(array $settings) {
    $options = isset($settings) ? $settings : array();
    if (!empty($options)) {
      // @todo Define this list somewhere else since it's used in the DatePopupTimepickerTimepicker::fieldSettingsFormSubmit() as well.
      // @todo Shorten code if possible.
      $groups = array(
        'boolean' => array(
          'showLeadingZero',
          'showMinutesLeadingZero',
          'showPeriod',
          'showPeriodLabels',
          'showHours',
          'showMinutes',
          'showCloseButton',
          'showNowButton',
          'showDeselectButton',
        ),
        'int' => array(
          'hours',
          'minutes',
          'rows',
          'hour',
          'minute',
          'interval',
          'starts',
          'ends',
        ),
        'no_filtering' => array(
          'timeSeparator',
          'periodSeparator',
        ),
      );
      // Callback for the array_walk_recursive().
      $filter = function (&$item, $key, $groups) {
        if (in_array($key, $groups['boolean'], TRUE)) {
          $item = (bool) $item;
        }
        elseif (in_array($key, $groups['int'], TRUE)) {
          $item = (int) $item;
        }
        elseif (in_array($key, $groups['no_filtering'], TRUE)) {
          // Do nothing.
        }
        else {
          // @todo Use filter_xss_admin() instead?
          $item = Html::escape($item);
        }
      };
      // Filter user submitted settings since plugin builds output by just
      // concatenation of strings so it's possible, for example,
      // to insert html into labels.
      array_walk_recursive($options, $filter, $groups);
    }
    return $options;
  }

}
