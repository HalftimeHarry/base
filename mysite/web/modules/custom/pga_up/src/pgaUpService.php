<?php

namespace Drupal\pga_up;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class pgaUpService.
 */
class pgaUpService {

  /**
   * Symfony\Component\DependencyInjection\ContainerAwareInterface definition.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerAwareInterface
   */
  protected $entityQuery;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new pgaUpService object.
   */
  public function __construct(ContainerAwareInterface $entity_query, EntityTypeManagerInterface $entity_type_manager) {
    $this->entityQuery = $entity_query;
    $this->entityTypeManager = $entity_type_manager;
  }
 /**
   * Check if file is unique among nodes.
   *
   * @param string $field
   * @param string $value
   * @return bool
   */
  public function uniqueField($field = '', $value = '') {
    $result = $this->entity_query->get('node')
      ->condition($field, $value, '=')
      ->execute();
    $unique = empty($result);
    return $unique;
  }
  /**
   * Match golfer_id and update score
   *
   * @param string $field
   * @param string $value
   * @return bool
   */
  public function matchNodesToGolfer($pid) {
    $result = $this->entity_query->get('node')
      ->condition('type','golfer')
      ->condition('field_player_id', $pid , '=')
      ->execute();
    return $result;
  }
  /**
   * Create node and populate fields.
   *
   * @param string $title
   * @param array $fields
   * @param string $type
   * @param int $uid
   */
  public function createNode($title = '', $fields = [], $type = 'golfer', $uid = 1) {
    $node = $this->entity_type_manager->getStorage('node')->create([
      'type'  => $type,
      'title' => $title,
      'uid'   => $uid,
    ]);
    foreach ($fields as $field => $value) {
      $node->set($field, $value);
    }
    $node->save();
  }
  /**
   * Create taxonomy and populate fields.
   *
   * @param string $name
   * @param array $fields
   * @param string $type
   * @param int $vid
   */
  public function createTerm($name = '', $fields = [], $type = 'fedex_st_jude_classic_2018') {
    $term = $this->entity_type_manager->getStorage('taxonomy_term')->create([
      'name' => $name,
      'vid'   => $type,
    ]);
    foreach ($fields as $field => $value) {
      $term->set($field, $value);
    }
    $term->save();
  }

/**
   * Update node and populate fields.
   *
   * @param string $test is the id
   * @param string $new_score is the new score
   */

  public function updateNode($update, $new_score) {
    $entity = $this->entity_type_manager->getStorage('node')->load($update);
    $entity->set('field_golfer_score', $new_score);
    $entity->save();
  }

  public function listOfGolfers($taxid){
      $query = $this->entity_query->get('node')
        ->condition('type', 'golfer');
 //       ->condition('field_gol_tour_ref', $taxid, "=");
        $nids = $query->execute();
        $nodes = node_load_multiple($nids);
        return $nodes;
  }

  public function listOfEntries($entid){
      $query = $this->entity_query->get('node')
        ->condition('type', 'entry');
 //       ->condition('field_entry_tour_ref', $entid, "=");
        $nids = $query->execute();
        $nodes = node_load_multiple($nids);
        return $nodes;
  }

  public function calcEntryScores($g1_score, $g2_score, $g3_score, $g4_score, $g5_score,
  $p1_score, $p2_score, $p3_score){
        $numbers = array($g1_score, $g2_score, $g3_score, $g4_score, $g5_score,
        $p1_score, $p2_score, $p3_score);
        sort($numbers);
        $best_1 = $numbers[0]+72;
        $best_2 = $numbers[1]+72;
        $best_3 = $numbers[2]+72;
        $best_4 = $numbers[3]+72;
        $best_5 = $numbers[4]+72;
        $best_6 = $numbers[5]+72;//dont use
        $best_7 = $numbers[6]+72;//dont use
        $best_8 = $numbers[7]+72;//dont use

        $best_total = $best_1 + $best_2 + $best_3 + $best_4 + $best_5;
        $best_result = $best_total -360;

        return $best_result;

  }
  /**
   * Create node and populate fields.
   *
   * @param string $title
   * @param array $fields
   * @param string $type
   * @param int $uid
   */
  public function createNodes($title = '', $fields, $type = 'golfer', $uid = 1) {
    $node = $this->entity_type_manager->getStorage('node')->create([
      'type'  => $type,
      'title' => $title,
      'uid'   => $uid,
      'field_player_id' => $fields,
    ]);
    $node->save();
  }
}
