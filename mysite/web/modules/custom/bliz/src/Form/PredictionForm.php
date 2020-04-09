<?php

namespace Drupal\bliz\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PredictionForm.
 */
class PredictionForm extends FormBase {

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
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->entityQuery = $container->get('entity.query');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'prediction_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['predict_game'] = [
      '#type' => 'entity_autocomplete',
      '#maxlength' => 255,
      '#title' => $this->t('Predict Game'),
      '#description' => $this->t('Enter the game here to filter the prediction'),
      '#target_type' => 'node',
      '#selection_handler' => 'default', // Optional. The default selection handler is pre-populated to 'default'.
      '#selection_settings' => array(
      'target_bundles' => array('game'),
      ),
    ];
    $form['add_bliz_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add Bliz ID'),
      '#description' => $this->t('Enter a Bliz ID'),
      '#maxlength' => 4,
      '#weight' => '0',
    ];
    $form['away_score'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Away Scored'),
      '#description' => $this->t('Add Score'),
      '#maxlength' => 4,
      '#weight' => '0',
    ];
    $form['home_score'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Home Scored'),
      '#description' => $this->t('Add Score'),
      '#maxlength' => 4,
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
      $p_gm = $form_state->getValue('predict_game'); // Get the ID for the game
      $b_id = $form_state->getValue('add_bliz_id'); // Empty when #tree = FALSE
      $aw_score = $form_state->getValue('away_score'); // Empty when #tree = FALSE
      $hm_score = $form_state->getValue('home_score'); // Empty when #tree = FALSE
      $gm_totals = $aw_score + $hm_score;
      $gm = \Drupal::entityManager()->getStorage('node')->load($p_gm);
      $gm_br_point = $gm->get('field_gm_br_point')->value;
      $gm_compare = $gm->get('field_gm_compare_tm')->value;
      $lmt = $gm->get('field_gm_limit')->value;
      $gm_total = $gm->get('field_gm_total')->value;
      $aw_ref = $gm->get('field_gm_aw_ref')->getValue()[0]['target_id'];
      $hm_ref = $gm->get('field_gm_hm_ref')->getValue()[0]['target_id'];
      $gm_aw = \Drupal::entityManager()->getStorage('node')->load($aw_ref);
      $gm_hm = \Drupal::entityManager()->getStorage('node')->load($hm_ref);
      $aw_title = $gm_aw->get('title')->value;
      $hm_title = $gm_hm->get('title')->value;
      if ($gm_compare === 'home'){
        $tm = $hm_title;
      }
      if ($gm_compare === 'away'){
        $tm = $aw_title;
      }
      $blz_service = \Drupal::service('blizservice');
      $hwp = $blz_service->teamWonPastGame($tm); //add smart filter
      $hlp = $blz_service->teamLostPastGame($tm); //add smart filter
      $all = array_merge($hwp, $hlp);
      rsort($all);
      $output = array_slice($all, $gm_br_point, $lmt);
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($output);
      foreach ($nodes as $node){
        $v = $node->get('nid')->value; // only used to check how it is sorting
        $ws = $node->get('field_pg_ptsw')->value;
        $ls = $node->get('field_pg_ptsl')->value;
        \Drupal::messenger()->addMessage('Winning '.$ws.' Losing '.$ls);
        $total = $ws + $ls;
        $sum += $total;
        }
        $d = $sum / $lmt;
        $margin = $d - $gm_total;
        \Drupal::messenger()->addMessage('The margin is '. $margin);

      if ($gm_totals > 0 && $gm_total < $gm_totals ){
          $node = \Drupal\node\Entity\Node::load($p_gm);
          $node->field_gm_aw_score = $aw_score;
          $node->field_gm_hm_score = $hm_score;
          $node->field_gm_total_result = 'under';
          $node->field_gm_margin = $margin;
          $node->field_gm_status = 'past';
          $node->save();
      }
        if ($gm_totals > 0 && $gm_total > $gm_totals ){
          $node = \Drupal\node\Entity\Node::load($p_gm);
          $node->field_gm_aw_score = $aw_score;
          $node->field_gm_hm_score = $hm_score;
          $node->field_gm_total_result = 'over';
          $node->field_gm_margin = $margin;
          $node->field_gm_status = 'past';
          $node->save();
      }
        if ($gm_totals > 0 && $gm_total === $gm_totals ){
          $node = \Drupal\node\Entity\Node::load($p_gm);
          $node->field_gm_aw_score = $aw_score;
          $node->field_gm_hm_score = $hm_score;
          $node->field_gm_total_result = 'push';
          $node->field_gm_margin = $margin;
          $node->field_gm_status = 'past';
          $node->save();
      }
        if ( $b_id > 1 && $aw_score > $hm_score ){
          $new_pg = array();
          $new_pg['type'] = 'past_game';
          $new_pg['title'] = $b_id;
          $new_pg['field_pg_ptsw'] = $aw_score;
          $new_pg['field_pg_ptsl'] = $hm_score;
          $new_pg['field_pg_away_won'] = '@';
          $new_pg['field_pg_bliz_id'] = $b_id;
          $new_pg['field_pg_winner_tie'] = $aw_title;
          $new_pg['field_pg_loser_tie'] = $hm_title;
          $new_pg = entity_create('node', $new_pg);
          $new_pg->save();
        }
        if ( $b_id > 1 && $aw_score < $hm_score ){
          $new_pg = array();
          $new_pg['type'] = 'past_game';
          $new_pg['title'] = $b_id;
          $new_pg['field_pg_ptsw'] = $hm_score;
          $new_pg['field_pg_ptsl'] = $aw_score;
          $new_pg['field_pg_bliz_id'] = $b_id;
          $new_pg['field_pg_winner_tie'] = $hm_title;
          $new_pg['field_pg_loser_tie'] = $aw_title;
          $new_pg = entity_create('node', $new_pg);
          $new_pg->save();
     }
  }
}

