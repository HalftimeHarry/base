<?php

namespace Drupal\baz\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BazConfigForm.
 *
 * @package Drupal\baz\Form
 */
class BazConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'baz.bazconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'baz_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('baz.bazconfig');
 $form['diable_last_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#description' => $this->t('Set on the week to eliminate this will also eliminate prior weeks'),
         '#options' => [
                    '0' => $this->t('Week 0'),
                    '1' => $this->t('Week 1'),
                    '2' => $this->t('Week 2'),
                    '3' => $this->t('Week 3'),
                    '4' => $this->t('Week 4'),
                    '5' => $this->t('Week 5'),
                    '6' => $this->t('Week 6'),
                    '7' => $this->t('Week 7'),
                    '8' => $this->t('Week 8'),
                    '9' => $this->t('Week 9'),
                    '10' => $this->t('Week 10'),
                    '11' => $this->t('Week 11'),
                    '12' => $this->t('Week 12'),
                    '13' => $this->t('Week 13'),
                    '14' => $this->t('Week 14'),
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
        
    return parent::buildForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('baz.bazconfig')
      ->set('diable_last_week', $form_state->getValue('diable_last_week'))
      ->save();
      
  }

}
