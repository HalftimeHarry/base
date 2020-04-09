<?php

namespace Drupal\bliz;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class BlizSrvice.
 */
class BlizSrvice implements BlizServiceInterface {

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
   * Constructs a new BlizSrvice object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ContainerAwareInterface $entity_query) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityQuery = $entity_query;
  }

  /**
   * Constructs a new query
   */
  public function teamWonPastGame($tm){
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('status', 1)
      ->condition('type', 'past_game')
      ->condition('field_pg_winner_tie', $tm)
      ->sort('field_pg_bliz_id','DESC')
      ->execute();
    return $query_result;
  }

  /**
   * Constructs a query
   */
  public function teamLostPastGame($tm){
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('status', 1)
      ->condition('type', 'past_game')
      ->condition('field_pg_loser_tie', $tm)
      ->sort('field_pg_bliz_id','DESC')
      ->execute();
    return $query_result;
  }

  /**
   * {@inheritdoc}
   */
  public function getPastGames() {
      $hwp = $pg_service->teamWonPastGame($tm); //add smart filter
      $hlp = $pg_service->teamLostPastGame($tm); //add smart filter
      $all = array_merge($hwp, $hlp);
      rsort($all);
      $output = array_slice($all, $sp_mes, $lmt);
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($output);
      foreach ($nodes as $node){
        $v = $node->get('nid')->value; // only used to check how it is sorting
        $ws = $node->get('field_pg_ptsw')->value;
        $ls = $node->get('field_pg_ptsl')->value;
        dpm('Winning '.$ws.' Losing '.$ls);
        $total = $ws + $ls;
        $sum+=$total;
    }
        $d = $sum / $lmt;
        dpm($d);
        $a = $d - $vgs_tot;
        return $a;
  }


}
