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
 * Class MasterForm.
 *
 * @package Drupal\lms\Form
 */
class EntryForm extends FormBase {
   /**
   *
   * @var Drupal\Core\Session\AccountInterface
   */
  protected $current_user;

  /**
   * Drupal\lms\TeamListService definition.
   *
   * @var \Drupal\lms\TeamListService
   */
  protected $lms_teamlist;
  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity_type_manager;
  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entity_query;
  public function __construct(AccountInterface $current_user, TeamListService $lms_teamlist, EntityTypeManager $entity_type_manager, QueryFactory $entity_query) {
    $this-> current_user = $current_user;
    $this-> lms_teamlist = $lms_teamlist;
    $this-> entity_type_manager = $entity_type_manager;
    $this-> entity_query = $entity_query;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('lms.teamlist'),
      $container->get('entity_type.manager'),
      $container->get('entity.query')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entry_form';
  }

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
      '#title' => $this->t('Select the "Entry" below.... If you click to proceed with out selecting one of the entries below it will NOT go to the list of teams'),
    ];
              
    $unm = $this->current_user->getDisplayName();
    $query = $this->entity_query->get('node')
        ->condition('title', $unm, 'CONTAINS')
        ->condition('type', 'lms_entry')
        ->condition('sticky','1')
        ->condition('field_type_of_entry', 'lms');
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
    'life_name' => $this->t('Choose Entry'),
        'wk_1' => $this->t('Week 1'),
        'wk_2' => $this->t('Week 2'),
        'wk_3' => $this->t('Week 3'),
        'wk_4' => $this->t('Week 4'),
        'wk_5' => $this->t('Week 5'),
        'wk_6' => $this->t('Week 6'),
        'wk_7' => $this->t('Week 7'),
        'wk_8' => $this->t('Week 8'),
        'wk_9' => $this->t('Week 9'),
        'wk_10' => $this->t('Week 10'),
        'wk_11' => $this->t('Week 11'),
        'wk_12' => $this->t('Week 12'),
        'wk_13' => $this->t('Week 13'),
        'wk_14' => $this->t('Week 14'),
        'wk_15' => $this->t('Week 15'),
        'wk_16' => $this->t('Week 16'),
        'wk_17' => $this->t('Week 17')
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
      '#title' => $this->t('After you have clicked on the radio button above'),
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
        $pk_lt[17] = $pk_17;
        
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
                  //  '1' => $this->t('Week 1'),
                  //  '2' => $this->t('Week 2'),
                  //  '3' => $this->t('Week 3'),
                 //   '4' => $this->t('Week 4'),
                 //   '5' => $this->t('Week 5'),
                 //   '6' => $this->t('Week 6'),
                 //   '7' => $this->t('Week 7'),
                 //   '8' => $this->t('Week 8'),
                 //   '9' => $this->t('Week 9'),
                 //   '10' => $this->t('Week 10'),
                 //   '11' => $this->t('Week 11'),
                 //   '12' => $this->t('Week 12'),
                //    '13' => $this->t('Week 13'),
                //    '14' => $this->t('Week 14'),
                //    '15' => $this->t('Week 15'),
                //    '16' => $this->t('Week 16'),
                    '17' => $this->t('Week 17'),
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

    public function fapiExampleMultistepFormNextSubmit(array &$form, FormStateInterface $form_state) {
    $form_state
      ->set('page_values', [
        // Keep only first step values to minimize stored data.
        'selected_life' => $form_state->getValue('selected_life'),
      ])
      ->set('page_num', 2)
      ->setRebuild(TRUE);
  }
    /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if (strlen ($form_state->getValue('selected_team'))<3){
      $form_state->setErrorByName('selected_team',
      $this-> t('Please select a team'));
    }
}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $sel_lf = $form_state->getValue('hold_life'); //get the node selected by the user
        $team = $form_state->getValue('selected_team'); //get the team selected by the user
        $wk = $form_state->getValue('wk_selected'); //get the week selected by the user
      if ($wk== 1) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[0] = $team;
          $node->field_week_1 = $team;
          $node->save();
      }
      if ($wk== 2) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[1] = $team;
          $node->field_week_2 = $team;
          $node->save();
      }
      if ($wk== 3) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[2] = $team;
          $node->field_week_3 = $team;
          $node->save();
      }
      if ($wk== 4) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[3] = $team;
          $node->field_week_4 = $team;
          $node->save();
      }
      if ($wk== 5) {
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
          $node->field_week_11 = $team;
          $node->save();
      }
      if ($wk== 12) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[11] = $team;
          $node->field_week_12 = $team;
          $node->save();
      }
      if ($wk== 13) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[12] = $team;
          $node->field_week_13 = $team;
          $node->save();
      }
      if ($wk== 14) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[13] = $team;
          $node->field_week_14 = $team;
          $node->save();
      }
      if ($wk== 15) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[14] = $team;
          $node->field_week_15 = $team;
          $node->save();
      }
      if ($wk== 16) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[15] = $team;
          $node->field_week_16 = $team;
          $node->save();
      }
      if ($wk== 17) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[16] = $team;
          $node->field_week_17 = $team;
          $node->save();
      }
      if ($wk== 18) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[17] = $team;
          $node->field_extra_1 = $team;
          $node->save();
      }
      if ($wk== 19) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[18] = $team;
          $node->field_extra_2 = $team;
          $node->save();
      }
      if ($wk== 20) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[19] = $team;
          $node->field_extra_3 = $team;
          $node->save();
      }
      if ($wk== 21) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[20] = $team;
          $node->field_extra_4 = $team;
          $node->save();
      }
      if ($wk== 22) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[21] = $team;
          $node->field_extra_5 = $team;
          $node->save();
      }
      if ($wk== 23) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[22] = $team;
          $node->field_extra_6 = $team;
          $node->save();
      }
      if ($wk== 24) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[23] = $team;
          $node->field_extra_7 = $team;
          $node->save();
      }
      if ($wk== 25) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[24] = $team;
          $node->field_extra_8 = $team;
          $node->save();
      }
      if ($wk== 26) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[25] = $team;
          $node->field_extra_9 = $team;
          $node->save();
      }
      if ($wk== 27) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[26] = $team;
          $node->field_extra_10 = $team;
          $node->save();
      }
      if ($wk== 28) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[27] = $team;
          $node->field_extra_11 = $team;
          $node->save();
      }
      if ($wk== 29) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[28] = $team;
          $node->field_extra_12 = $team;
          $node->save();
      }
      if ($wk== 30) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[29] = $team;
          $node->field_extra_13 = $team;
          $node->save();
      }
      if ($wk== 31) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[30] = $team;
          $node->field_extra_14 = $team;
          $node->save();
      }
      if ($wk== 32) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[31] = $team;
          $node->field_extra_15 = $team;
          $node->save();
      }
      if ($wk== 33) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[32] = $team;
          $node->field_extra_16 = $team;
          $node->save();
      }
      if ($wk== 34) {
          $node = \Drupal\node\Entity\Node::load($sel_lf);
          $node->field_picks[33] = $team;
          $node->field_extra_17 = $team;
          $node->save();
      }
  }
}
