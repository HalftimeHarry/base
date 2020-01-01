<?php

namespace Drupal\awntrack\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\awntrack\AwnService;
use Drupal\Core\Session\AccountInterface;


/**
 * Class UpdateForm.
 */
class UpdateForm extends FormBase {

  /**
   * Drupal\awntrack\AwnService definition.
   *
   * @var \Drupal\awntrack\AwnService
   */
  protected $awnService;
  /**
   * @var AccountInterface $account
   */
  protected $account;
  /**
   * Constructs a new UpdateForm object.
   */
  public function __construct(
    AwnService $awn_service,
    AccountInterface $account
  ) {
    $this->awnService = $awn_service;
    $this->account = $account;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('awn.service'),
      $container->get('current_user')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'update_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['scan'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Scan'),
      '#description' => $this->t('Scan UCID'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
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
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
  // Get current user data.
       $user_nm = $this->account->getAccountName();
  // Match logged in user name to a location
       $id_of_location = $this->awnService->matchUserNameToLocationName($user_nm);
  // Get the scan from the gun
       $scan_fm_gun = $form_state->getValue('scan');
       $serial_from_gun = stripslashes($scan_fm_gun);
       $is_5 = $serial_from_gun[0];
  // Match the scan to a ucid
       $id_of_equipment = $this->awnService->matchScanToUcid($serial_from_gun);
  //Get the id only
       $equ_id = end($id_of_equipment);
       $loc_id = end($id_of_location);
   drupal_set_message($scan_fm_gun . ':  UPDATED!');
    if ($is_5 == 5){
      $this->awnService->updateNodeEquipmentToNewLocation($equ_id, $loc_id);
    }
    else {
      drupal_set_message(t('An error occurred and processing did not complete. 
      Scan the RED UCID'), 'error');
    }

    }
    
  }

}
