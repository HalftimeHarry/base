<?php

namespace Drupal\pga_imp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ImportSettingsForm.
 */
class ImportSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'pga_imp.importsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'import_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('pga_imp.importsettings');
    $form['set_tournament_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Set tournament id'),
      '#description' => $this->t('Example 012'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('set_tournament_id'),
    ];
    $form['allow_import'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Allow import'),
      '#description' => $this->t('Allow importing'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('allow_import'),
    ];
    $form['tournament_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Taxonomy Term name to reference'),
      '#description' => $this->t('Our intrest is this ID'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('tournament_name'),
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

    $this->config('pga_imp.importsettings')
      ->set('set_tournament_id', $form_state->getValue('set_tournament_id'))
      ->set('allow_import', $form_state->getValue('allow_import'))
      ->set('tournament_name', $form_state->getValue('tournament_name'))
      ->save();
  }

}
