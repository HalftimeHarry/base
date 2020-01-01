<?php

namespace Drupal\bolthunter\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Defines the Equipment entity entity.
 *
 * @ingroup bolthunter
 *
 * @ContentEntityType(
 *   id = "equipment_entity",
 *   label = @Translation("Equipment entity"),
 *   handlers = {
 *     "storage" = "Drupal\bolthunter\EquipmentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bolthunter\EquipmentEntityListBuilder",
 *     "views_data" = "Drupal\bolthunter\Entity\EquipmentEntityViewsData",
 *     "translation" = "Drupal\bolthunter\EquipmentEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\bolthunter\Form\EquipmentEntityForm",
 *       "add" = "Drupal\bolthunter\Form\EquipmentEntityForm",
 *       "edit" = "Drupal\bolthunter\Form\EquipmentEntityForm",
 *       "delete" = "Drupal\bolthunter\Form\EquipmentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bolthunter\EquipmentEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\bolthunter\EquipmentEntityAccessControlHandler",
 *   },
 *   base_table = "equipment_entity",
 *   data_table = "equipment_entity_field_data",
 *   revision_table = "equipment_entity_revision",
 *   revision_data_table = "equipment_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer equipment entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/equipment_entity/{equipment_entity}",
 *     "add-form" = "/admin/structure/equipment_entity/add",
 *     "edit-form" = "/admin/structure/equipment_entity/{equipment_entity}/edit",
 *     "delete-form" = "/admin/structure/equipment_entity/{equipment_entity}/delete",
 *     "version-history" = "/admin/structure/equipment_entity/{equipment_entity}/revisions",
 *     "revision" = "/admin/structure/equipment_entity/{equipment_entity}/revisions/{equipment_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/equipment_entity/{equipment_entity}/revisions/{equipment_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/equipment_entity/{equipment_entity}/revisions/{equipment_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/equipment_entity/{equipment_entity}/revisions/{equipment_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/equipment_entity",
 *   },
 *   field_ui_base_route = "equipment_entity.settings"
 * )
 */
class EquipmentEntity extends EditorialContentEntityBase implements EquipmentEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly,
    // make the equipment_entity owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
    $this->getDirationForNextPM();
    $this->advanceMaintenceDate();
    $this->getDirationForFinalPM();
    $this->advanceFinalDate();
    $this->setNextPMAuto();
    $this->setFinalPMAuto();
  }

  /**
   * {@inheritdoc}
   * The users will set defaults throught the Configuration menu for the Diration on the next pm
   */
  public function getDirationForNextPM() {
  // We access our configuration.
    $config = \Drupal::config('bolthunter.bolt');
    $next_pm = $config->get('get_dir_next_pm');
    return $next_pm;
  }

  /**
   * {@inheritdoc}
   */
  public function getDirationForFinalPM() {
  // We access our configuration.
    $config = \Drupal::config('bolthunter.bolt');
    $final_pm = $config->get('get_dir_final');
    dpm($final_pm);
    return $final_pm;
  }

  /**
   * {@inheritdoc}
   */
  public function advanceMaintenceDate(){
    $get_dir = $this->getDirationForNextPM();
    $last_pm_date = $this->get('field_equ_pm_last')->value;
    $date = new DrupalDateTime($last_pm_date , 'UTC');
    $date->modify($get_dir);
    $new_date = $date->format('Y-m-d');
    return $new_date;
  }

  /**
   * {@inheritdoc}
   */
  public function setNextPMAuto(){
    $yes = $this->get('field_equ_set_main')->value;
    $next = $this->advanceMaintenceDate();
    if ($yes === 'yes'){
      $this->set('field_equ_pm_due', $next);
    }
  }


  /**
   * {@inheritdoc}
   */
  public function advanceFinalDate(){
    $get_dir = $this->getDirationForFinalPM();
    $final_pm_date = $this->get('field_equ_main')->value;
    $date = new DrupalDateTime($final_pm_date , 'UTC');
    $date->modify($get_dir);
    $new_date = $date->format('Y-m-d');
      return $new_date;
  }

  /**
   * {@inheritdoc}
   */
  public function setFinalPMAuto(){
    $yes = $this->get('field_equ_set_main')->value;
    $final = $this->advanceFinalDate();
    if ($yes === 'yes'){
      $this->set('field_equ_main', $final);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Equipment entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Equipment entity entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Equipment entity is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
