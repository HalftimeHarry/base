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

  /**
   * Constructs a query
   */
  public function checkPassing($tm, $tm_nm){
    if ($tm < '5'){
      dpm('The '. $tm_nm.' has a great passing offense');
    }
    if ($tm >= '5' && $tm < '15'){
      dpm('The '.$tm_nm.' has a okay passing offense');
    }
    if ($tm >= '15' && $tm < '25'){
      dpm('The '. $tm_nm.' has a not so good passing offense');
    }
    if ($tm >= '25') {
      dpm('The '. $tm_nm.'have a terible passing offense');
    }
  }

  /**
   * Constructs a query
   */
  public function checkDefense($tm, $tm_nm){
    if ($tm < '5'){
      dpm('The '. $tm_nm.' has a great scoring defense');
    }
    if ($tm >= '5' && $tm < '15'){
      dpm('The '.$tm_nm.' has a okay scoring defense');
    }
    if ($tm >= '15' && $tm < '25'){
      dpm('The '. $tm_nm.' has a not so good scoring defense');
    }
    if ($tm >= '25') {
      dpm('The '. $tm_nm.'have a terible scoring defense');
    }
  }

  /**
   * Constructs a query
   */
  public function checkRushing($tm, $tm_nm){
    if ($tm < '5'){
      dpm('The '. $tm_nm.' has a great rushing offense');
    }
    if ($tm >= '5' && $tm < '15'){
      dpm('The '.$tm_nm.' has a okay rushing offense');
    }
    if ($tm >= '15' && $tm < '25'){
      dpm('The '. $tm_nm.' has a not so good rushing offense');
    }
    if ($tm >= '25') {
      dpm('The '. $tm_nm.'have a terible rushing offense');
    }
  }

    /**
   * Constructs a query
   */
  public function checkScoring($tm, $tm_nm){
    if ($tm < '5'){
      dpm('The '. $tm_nm.' has a great scoring offense');
    }
    if ($tm >='5' && $tm < '15'){
      dpm('The '.$tm_nm.' has a okay scoring offense');
    }
    if ($tm >='15' && $tm < '25'){
      dpm('The '. $tm_nm.' has a not so good scoring offense');
    }
    if ($tm >= '25') {
      dpm('The '. $tm_nm.'have a terible scoring offense');
    }
  }


}
