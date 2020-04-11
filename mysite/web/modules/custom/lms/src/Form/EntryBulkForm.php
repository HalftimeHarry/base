<?php

namespace Drupal\lms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\lms\TeamListService;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Class EntryBulkForm.
 */
class EntryBulkForm extends FormBase {

  /**
   * Drupal\lms\TeamListService definition.
   *
   * @var \Drupal\lms\TeamListService
   */
  protected $lms_teamlist;
  
  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entity_query;
  /**
   * Constructs a new EntryBulkForm object.
   */
   
  public function __construct(TeamListService $lms_teamlist, QueryFactory $entity_query) {
    $this->lms_teamlist = $lms_teamlist;
    $this->entity_query = $entity_query;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('lms.teamlist'),
      $container->get('entity.query')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entry_bulk_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    
    $tl = $this->lms_teamlist->getTeams();
    
    $query = $this->entity_query->get('node')
        ->condition('type', 'entry')
        ->condition('sticky','1')
        ->condition('field_type_of_entry', 'lms');
          $nids = $query->execute();
          $nodes = node_load_multiple($nids);
          
    $form['selected_team'] = [
      '#type' => 'radios',
      '#title' => $this->t('Use the button left of the team to select that team'),
      '#attributes' => array('class' => array('inline')),
      '#options' => $tl,
      '#multiple' => FALSE,
      '#empty' => $this->t('No teams found'),
   ];
    $options = [];
    foreach ($nodes as $node) {
          $nmid = $node->nid->value;
          $nm = $node->title->value;
          $nm1 = $node->get('field_week_1')->value;
          $nm2 = $node->get('field_week_2')->value;
          $nm3 = $node->get('field_week_3')->value;
          $nm4 = $node->get('field_week_4')->value;
          $nm5 = $node->get('field_week_5')->value;
          $nm6 = $node->get('field_week_6')->value;
          $nm7 = $node->get('field_week_7')->value;
          $nm8 = $node->get('field_week_8')->value;
          $nm9 = $node->get('field_week_9')->value;
          $nm10 = $node->get('field_week_10')->value;
          $nm11 = $node->get('field_week_11')->value;
          $nm12 = $node->get('field_week_12')->value;
          $nm13 = $node->get('field_week_13')->value;
          $nm14 = $node->get('field_week_14')->value;
          $nm15 = $node->get('field_week_15')->value;
          $nm16 = $node->get('field_week_16')->value;
          $nm17 = $node->get('field_week_17')->value;
          
       $options[$nmid] = ['par_name' => $nm, 
                          'wk_1' => $nm1, 
                          'wk_2' => $nm2,
                          'wk_3' => $nm3, 
                          'wk_4' => $nm4,
                          'wk_5' => $nm5, 
                          'wk_6' => $nm6,
                          'wk_7' => $nm7, 
                          'wk_8' => $nm8,
                          'wk_9' => $nm9, 
                          'wk_10' => $nm10,
                          'wk_11' => $nm11, 
                          'wk_12' => $nm12,
                          'wk_13' => $nm13, 
                          'wk_14' => $nm14,
                          'wk_15' => $nm15,
                          'wk_16' => $nm16, 
                          'wk_17' => $nm17];
       
 }
        
   $header = [
     'par_name' => $this->t('LMS Entry'),
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
     'wk_17' => $this->t('Week 17'),
  ];


    $form['input_data'] = array(
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => $this->t('No users found'),
);
    
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
    // Dislmsy result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
      
        $sel_lf = $form_state->getValue('input_data'); //get the nodes selected by the user
        $team = $form_state->getValue('selected_team'); //get the team selected by the user
        foreach ($sel_lf as $entry){
          $node = \Drupal\node\Entity\Node::load($entry);
         // $node->set("field_picks[3]", $team);
          $node->set("field_week_5", $team);
          $node->save();
      }
    }
  }
}
