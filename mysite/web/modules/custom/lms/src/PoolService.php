<?php

namespace Drupal\lms;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class PoolService.
 */
class PoolService implements PoolServiceInterface {

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

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
   * Constructs a new PoolService object.
   */
  public function __construct(AccountProxyInterface $current_user, EntityTypeManagerInterface $entity_type_manager, ContainerAwareInterface $entity_query) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityQuery = $entity_query;
  }


  /**
   * Constructs a query
   */
  public function getListOfEntriesCreatedByThisPoolAdmin($pool_admin_id){
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('status', 0)
      ->condition('type', 'entry')
      ->condition('uid', $pool_admin_id)
      ->sort('created','DESC')
      ->execute();
    return $query_result;
  }

}
