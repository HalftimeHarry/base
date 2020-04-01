<?php

namespace Drupal\bliz_odds\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CreateCustomGameSettingForm.
 */
class CreateCustomGameSettingForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bliz_odds.createcustomgamesetting',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'create_custom_game_setting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bliz_odds.createcustomgamesetting');
    $form['special_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Special Message'),
      '#description' => $this->t('Create a message'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('special_message'),
    ];
    $form['auto_create_past_game'] = [
      '#type' => 'select',
      '#title' => $this->t('Auto Create Past Game'),
      '#description' => $this->t('Make new past game with ID'),
      '#options' => ['no' => $this->t('NO'),
                     'yes' => $this->t('YES')

    ],
      '#size' => yes,
      '#default_value' => $config->get('auto_create_past_game'),
    ];
    $form['bliz_number_to_use'] = [
      '#type' => 'number',
      '#title' => $this->t('Bliz ID Number to use'),
      '#description' => $this->t('Increase from the latest Bliz ID from the past game on the list'),
      '#default_value' => $config->get('number_to_use'),
    ];
    $form['season'] = [
      '#type' => 'number',
      '#title' => $this->t('Season'),
      '#description' => $this->t('Season'),
      '#default_value' => $config->get('season'),
    ];
    $form['week'] = [
      '#type' => 'number',
      '#title' => $this->t('Week'),
      '#description' => $this->t('Week'),
      '#default_value' => $config->get('week'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('bliz_odds.createcustomgamesetting')
      ->set('auto_create_past_game', $form_state->getValue('auto_create_past_game'))
      ->set('bliz_number_to_use', $form_state->getValue('bliz_number_to_use'))
      ->set('special_message', $form_state->getValue('special_message'))
      ->set('season', $form_state->getValue('season'))
      ->set('week', $form_state->getValue('week'))
      ->save();
  }

}
