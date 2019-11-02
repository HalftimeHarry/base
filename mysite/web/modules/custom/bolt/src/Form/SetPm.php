<?php

namespace Drupal\bolt\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SetPm.
 */
class SetPm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bolt.setpm',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'set_pm';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bolt.setpm');

    $options = [
      '0' => t('None'),
      '3' => t('3 Months from Expiration'),
      '6' => t('6 Months from Expiration'),
    ];


    $form['set_pm'] = [
      '#type' => 'radios',
      '#title' => $this->t('Set PM'),
      '#options' => $options,
      '#description' => $this->t('How would you like to set your PM'),
      '#default_value' => $config->get('0'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    dpm('Now set to '. $form_state->getValue('set_pm').' Months');
    $this->config('bolt.setpm')
      ->set('set_pm', $form_state->getValue('set_pm'))
      ->save();
  }

}
