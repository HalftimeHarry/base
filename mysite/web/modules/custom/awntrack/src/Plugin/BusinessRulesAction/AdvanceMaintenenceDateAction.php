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
 * Class AdvanceMaintenenceDateAction.
 *
 * @package Drupal\awntrack\Plugin\BusinessRulesAction
 *
 * @BusinessRulesAction(
 *   id = "adv_action",
 *   label = @Translation("Set Deration"),
 *   group = @Translation("Awntrack"),
 *   description = @Translation("Using the exp date this adds 6 months"),
 *   reactsOnIds = {},
 *   isContextDependent = FALSE,
 *   hasTargetEntity = FALSE,
 *   hasTargetBundle = FALSE,
 *   hasTargetField = FALSE,
 * )
 */
class AdvanceMaintenenceDateAction extends BusinessRulesActionPlugin {

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm(array &$form, FormStateInterface $form_state, ItemInterface $item) {
      // TODO currently this is not used add it if you need to adjust the durations 
    $settings['set_adv'] = [
      '#type'          => 'select',
      '#title'         => t('Set the amount of time from exp'),
      '#required'      => TRUE,
      '#default_value' => $item->getSettings('set_adv'),
      '#options'       => [
        '+6 Months'  => t('+6 Months'),
        '+3years' => t('+3years'),
      ],
    ];
    
    $settings['exp_date'] = [
      '#type'          => 'textarea',
      '#title'         => t('Date to convert'),
      '#description'   => t('You will need to convert the date example 6 for 6 months'),
      '#required'      => TRUE,
      '#default_value' => $item->getSettings('exp_date'),
    ];
    
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
    $set_adv           = $action->getSettings('set_adv');
    $exp_date_to_adv           = $action->getSettings('exp_date');
    $setting_1_processed = $this->processVariables($exp_date_to_adv, $variables);
    $variable        = $action->getSettings('variable');
    
    // TODO write your execution code here.
    $date = new DrupalDateTime($exp_date_to_adv, 'UTC');
    $date->modify($set_adv);
    $new_date = $date->format('Y-m-d');
    $variables->replaceValue($variable, $new_date);
    
    $result = [
      '#type'   => 'markup',
      '#markup' => t(' from %setting_2 which is %setting_3',  [
      //  '%setting_1' => $set_adv,
        '%setting_2' => $exp_date_to_adv,
        '%setting_3' => $new_date,
      ]),
    ];

    return $result;
  }

}