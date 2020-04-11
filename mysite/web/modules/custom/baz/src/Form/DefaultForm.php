<?php

namespace Drupal\baz\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Class DefaultForm.
 *
 * @package Drupal\baz\Form
 */
class DefaultForm extends FormBase {

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
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
        $query = $this->entity_query->get('node')
        ->condition('status', 1)
        ->condition('type', 'team');
        $nids = $query->execute();
        $nodes = node_load_multiple(array_keys($nids['node']));
        
        $t1 = $nodes[1]->title->value;
        $t2 = $nodes[2]->title->value;
        $t3 = $nodes[3]->title->value;
        $t4 = $nodes[4]->title->value;
        $t5 = $nodes[5]->title->value;
        $t6 = $nodes[6]->title->value;
        $t7 = $nodes[7]->title->value;
        $t8 = $nodes[8]->title->value;
        $t9 = $nodes[9]->title->value;
        $t10 = $nodes[10]->title->value;
        $t11 = $nodes[11]->title->value;
        $t12 = $nodes[12]->title->value;
        $t13 = $nodes[13]->title->value;
        $t14 = $nodes[14]->title->value;
        $t15 = $nodes[15]->title->value;
        $t16 = $nodes[16]->title->value;
        $t17 = $nodes[17]->title->value;
        $t18 = $nodes[18]->title->value;
        $t19 = $nodes[19]->title->value;
        $t20 = $nodes[20]->title->value;
        $t21 = $nodes[21]->title->value;
        $t22 = $nodes[22]->title->value;
        $t23 = $nodes[23]->title->value;
        $t24 = $nodes[24]->title->value;
        $t25 = $nodes[25]->title->value;
        $t26 = $nodes[26]->title->value;
        $t27 = $nodes[27]->title->value;
        $t28 = $nodes[28]->title->value;
        $t29 = $nodes[29]->title->value;
        $t30 = $nodes[30]->title->value;
        $t31 = $nodes[31]->title->value;
        $t32 = $nodes[32]->title->value;
       
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $name = $user->get('name')->value;
    $pk_1 = $user->field_week_1->value;
        $pk_2 = $user->field_week_2->value;
        $pk_3 = $user->field_week_3->value;
        $pk_4 = $user->field_week_4->value;
        $pk_5 = $user->field_week_5->value;
        $pk_6 = $user->field_week_6->value;
        $pk_7 = $user->field_week_7->value;
        $pk_8 = $user->field_week_8->value;
        $pk_9 = $user->field_week_9->value;
        $pk_10 = $user->field_week_10->value;
        $pk_11 = $user->field_week_11->value;
        $pk_12 = $user->field_week_12->value;
        $pk_13 = $user->field_week_13->value;
        $pk_14 = $user->field_week_14->value;
        $pk_15 = $user->field_week_15->value;
        $pk_16 = $user->field_week_16->value;
        
        $pk_list = array();
        $pk_list[1] = $pk_1;
        $pk_list[2] = $pk_2;
        $pk_list[3] = $pk_3;
        $pk_list[4] = $pk_4;
        $pk_list[5] = $pk_5;
        $pk_list[6] = $pk_6;
        $pk_list[7] = $pk_7;
        $pk_list[8] = $pk_8;
        $pk_list[9] = $pk_9;
        $pk_list[10] = $pk_10;
        $pk_list[11] = $pk_11;
        $pk_list[12] = $pk_12;
        $pk_list[13] = $pk_13;
        $pk_list[14] = $pk_14;
        $pk_list[15] = $pk_15;
        $pk_list[16] = $pk_16;
        drupal_set_message($pk_list);
        
       
        $dups = array();
        foreach(array_count_values($pk_list) as $val => $c)
        if($c > 1) $dups[] = $val;
        
        if($dups[0] === null){
          drupal_set_message('You have no duplicate teams picked','status', $repeat = FALSE);
        }
        else {
          drupal_set_message(t('You have the same team picked more than once'), 'error', $repeat = FALSE);
        }
        $config = \Drupal::config('baz.bazconfig');
        $restwk = $config->get('diable_last_week');

         if ($restwk==0){
        $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
         '#options' => [
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
         }
      if ($restwk==1){
          $form['select_week'] = [
            '#type' => 'select',
            '#title' => $this->t('Select week'),
            '#options' => [
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
         }
          if ($restwk==2){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
          if ($restwk==3){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
           if ($restwk==4){
              $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
           if ($restwk==5){
               $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
         if ($restwk==6){
            $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
         if ($restwk==7){
            $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
         if ($restwk==8){
            $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
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
         }
          if ($restwk==9){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '10' => $this->t('Week 10'),
                    '11' => $this->t('Week 11'),
                    '12' => $this->t('Week 12'),
                    '13' => $this->t('Week 13'),
                    '14' => $this->t('Week 14'),
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         if ($restwk==10){
            $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '11' => $this->t('Week 11'),
                    '12' => $this->t('Week 12'),
                    '13' => $this->t('Week 13'),
                    '14' => $this->t('Week 14'),
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         if ($restwk==11){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '12' => $this->t('Week 12'),
                    '13' => $this->t('Week 13'),
                    '14' => $this->t('Week 14'),
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         if ($restwk==12){
            $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '13' => $this->t('Week 13'),
                    '14' => $this->t('Week 14'),
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         if ($restwk==13){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '14' => $this->t('Week 14'),
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         if ($restwk==14){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '15' => $this->t('Week 15'),
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         if ($restwk==15){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [
                    '16' => $this->t('Week 16'),
               ],
  ];
         }
         
         if ($restwk==16){
             $form['select_week'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
              '#options' => [

               ],
  ];
         }
        
  $header = [
    'team_name' => $this->t('Team Name'),
  ];

  $options = [
    1 => ['team_name' => $this->t($t1)],
    2 => ['team_name' => $this->t($t2)],
    3 => ['team_name' => $this->t($t3)],
    4 => ['team_name' => $this->t($t4)],
    5 => ['team_name' => $this->t($t5)],
    6 => ['team_name' => $this->t($t6)],
    7 => ['team_name' => $this->t($t7)],
    8 => ['team_name' => $this->t($t8)],
    9 => ['team_name' => $this->t($t9)],
    10 => ['team_name' => $this->t($t10)],
    11 => ['team_name' => $this->t($t11)],
    12 => ['team_name' => $this->t($t12)],
    13 => ['team_name' => $this->t($t13)],
    14 => ['team_name' => $this->t($t14)],
    15 => ['team_name' => $this->t($t15)],
    16 => ['team_name' => $this->t($t16)],
    17 => ['team_name' => $this->t($t17)],
    18 => ['team_name' => $this->t($t18)],
    19 => ['team_name' => $this->t($t19)],
    20 => ['team_name' => $this->t($t20)],
    21 => ['team_name' => $this->t($t21)],
    22 => ['team_name' => $this->t($t22)],
    23 => ['team_name' => $this->t($t23)],
    24 => ['team_name' => $this->t($t24)],
    25 => ['team_name' => $this->t($t25)],
    26 => ['team_name' => $this->t($t26)],
    27 => ['team_name' => $this->t($t27)],
    28 => ['team_name' => $this->t($t28)],
    29 => ['team_name' => $this->t($t29)],
    30 => ['team_name' => $this->t($t30)],
    31 => ['team_name' => $this->t($t31)],
    32 => ['team_name' => $this->t($t32)],
  ];
  
  //dpm(array_udiff($options, $pk_list0));
  //  dpm($user);


  $form['table'] = array(
    '#type' => 'tableselect',
    '#header' => $header,
    '#options' => $options,
    '#multiple' => FALSE,
    '#empty' => $this->t('No users found'),
  );

     $form['actions'] = array('#type' => 'actions');
     $form['actions']['submit'] = array(
           '#type' => 'submit',
           '#value' => t('Submit Pick'),
  );
        return $form;
    }

  public function validateForm(array &$form, FormStateInterface $form_state) {
      if($dups[0] === null){
         
        }
        else {
          $this->setFormError('team_name', $this->t('You may not pick the same team twice'));
        }
}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
        $week = $form_state->getValue('select_week');
        $entry = $form_state->getValue('table');
        $more = \Drupal\node\Entity\Node::load($entry);
        $tm_gt = $more->title->value;
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        
        drupal_set_message(t('You have submitted the '.$tm_gt.' for week '.$week), 'status', $repeat = FALSE);
        
        $user->field_acct_selection->entity = $entry;
        $user->save();
        
        if ($week==1){
        $user->field_week_1->value = $tm_gt;
        $user->save();
        }
        if ($week==2){
        $user->field_week_2->value = $tm_gt;
        $user->save();
        }
        if ($week==3){
        $user->field_week_3->value = $tm_gt;
        $user->save();
        }
        if ($week==4){
        $user->field_week_4->value = $tm_gt;
        $user->save();
        }
        if ($week==5){
        $user->field_week_5->value = $tm_gt;
        $user->save();
        }
        if ($week==6){
        $user->field_week_6->value = $tm_gt;
        $user->save();
        }
        if ($week==7){
        $user->field_week_7->value = $tm_gt;
        $user->save();
        }
        if ($week==8){
        $user->field_week_8->value = $tm_gt;
        $user->save();
        }
        if ($week==9){
        $user->field_week_9->value = $tm_gt;
        $user->save();
        }
        if ($week==10){
        $user->field_week_10->value = $tm_gt;
        $user->save();
        }
        if ($week==11){
        $user->field_week_11->value = $tm_gt;
        $user->save();
        }
        if ($week==12){
        $user->field_week_12->value = $tm_gt;
        $user->save();
        }
        if ($week==13){
        $user->field_week_13->value = $tm_gt;
        $user->save();
        }
        if ($week==14){
        $user->field_week_14->value = $tm_gt;
        $user->save();
        }
        if ($week==15){
        $user->field_week_15->value = $tm_gt;
        $user->save();
        }
        if ($week==16){
        $user->field_week_16->value = $tm_gt;
        $user->save();
        }
        
 }
}