<?php

namespace Drupal\awntrack\Plugin\BusinessRulesAction;

use Drupal\business_rules\ActionInterface;
use Drupal\business_rules\Events\BusinessRulesEvent;
use Drupal\business_rules\ItemInterface;
use Drupal\business_rules\Plugin\BusinessRulesActionPlugin;
use Drupal\Core\Form\FormStateInterface;
use Drupal\business_rules\VariableObject;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class RunCacheClearAction.
 *
 * @package Drupal\awntrack\Plugin\BusinessRulesAction
 *
 * @BusinessRulesAction(
 *   id = "clr_action",
 *   label = @Translation("Clear Cache"),
 *   group = @Translation("Awntrack"),
 *   description = @Translation("Need to clear cache after login"),
 *   reactsOnIds = {},
 *   isContextDependent = FALSE,
 *   hasTargetEntity = FALSE,
 *   hasTargetBundle = FALSE,
 *   hasTargetField = FALSE,
 * )
 */
class RunCacheClearAction extends BusinessRulesActionPlugin {

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm(array &$form, FormStateInterface $form_state, ItemInterface $item) {
      // TODO currently this is not used add it if you need to adjust the durations


    $settings['variable'] = [
      '#type'          => 'select',
      '#title'         => t('Variable to store the result'),
      '#options'       => $this->util->getVariablesOptions(['custom_value_variable']),
      '#default_value' => $item->getSettings('variable'),
      '#required'      => TRUE,
      '#description'   => t('The variable to store the value. Only variables type "Custom value" are allowed.'),
    ];

    return $settings;
  }


  /**
   * {@inheritdoc}
   */
  public function getVariables(ItemInterface $item) {
    $variableSet = parent::getVariables($item);
    $variableObj = new VariableObject($item->getSettings('variable'), NULL, 'custom_value_variable');
    $variableSet->append($variableObj);

    return $variableSet;
  }

  /**
   * {@inheritdoc}
   */
  public function execute(ActionInterface $action, BusinessRulesEvent $event) {
    $variables           = $event->getArgument('variables');
    $set_role           = $action->getSettings('variable');
    $setting_1_processed = $this->processVariables($set_role, $variables);

    // TODO write your execution code here.

    drupal_flush_all_caches();
    $flush = 'Caches were flushed.';

    $result = [
      '#type'   => 'markup',
      '#markup' => t(' Used %setting_1 and %setting_2 and %setting_3',  [
        '%setting_1' => $set_role,
        '%setting_2' => $flush,
        '%setting_3' => $setting_1_processed,
      ]),
    ];

    return $result;
  }

}
