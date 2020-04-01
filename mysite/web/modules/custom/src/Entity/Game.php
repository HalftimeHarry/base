<?php

namespace Drupal\bliz_odds\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Session\AccountInterface;


/**
 * Defines the Game entity.
 *
 * @ingroup bliz_odds
 *
 * @ContentEntityType(
 *   id = "game",
 *   label = @Translation("Game"),
 *   handlers = {
 *     "storage" = "Drupal\bliz_odds\GameStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bliz_odds\GameListBuilder",
 *     "views_data" = "Drupal\bliz_odds\Entity\GameViewsData",
 *     "translation" = "Drupal\bliz_odds\GameTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\bliz_odds\Form\GameForm",
 *       "add" = "Drupal\bliz_odds\Form\GameForm",
 *       "edit" = "Drupal\bliz_odds\Form\GameForm",
 *       "delete" = "Drupal\bliz_odds\Form\GameDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bliz_odds\GameHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\bliz_odds\GameAccessControlHandler",
 *   },
 *   base_table = "game",
 *   data_table = "game_field_data",
 *   revision_table = "game_revision",
 *   revision_data_table = "game_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer game entities",
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
 *     "canonical" = "/admin/structure/game/{game}",
 *     "add-form" = "/admin/structure/game/add",
 *     "edit-form" = "/admin/structure/game/{game}/edit",
 *     "delete-form" = "/admin/structure/game/{game}/delete",
 *     "version-history" = "/admin/structure/game/{game}/revisions",
 *     "revision" = "/admin/structure/game/{game}/revisions/{game_revision}/view",
 *     "revision_revert" = "/admin/structure/game/{game}/revisions/{game_revision}/revert",
 *     "revision_delete" = "/admin/structure/game/{game}/revisions/{game_revision}/delete",
 *     "translation_revert" = "/admin/structure/game/{game}/revisions/{game_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/game",
 *   },
 *   field_ui_base_route = "game.settings"
 * )
 */
class Game extends EditorialContentEntityBase implements GameInterface {

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
  // We access our configuration.
    $config = \Drupal::config('bliz_odds.createcustomgamesetting');
    $sp_mes = $config->get('special_message');
    $cre_gm = $config->get('auto_create_past_game');
    $use_blz_id = $config->get('bliz_number_to_use');
    $season = $config->get('season');
    $week = $config->get('week');
    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly,
    // make the game owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
    $this->getPastGames();
    $this->setAverage();
    $this->setPrediction();
    if ($cre_gm === 'yes'){
       $this->createPastGame();
      }
    $this->setResult();
    $this->setPredictionResult();
    $this->setNewName();
    $this->updateName();
    $this->displayRankings();
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
    return $this->set('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function updateName(){
    $auto_name = $this->setNewName();
    $this->set('name', $auto_name);
      return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLimit() {
    return $this->get('field_gm_limit')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function checkIfAwayWon() {
    return $this->get('field_gm_away_won')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function getLosingTeam() {
    return $this->get('field_gm_loser_tie')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function getWinningTeam() {
    return $this->get('field_gm_winner_tie')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getAverage() {
    return $this->get('field_gm_average')->value;
  }


   /**
   * {@inheritdoc}
   */
  public function getPointsLoser() {
    return $this->get('field_gm_ptsl')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function getPointsWinner() {
    return $this->get('field_gm_ptsw')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function getGameState() {
    return $this->get('field_gm_state')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function getTotal() {
    return $this->get('field_gm_total')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getPredictionResult() {
    return $this->get('field_gm_prediction_result')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrediction() {
    return $this->get('field_gm_prediction')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getTeamToCompare() {
    return $this->get('field_gm_compare_tm')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getHomeTeam() {
    return $this->get('field_gm_hm_ref')->value;
  }


  /**
   * {@inheritdoc}
   */
  public function getAwayTeam() {
    return $this->get('field_gm_aw_ref')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getResult() {
    return $this->get('field_gm_result')->value;
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
  public function getPastGames() {
      $lmt = $this->getLimit();
      $vgs_tot = $this->getTotal();
      $tm_comp = $this->getTeamToCompare();
    if ($tm_comp === 'home'){
      $hm_ref = $this->get('field_gm_hm_ref')->entity;
      $tm_nm = $hm_ref->get('title')->getValue();
      $tm = $tm_nm[0]['value']; //boil it down to just the title of the Home team
    }
    if ($tm_comp === 'away'){
      $hm_ref = $this->get('field_gm_aw_ref')->entity;
      $tm_nm = $hm_ref->get('title')->getValue();
      $tm = $tm_nm[0]['value']; //boil it down to just the title of the Away team
    }
      $pg_service = \Drupal::service('bliz_odds.default');
      $hwp = $pg_service->teamWonPastGame($tm);
      $hlp = $pg_service->teamLostPastGame($tm);
      $all = array_merge($hwp, $hlp);
      rsort($all);
      $output = array_slice($all, 0, $lmt);
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($output);
      foreach ($nodes as $node){
     //   $v = $node->get('nid')->value; // only used to check how it is sorting
      $ws = $node->get('field_pg_ptsw')->value;
      $ls = $node->get('field_pg_ptsl')->value;
      $total = $ws + $ls;
      $sum += $total;
    }
      $d = $sum / $lmt;
      dpm($d);
      $a = $d - $vgs_tot;
        return $a;

  }

  /**
   * {@inheritdoc}
   */
  public function getPastGamesUsingBlizId() {
      $lmt = $this->getLimit();
      $vgs_tot = $this->getTotal();
      $tm_comp = $this->getTeamToCompare();
    if ($tm_comp === 'home'){
      $hm_ref = $this->get('field_gm_hm_ref')->entity;
      $tm_nm = $hm_ref->get('title')->getValue();
      $tm = $tm_nm[0]['value']; //boil it down to just the title of the Home team
    }
    if ($tm_comp === 'away'){
      $hm_ref = $this->get('field_gm_aw_ref')->entity;
      $tm_nm = $hm_ref->get('title')->getValue();
      $tm = $tm_nm[0]['value']; //boil it down to just the title of the Away team
    }
      $pg_service = \Drupal::service('bliz_odds.default');
      $hwp = $pg_service->teamWonPastGame($tm); //add smart filter
      $hlp = $pg_service->teamLostPastGame($tm); //add smart filter
      $all = array_merge($hwp, $hlp);
      rsort($all);
      $output = array_slice($all, 0, $lmt);
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($output);
      foreach ($nodes as $node){
        $v = $node->get('nid')->value; // only used to check how it is sorting
        $ws = $node->get('field_pg_ptsw')->value;
        $ls = $node->get('field_pg_ptsl')->value;
        $total = $ws + $ls;
        $sum+=$total;
    }
        $d = $sum / $lmt;
        dpm($d);
        $a = $d - $vgs_tot;
        return $a;
  }

  /**
   * {@inheritdoc}
   */
  public function setAverage() {
    $avg = $this->getPastGames();
    return $this->set('field_gm_average', $avg);
  }

  /**
   * {@inheritdoc}
   */
  public function setNewName() {
    // We access our configuration.
    $config = \Drupal::config('bliz_odds.createcustomgamesetting');
    $season = $config->get('season');
    $week = $config->get('week');
    $hom = $this->get('field_gm_hm_ref')->entity;
    $hm = $hom->get('title')->getValue();
    $h = $hm[0]['value']; //not used
    $hs = $hom->get('field_tm_logo')->getValue();
    $hss = $hs[0]['alt'];
    $awy = $this->get('field_gm_aw_ref')->entity;
    $aw = $awy->get('title')->getValue();
    $as = $awy->get('field_tm_logo')->getValue();
    $ass = $as[0]['alt'];
    $a = $aw[0]['value']; //not used
    $new_name = $season.' | '.$ass.' @ '. $hss.' Wk'.$week;
    return $new_name;
  }
  /**
   * {@inheritdoc}
   */
  public function calculateScores(){
    $ws = $this->getPointsWinner();
    $ls = $this->getPointsLoser();
    $tot = $ws + $ls;
    return $tot;
  }

  /**
   * {@inheritdoc}
   */
  public function setPrediction() {
    $avg = $this->getAverage();
    if ( $avg > 5 ){
      dpm('over');
      return $this->set('field_gm_prediction', 'over');
    }
    if ( $avg < -5 ){
      dpm('under');
      return $this->set('field_gm_prediction', 'under');
    }
    else {
      dpm('no play');
      return $this->set('field_gm_prediction', 'no_play');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setPredictionResult(){
    $pred = $this->getPrediction();
    $pred_result = $this->getResult();
    if ( $pred === 'no_play'){
      return $this->set('field_gm_prediction_result','did_not_play');
    }
    if ($pred === 'over' && $pred_result ==='over'){
      return $this->set('field_gm_prediction_result','correct');
    }
    if ($pred === 'under' && $pred_result ==='under'){
      return $this->set('field_gm_prediction_result','correct');
    }
    else{
      return $this->set('field_gm_prediction_result','wrong');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createPastGame(){
    // We access our configuration.
    $config = \Drupal::config('bliz_odds.createcustomgamesetting');
    $use_blz_id = $config->get('bliz_number_to_use');
    $week = $config->get('week');
    $hom_ref = $this->get('field_gm_hm_ref')->entity;
    $tem_hm = $hom_ref->get('title')->getValue();
    $tem_nm_hm = $tem_hm[0]['value']; //boil it down to just the name of the Home team need to use the ref field
    $awa_ref = $this->get('field_gm_aw_ref')->entity;
    $tem_aw = $awa_ref->get('title')->getValue();
    $tem_nm_aw = $tem_aw[0]['value']; //boil it down to just the name of the Away team need to use the ref field
    $ws = $this->getPointsWinner();
    $ls = $this->getPointsLoser();
    $aw_won = $this->checkIfAwayWon();
    $scored = $ws + $ls;
    if ($scored > 0){
    $node = Node::create(['type' => 'past_game']);
    $node->set('field_pg_away_won', $aw_won);
    if ($aw_won === '@'){
    $node->set('field_pg_winner_tie', $tem_nm_aw);//get name with logic
    $node->set('field_pg_loser_tie', $tem_nm_hm);//get name with logic
    $node->set('title', $use_blz_id);
    }
    if ($aw_won === NULL){
    $node->set('field_pg_winner_tie', $tem_nm_hm);//get name with logic
    $node->set('field_pg_loser_tie', $tem_nm_aw);//get name with logic
    $node->set('title', $tem_nm_hm);
    }
    $node->set('field_pg_ptsl', $ls);
    $node->set('field_pg_ptsw', $ws);
    $node->set('field_pg_bliz_id', $use_blz_id);
    $node->set('field_pg_week', $week);
    $node->set('uid', 1);
    $node->status = 1;
    $node->enforceIsNew();
    $node->save();
    drupal_set_message( "Node with nid " . $node->id() . " saved!\n");

    }
  }

  /**
   * {@inheritdoc}
   */
  public function setResult(){
    $sum = $this->calculateScores();
    $total = $this->getTotal();
    if ($sum === $total){
      return $this->set('field_gm_result', 'push');
    }
    if ($sum > $total){
      return $this->set('field_gm_result', 'over');
    }
    if ($sum < $total){
      return $this->set('field_gm_result', 'under');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function displayRankings(){
    // Drill into the teams and get the data
    $hom_ent = $this->get('field_gm_hm_ref')->entity;
    $hm = $hom_ent->get('field_tm_str_of_sch_float')->getValue();
    $hm_sos = $hm[0]['value']; //boil it down to the sos
    $hm_ti = $hom_ent->get('title')->getValue();
    $hm_title = $hm_ti[0]['value']; //boil it down to the tittle
    $hm2 = $hom_ent->get('field_tm_srs_float')->getValue();
    $hm_srs = $hm2[0]['value']; //boil it down to the srs
    $hm3 = $hom_ent->get('field_tm_off_pass')->getValue();
    $hm_pass = $hm3[0]['value']; //boil it down to the pass
    $hm4 = $hom_ent->get('field_tm_def_scor')->getValue();
    $hm_def = $hm4[0]['value']; //boil it down to the def
    $hm5 = $hom_ent->get('field_tm_rush_off')->getValue();
    $hm_rush = $hm5[0]['value']; //boil it down to the rush
    $hm6 = $hom_ent->get('field_tm_off_scor')->getValue();
    $hm_off = $hm6[0]['value']; //boil it down to the off
    // get away info
    $awa_ent = $this->get('field_gm_aw_ref')->entity;
    $aw = $awa_ent->get('field_tm_str_of_sch_float')->getValue();
    $aw_sos = $aw[0]['value']; //boil it down to the sos
    $aw_ti = $awa_ent->get('title')->getValue();
    $aw_title = $aw_ti[0]['value']; //boil it down to the sos
    $aw2 = $awa_ent->get('field_tm_srs_float')->getValue();
    $aw_srs = $aw2[0]['value']; //boil it down to the srs
    $aw3 = $awa_ent->get('field_tm_off_pass')->getValue();
    $aw_pass = $aw3[0]['value']; //boil it down to the pass
    $aw4 = $awa_ent->get('field_tm_def_scor')->getValue();
    $aw_def = $aw4[0]['value']; //boil it down to the def
    $aw5 = $awa_ent->get('field_tm_rush_off')->getValue();
    $aw_rush = $aw5[0]['value']; //boil it down to the rush
    $aw6 = $awa_ent->get('field_tm_off_scor')->getValue();
    $aw_off = $aw6[0]['value']; //boil it down to the off
    $pass_serv = \Drupal::service('bliz_odds.default');

    $pa2 = $pass_serv->checkDefense($aw_def, $aw_title);
    $pa4 = $pass_serv->checkScoring($aw_off, $aw_title);
    $pa3 = $pass_serv->checkRushing($aw_rush, $aw_title);
    $pa = $pass_serv->checkPassing($aw_pass, $aw_title);
    dpm('--------------------------------------');
    $ph2 = $pass_serv->checkDefense($hm_def, $hm_title);
    $ph4 = $pass_serv->checkScoring($hm_off, $hm_title);
    $ph3 = $pass_serv->checkRushing($hm_rush, $hm_title);
    $ph = $pass_serv->checkPassing($hm_pass, $hm_title);






    // Also see who has a better SRS
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
      ->setDescription(t('The user ID of author of the Game entity.'))
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
      ->setDescription(t('The name of the Game entity.'))
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

    $fields['status']->setDescription(t('A boolean indicating whether the Game is published.'))
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
