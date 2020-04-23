<?php

namespace Drupal\lms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\lms\PoolService;

/**
 * Class EnterInMyEntryForm.
 */
class EnterInMyEntryForm extends FormBase {

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $lmsService;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Symfony\Component\DependencyInjection\ContainerAwareInterface definition.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerAwareInterface
   */
  protected $entityQuery;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->currentUser = $container->get('current_user');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->entityQuery = $container->get('entity.query');
    $instance->lmsService = $container->get('lms.pool');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'enter_in_my_entry_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    //$member stores the target_id of the particular user
    $entity =  \Drupal\user\Entity\User::loadMultiple(); // Load all user entities
      foreach ($entity as $key => $user) {
        if($user->id() === $member){
       $member = $user; //the user entity which I want is now stored in member
       break;
     }
  }

    $form['my_user'] = array(
      '#title' => $this->t('My user be sure the (ID) matches when you submit'),
      '#type' => 'entity_autocomplete',
      '#description' => $this->t('From the list on the left type in the user name this will autocomplete to link it up'),
      '#required' => TRUE,
      '#target_type' => 'user',
      '#selection_settings' => [
        'include_anonymous' => FALSE,
      ],
    );

    $form['max'] = [
      '#type' => 'number',
      '#title' => $this->t('How many entries for this user?'),
      '#description' => $this->t('Enter in the number of accounts by default set to 1'),
      '#default_value' => '1',
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
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $my_user = $form_state->getValue('my_user'); // Get the data from the form
      $import_max = $form_state->getValue('max'); // Get the data from the form
      $get_user_name = \Drupal::entityManager()->getStorage('user')->load($my_user);
      $user_name = $get_user_name->get('name')->value;
      $lms_service = $this->lmsService;
      $user = $this->entityTypeManager->getStorage('user')->load($this->currentUser()->id());
    for ($i = 1; $i <= $import_max; $i++) {
      $node_fields = ['title' => $user_name.' '.$i];
      dump($node_fields);
        $lms_service->createNode($node_fields['title'], $type = 'entry', $uid = $user->id(), $pool_id = $user->id());


        $imported++;
    }
  }
}
