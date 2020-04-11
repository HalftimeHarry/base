<?php

namespace Drupal\baz\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Class ParForm.
 *
 * @package Drupal\baz\Form
 */
class ParForm extends FormBase {

   /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entity_query;
  
  
  public function __construct(
    QueryFactory $entity_query
  ) {
    $this->entity_query = $entity_query;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'par_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
        $query = $this->entity_query->get('user')
        ->condition('status', 1);
        $uids = $query->execute();
        $users = user_load_multiple(array_keys($uids['user']));
        //here is where you will need to add each participant use id and keys for more weeks
        $p6 = $users[6]->name->value;
        $p16 = $users->field_week_1->value;
                dpm('When you go back to this project start here');

  $header = [
    'par_name' => $this->t('Participant'),'wk_1' => $this->t('Week 1'),'wk_2' => $this->t('Week 2')
  ];

  $rows = [
    1 => ['par_name' => $p6,'par_sel1' => $p16,'par_sel2' => 'not in yet'],
  ];
  
    
  
  //dpm(array_udiff($options, $pk_list0));
  //  dpm($user);


  $form['table'] = array(
    '#type' => 'table',
    '#header' => $header,
    '#options' => $options,
    '#rows' => $rows,
    '#multiple' => TRUE,
    '#empty' => $this->t('No users found'),
  );
        return $form;
    }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
