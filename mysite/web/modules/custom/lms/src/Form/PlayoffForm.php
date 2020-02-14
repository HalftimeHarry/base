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
 * Class PlayoffForm.
 *
 * @package Drupal\lms\Form
 */
class PlayoffForm extends LoserForm {
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
      '#title' => $this->t('Select the "Playoff Entry" then select next to submit your team for the LMS List'),
    ];
              
    $unm = $this->current_user->getDisplayName();
    $query = $this->entity_query->get('node')
        ->condition('title', $unm, 'CONTAINS')
        ->condition('type', 'entry')
        ->condition('sticky','1')
        ->condition('field_type_of_entry', 'playoff');
        $nids = $query->execute();
        $nodes = node_load_multiple($nids);
        
     $op = [];
        foreach ($nodes as $life) {
          $nid = $life->nid->value;
          $nm = $life->title->value;
          $lf_sym = $life->field_life_symbol->value;
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
       $op[$nid] = ['life_name' => $nm,
                    'wk_1' => $pk_1,
                    'wk_2' => $pk_2,
                    'wk_3' => $pk_3,
                    'wk_4' => $pk_4,
                    'wk_5' => $pk_5,
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
                    'wk_17' => $pk_17];
 }
   $header = [
        'life_name' => $this->t('Choose Playoff Entry'),
        'wk_1' => $this->t('Wildcard 1'),
        'wk_2' => $this->t('Wildcard 2'),
        'wk_3' => $this->t('Wildcard 3'),
        'wk_4' => $this->t('Wildcard Lock'),
        'wk_5' => $this->t('Divisinal 1'),
        'wk_6' => $this->t('Divisinal 2'),
        'wk_7' => $this->t('Divisinal 3'),
        'wk_8' => $this->t('Divisinal Lock'),
        'wk_9' => $this->t('Confrance 1'),
        'wk_10' => $this->t('Confrance Lock'),
        'wk_11' => $this->t('Superbowl')
  ];
 
    $form['selected_life'] = [
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $op,
        '#multiple' => FALSE,
        '#required' => TRUE,
        '#empty' => $this->t('No users found'),
  ];
  
    $form['actions'] = [
      '#type' => 'actions',
  ];

    $form['actions']['next'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Next'),
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
        $pk_lt[17] = $pk_16;
        
    $result = array_diff($teamlist, $pk_lt);
   
    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('If your team does not appear that means you already used them. If you want to change your selection choose
      another team and lmsce that team in the proper week.'),
    ];
    
      $form['wk_selected'] = [
              '#type' => 'select',
              '#title' => $this->t('Select week'),
         '#options' => [
                    '1' => $this->t('Wildcard 1'),
                    '2' => $this->t('Wildcard 2'),
                    '3' => $this->t('Wildcard 3'),
                    '4' => $this->t('Wildcard Lock'),
                    '5' => $this->t('Divisional 1'),
                    '6' => $this->t('Divisional 2'),
                    '7' => $this->t('Divisional 3'),
                    '8' => $this->t('Divisional Lock'),
                    '9' => $this->t('Confrance 1'),
                    '10' => $this->t('Confrance Lock'),
                    '11' => $this->t('Superbowl')
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

}