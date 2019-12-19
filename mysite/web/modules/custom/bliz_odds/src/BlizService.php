<?php

namespace Drupal\bliz_odds;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class BlizService.
 */
class BlizService implements BlizServiceInterface {

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
   * Constructs a new BlizService object.
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

}
