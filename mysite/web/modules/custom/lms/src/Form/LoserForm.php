<?php

namespace Drupal\lms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\lms\TeamListService;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Session\AccountInterface;

/**
 * Class LoserForm.
 *
 * @package Drupal\lms\Form
 */
class LoserForm extends EntryForm {
      /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
        if ($form_state->has('page_num') && $form_state->get('page_num') == 2) {
      return self::fapiExamplePageTwoBack($form, $form_state);
    }
    
    $form_state->set('page_num', 1);
    
      $form['descptn_1'] = [
      '#type' => 'item',
      '#title' => $this->t('"Second Half Pool" selection form'),
    ];
              
    $unm = $this->current_user->getDisplayName();
    $query = $this->entity_query->get('node')
        ->condition('title', $unm, 'CONTAINS')
        ->condition('type', 'entry')
        ->condition('sticky','1')
        ->condition('field_type_of_entry', 'second_half');
        $nids = $query->execute();
        $nodes = node_load_multiple($nids);
        
   $op = [];
        foreach ($nodes as $life) {
          $nid = $life->nid->value;
          $nm = $life->title->value;
          $pk_1 = $life->field_picks->value;
          $pk_2 = $life->field_picks[1]->value;
          $pk_3 = $life->field_picks[2]->value;
          $pk_4 = $life->field_picks[3]->value;
          $pk_5 = $life->field_picks[4]->value;
          $pk_6 = $life->field_picks[5]->value;
          $pk_7 = $life->field_picks[6]->value;
          $pk_8 = $life->field_picks[7]->value;
          $pk_9 = $life->field_picks[8]->value;
          $pk_10 = $life->field_picks[9]->value;
          $pk_11 = $life->field_picks[10]->value;
          $pk_12 = $life->field_picks[11]->value;
          $pk_13 = $life->field_picks[12]->value;
          $pk_14 = $life->field_picks[13]->value;
          $pk_15 = $life->field_picks[14]->value;
          $pk_16 = $life->field_picks[15]->value;
          $pk_17 = $life->field_picks[16]->value;
          $pk_18 = $life->field_picks[17]->value;
          $pk_19 = $life->field_picks[18]->value;
          $pk_20 = $life->field_picks[19]->value;
          $pk_21 = $life->field_picks[20]->value;
          $pk_22 = $life->field_picks[21]->value;
          $pk_23 = $life->field_picks[22]->value;
          $pk_24 = $life->field_picks[23]->value;
          $pk_25 = $life->field_picks[24]->value;


       $op[$nid] = ['life_name' => $nm,
                    'wk_6' => $pk_6,
                    'wk_7' => $pk_7,
                    'wk_8' => $pk_8,
                    'wk_9' => $pk_9,
                    'wk_10' => $pk_10,
                    'wk_11' => $pk_11,
                    'wk_12' => $pk_12,
                    'wk_13' => $pk_13,
                    'wk_14' => $pk_14,
                    'wk_15' => $pk_15,
                    'wk_16' => $pk_16,
                    'wk_17' => $pk_17,
                    'wk_18' => $pk_18,
                    'wk_19' => $pk_19,
                    'wk_20' => $pk_20,
                    'wk_21' => $pk_21,
                    'wk_22' => $pk_22,
                    'wk_23' => $pk_23,
                    'wk_24' => $pk_24,
                    'wk_25' => $pk_25];
 }

 $header = [
    'life_name' => $this->t('Choose Entry'),
     //   'wk_1' => $this->t('Week 1'),
     //   'wk_2' => $this->t('Week 2'),
     //   'wk_3' => $this->t('Week 3'),
     //   'wk_4' => $this->t('Week 4'),
     //   'wk_5' => $this->t('Pk 1 Wk 5'),
     //   'wk_52' => $this->t('Pk 2 Wk 5'),
     //   'wk_6' => $this->t('Pk 1 Wk 6'),
     //   'wk_62' => $this->t('Pk 2 Wk 6'),
        'wk_7' => $this->t('Pk 1 Wk 7'),
    //    'wk_72' => $this->t('Pk 2 Wk 7'),
        'wk_8' => $this->t('Pk 1 Wk 8'),
    //    'wk_82' => $this->t('Pk 2 Wk 8'),
        'wk_9' => $this->t('Pk 1 Wk 9'),
    //    'wk_92' => $this->t('Pk 2 Wk 9'),
        'wk_10' => $this->t('Pk 1 Wk 10'),
        'wk_102' => $this->t('Pk 2 Wk 10'),
        'wk_11' => $this->t('Pk 1 Wk 11'),
        'wk_112' => $this->t('Pk 2 Wk 11'),
        'wk_12' => $this->t('Pk 1 Wk 12'),
        'wk_122' => $this->t('Pk 2 Wk 12'),
        'wk_13' => $this->t('Pk 1 Wk 13'),
        'wk_132' => $this->t('Pk 2 Wk 13'),
        'wk_14' => $this->t('Pk 1 Wk 14'),
        'wk_142' => $this->t('Pk 2 Wk 14'),
        'wk_15' => $this->t('Pk 1 Wk 15'),
        'wk_152' => $this->t('Pk 2 Wk 15'),
        'wk_16' => $this->t('Pk 1 Wk 16'),
        'wk_162' => $this->t('Pk 2 Wk 16'),
        'wk_17' => $this->t('Pk 1 Wk 17'),
        'wk_172' => $this->t('Pk 2 Wk 17'),
        'wk_18' => $this->t(''),
        'wk_182' => $this->t(''),
        'wk_19' => $this->t(''),
        'wk_192' => $this->t(''),
        'wk_20' => $this->t(''),
        'wk_202' => $this->t(''),
        'wk_21' => $this->t(''),
        'wk_212' => $this->t(''),
        'wk_22' => $this->t(''),
        'wk_222' => $this->t(''),
        'wk_23' => $this->t(''),
        'wk_232' => $this->t(''),
        'wk_24' => $this->t(''),
        'wk_242' => $this->t(''),
        'wk_25' => $this->t(''),
        'wk_252' => $this->t('')
  ];
    
     $form['selected_life'] = [
    '#type' => 'tableselect',
    '#header' => $header,
    '#options' => $op,
    '#multiple' => FALSE,
    '#required' => TRUE,
    '#empty' => $this->t('No entries found'),
   ];
  
    $form['actions'] = [
      '#type' => 'actions',
    ];
    
     $form['descptn_2'] = [
      '#type' => 'item',
      '#title' => $this->t('Select the entry above and submit your first pick then
                            select the same entry to submit the 2nd pick'),
    ];

    $form['actions']['next'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Click here to proceed'),
      // Custom submission handler for page 1.
      '#submit' => ['::fapiExampleMultistepFormNextSubmit'],
      // Custom validation handler for page 1.
      '#validate' => ['::fapiExampleMultistepFormNextValidate'],
    ];

    return $form;
  }

    
  public function fapiExamplePageTwoBack(array &$form, FormStateInterface $form_state) {
    
    $teamlist = $this->lms_teamlist->getTeams();
    $sel_life = $form_state->getValue('selected_life');
     $more = \Drupal\node\Entity\Node::load($sel_life);
     $pk_id = $more->nid->value;
     $pk_list = $more->field_picks[0]->value;
     $pk_2 = $more->field_picks[1]->value;
     $pk_3 = $more->field_picks[2]->value;
     $pk_4 = $more->field_picks[3]->value;
     $pk_5 = $more->field_picks[4]->value;
     $pk_6 = $more->field_picks[5]->value;
     $pk_7 = $more->field_picks[6]->value;
     $pk_8 = $more->field_picks[7]->value;
     $pk_9 = $more->field_picks[8]->value;
     $pk_10 = $more->field_picks[9]->value;
     $pk_11 = $more->field_picks[10]->value;
     $pk_12 = $more->field_picks[11]->value;
     $pk_13 = $more->field_picks[12]->value;
     $pk_14 = $more->field_picks[13]->value;
     $pk_15 = $more->field_picks[14]->value;
     $pk_16 = $more->field_picks[15]->value;
     $pk_17 = $more->field_picks[16]->value;
     $pk_18 = $more->field_picks[17]->value;
     $pk_19 = $more->field_picks[18]->value; //also updated here
     $pk_20 = $more->field_picks[19]->value;
     $pk_21 = $more->field_picks[20]->value;
     $pk_22 = $more->field_picks[21]->value;
     $pk_23 = $more->field_picks[22]->value;
     $pk_24 = $more->field_picks[23]->value;
     $pk_25 = $more->field_picks[24]->value;
     
     $pk_lt = array();
        $pk_lt[0] = $pk_list;
        $pk_lt[1] = $pk_2;
        $pk_lt[2] = $pk_3;
        $pk_lt[3] = $pk_4;
        $pk_lt[4] = $pk_5;
        $pk_lt[5] = $pk_6;
        $pk_lt[6] = $pk_7;
        $pk_lt[7] = $pk_8;
        $pk_lt[8] = $pk_9;
        $pk_lt[9] = $pk_10;
        $pk_lt[10] = $pk_11;
        $pk_lt[11] = $pk_12;
        $pk_lt[12] = $pk_13;
        $pk_lt[13] = $pk_14;
        $pk_lt[14] = $pk_15;
        $pk_lt[15] = $pk_16;
        $pk_lt[16] = $pk_17;
        $pk_lt[17] = $pk_18; //changed here
        $pk_lt[18] = $pk_19;
        $pk_lt[19] = $pk_20;
        $pk_lt[20] = $pk_21;
        $pk_lt[21] = $pk_22;
        $pk_lt[22] = $pk_23;
        $pk_lt[23] = $pk_24;
        $pk_lt[24] = $pk_25;
        $pk_lt[25] = $pk_26;
   

    $result = array_diff($teamlist, $pk_lt);
   
    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('If your team does not appear that means you already used them. If you want to change your selection choose
      another team and target the week that will replace the team you want to change and make them available for the future.'),
    ];
    
      $form['wk_selected'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
         '#options' => [
            //        '1' => $this->t('Week 1'),
            //        '2' => $this->t('Week 2'),
            //        '3' => $this->t('Week 3'),
            //        '4' => $this->t('Week 4'),
            //        '5' => $this->t('Week 5'),
            //        '6' => $this->t('Week 6'),
            //        '7' => $this->t('Week 7'),
            //        '8' => $this->t('Week 8'),
            //        '9' => $this->t('Week 9'),
            //        '10' => $this->t('Week 10'),
            //        '11' => $this->t('Week 10 Pk 2'),
            //        '12' => $this->t('Week 11'),
            //        '13' => $this->t('Week 11 Pk 2'),
            //        '14' => $this->t('Week 12'),
            //        '15' => $this->t('Week 12 Pk 2'),
           //         '16' => $this->t('Week 13'),
           //         '17' => $this->t('Week 13 Pk 2'),
                    '18' => $this->t('Week 14'),
                    '19' => $this->t('Week 14 Pk 2'),
                    '20' => $this->t('Week 15'),
                    '21' => $this->t('Week 15 Pk 2'),
                    '22' => $this->t('Week 16'),
                    '23' => $this->t('Week 16 Pk 2'),
                    '24' => $this->t('Week 17'),
                    '25' => $this->t('Week 17 Pk 2'),
               ],
  ];
    
    $form['selected_team'] = [
      '#type' => 'radios',
      '#title' => $this->t('Use the button left of the team to select that team'),
      '#attributes' => array('class' => array('container-inline')),
      '#header' => $header,
      '#options' => $result,
      '#multiple' => FALSE,
      '#empty' => $this->t('No teams found'),
      '#attached' => array(
        'library' => array(
          'lms/team-selection',
        ),
      ),
    ];
    
    $form['hold_life'] = [
      '#type' => 'value',
      '#value' => $pk_id,
    ];
    
    $form['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      // Custom submission handler for 'Back' button.
      '#submit' => ['::fapiExamplePageTwoBack'],
      // We won't bother validating the required 'color' field, since they
      // have to come back to this page to submit anyway.
      '#limit_validation_errors' => [],
    ];
    
    $form['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }
  
   /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
       $sel_lf = $form_state->getValue('hold_life'); //get the node selected by the user
        $team = $form_state->getValue('selected_team'); //get the team selected by the user
        $wk = $form_state->getValue('wk_selected'); //get the week selected by the user
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[0] = $team;
          $node->field_week_1 = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[1] = $team;
          $node->field_week_2 = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[2] = $team;
          $node->field_week_3 = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[3] = $team;
          $node->field_week_4 = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[4] = $team;
          $node->field_week_5 = $team;
          $node->save();
      }
      if ($wk== 6) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[5] = $team;
          $node->field_week_6 = $team;
          $node->save();
      }
      if ($wk== 7) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[6] = $team;
          $node->field_week_7 = $team;
          $node->save();
      }
      if ($wk== 8) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[7] = $team;
          $node->field_week_8 = $team;
          $node->save();
      }
      if ($wk== 9) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[8] = $team;
          $node->field_week_9 = $team;
          $node->save();
      }
      if ($wk== 10) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[9] = $team;
          $node->field_week_10 = $team;
          $node->save();
      }
      if ($wk== 11) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[10] = $team;
          $node->field_week_10b = $team;
          $node->save();
      }
      if ($wk== 12) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[11] = $team;
          $node->field_week_11 = $team;
          $node->save();
      }
      if ($wk== 13) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[12] = $team;
          $node->field_week_11b = $team;
          $node->save();
      }
      if ($wk== 14) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[13] = $team;
          $node->field_week_12 = $team;
          $node->save();
      }
      if ($wk== 15) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[14] = $team;
          $node->field_week_12b = $team;
          $node->save();
      }
      if ($wk== 16) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[15] = $team;
          $node->field_week_13 = $team;
          $node->save();
      }
      if ($wk== 17) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[16] = $team;
          $node->field_week_13b = $team;
          $node->save();
      }
      if ($wk== 18) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[17] = $team;
          $node->field_week_14 = $team;
          $node->save();
      }
      if ($wk== 19) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[18] = $team;
          $node->field_week_14b = $team;
          $node->save();
      }
      if ($wk== 20) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[19] = $team;
          $node->field_week_15 = $team;
          $node->save();
      }
      if ($wk== 21) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[20] = $team;
          $node->field_week_15b = $team;
          $node->save();
      }
      if ($wk== 22) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[21] = $team;
          $node->field_week_16 = $team;
          $node->save();
      }
      if ($wk== 23) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[22] = $team;
          $node->field_week_16b = $team;
          $node->save();
      }
      if ($wk== 24) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[23] = $team;
          $node->field_week_17 = $team;
          $node->save();
      }
      if ($wk== 25) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[24] = $team;
          $node->field_week_17b = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[5] = $team;
          $node->field_week_5b = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[7] = $team;
          $node->field_week_6b = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[9] = $team;
          $node->field_week_7b = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[11] = $team;
          $node->field_week_8b = $team;
          $node->save();
      }
      if ($wk== add_later) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[13] = $team;
          $node->field_week_9b = $team;
          $node->save();
      }
  }
}