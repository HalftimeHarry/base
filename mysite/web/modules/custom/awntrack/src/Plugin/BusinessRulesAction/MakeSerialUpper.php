<?php

namespace Drupal\awntrack\Plugin\BusinessRulesAction;

use Drupal\business_rules\ActionInterface;
use Drupal\business_rules\Events\BusinessRulesEvent;
use Drupal\business_rules\ItemInterface;
use Drupal\business_rules\Plugin\BusinessRulesActionPlugin;
use Drupal\Core\Form\FormStateInterface;
use Drupal\business_rules\VariableObject;


/**
 * Class MakeSerialUpper.
 *
 * @package Drupal\awntrack\Plugin\BusinessRulesAction
 *
 * @BusinessRulesAction(
 *   id = "upper_action",
 *   label = @Translation("Make SN Upper"),
 *   group = @Translation("Awntrack"),
 *   description = @Translation("This action makes the SN Upper"),
 *   reactsOnIds = {},
 *   isContextDependent = FALSE,
 *   hasTargetEntity = FALSE,
 *   hasTargetBundle = FALSE,
 *   hasTargetField = FALSE,
 * )
 */
class MakeSerialUpper extends BusinessRulesActionPlugin {

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm(array &$form, FormStateInterface $form_state, ItemInterface $item) {
    
    $settings['serial_string'] = [
      '#type'          => 'textarea',
      '#title'         => t('Date to convert'),
      '#description'   => t('Get the SN to make upper'),
      '#required'      => TRUE,
      '#default_value' => $item->getSettings('serial_string'),
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
    $sn_str           = $action->getSettings('serial_string');
    $setting_1_processed = $this->processVariables($sn_str, $variables);
    $variable        = $action->getSettings('variable');
    
    // TODO write your execution code here.
    $str = strtoupper($sn_str);
    $stripped = str_replace(' ', '', $str);
    $variables->replaceValue($variable, $stripped);

    $result = [
      '#type'   => 'markup',
      '#markup' => t(' from %setting_1from %setting_2' ,  [
        '%setting_1' => $sn_str,
        '%setting_2' => $str,
      ]),
    ];

    return $result;
  }

}