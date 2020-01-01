<?php

namespace Drupal\bolthunter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BoltForm.
 */
class BoltForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bolthunter.bolt',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bolt_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bolthunter.bolt');
    $final_pm = $config->get('get_dir_final');
    $next_pm = $config->get('get_dir_next_pm');
    drupal_set_message('Diration between last PM and next PM is set at' . $next_pm);
    drupal_set_message('Diration until last PM' . $final_pm );
    $form['dir_next_pm'] = [
  '#type' => 'select',
  '#title' => $this->t('Select diration for next PM'),
  '#options' => [
    '+3Months' => $this->t('Three Months'),
    '+6Months' => $this->t('Six Months'),
  ],
  '#default_value' => '+6Months',
];
    $form['dir_final_pm'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Final PM diration last day equipment item is good for'),
      '#description' => $this->t('When creating or updating equipment and you have auto to yes then here is the deration'),
      '#options' => [
          '+1years' => $this->t('One Year'),
          '+2years' => $this->t('Two Years'),
          '+3years' => $this->t('Three Years'),
          '+4years' => $this->t('Four Years'),
    ],
    '#default_value' => '+4years',
  ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('bolthunter.bolt')
      ->set('get_dir_next_pm', $form_state->getValue('dir_next_pm'))
      ->set('get_dir_final', $form_state->getValue('dir_final_pm'))
      ->save();
  }

}
