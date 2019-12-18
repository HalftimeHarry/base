<?php

namespace Drupal\awntrack;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Class AwnService.
 */
class AwnService {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  protected $queryFactory;
  /**
   * Constructs a new AwnService object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, QueryFactory $queryFactory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->queryFactory = $queryFactory;
  }
  
  /**
   * Match scan and update node
   *
   * @param string $field
   * @param string $value
   * @return bool
   */
  
  public function matchScanToUcid($scan) {
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('type', 'equipment')
      ->condition('field_equ_ucid', $scan , '=')
      ->execute();
    return $query_result;  
   // ksm($query_result);
  }
  
  /**
   * Match scan and update node
   *
   * @param string $field
   * @param string $value
   * @return bool
   */
  
  public function matchUserNameToLocationName($username) {
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('type', 'location')
      ->condition('title', $username , '=')
      ->execute();
    return $query_result;
  }
  
  /**
   * Update node and populate fields.
   *
   * @param string $test is the id
   * @param string $new_id is the new id
   */
   
  public function updateNodeEquipmentToNewLocation($equ_id, $loc_id) {
    $location_entity = $this->entityTypeManager->getStorage('node')->load($loc_id);
    $entity = $this->entityTypeManager->getStorage('node')->load($equ_id);
    $entity->set('field_equ_loc_ref', $location_entity);
    $entity->save();
  } 
  
  /**
   * Match place issuing info
   *
   * @param string $field
   * @param string $value
   * @return bool
   */
  
  public function getIssuingInfo($iid) {
    $equ_entity = $this->entityTypeManager->getStorage('node')->load($iid);
    $loc_ref = $equ_entity->get('field_equ_loc_ref')->getString();
    return $loc_ref;
  }
  
  /**
   * Match place issuing info
   *
   * @param string $field
   * @param string $value
   * @return bool
   */
  
  public function matchRefToPatient($match) {
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('type', 'patient')
      ->condition('field_pat_loc_ref', $match , '=')
      ->execute();
    return $query_result;
  }
  
  /**
   * Update node and populate fields.
   *
   * @param string $test is the id
   * @param string $new_id is the new id
   */
   
  public function getPatentData($loc_build) {
    $location_entity = $this->entityTypeManager->getStorage('node')->load($loc_build);
    return $location_entity;
  } 
  
  /**
   * Update node and populate fields.
   *
   * @param string $test is the id
   * @param string $new_id is the new id
   */
   
  public function updateEquipmentToPatientInitial($equ_id, $loc_id, $pat_intl) {
    $location_entity = $this->entityTypeManager->getStorage('node')->load($loc_id);
    $entity = $this->entityTypeManager->getStorage('node')->load($equ_id);
    $entity->set('field_equ_loc_ref', $location_entity);
    $entity->set('field_equ_issued_to', $pat_intl);
    $entity->save();
  } 
  
}
