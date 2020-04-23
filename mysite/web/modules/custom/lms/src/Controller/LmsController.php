<?php

namespace Drupal\lms\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Markup;

/**
 * Class LmsController.
 */
class LmsController extends ControllerBase {

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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->currentUser = $container->get('current_user');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->entityQuery = $container->get('entity.query');
    return $instance;
  }

  /**
   * Poollist.
   *
   * @return string
   *   Return Hello string.
   */
  public function poolList() {
     $uid = $this->currentUser->id();
     $user = $this->entityTypeManager->getStorage('user')->load($this->currentUser()->id($uid));
     $pool_admin_id = $user->get('field_pools')->getValue()[0]['target_id'];
     $lms_service = \Drupal::service('lms.pool');
     $pool_list = $lms_service->getListOfEntriesCreatedByThisPoolAdmin($pool_admin_id);
     $node_storage = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($pool_list);

    $rows = [];

      foreach ($node_storage as $row){
        $rows [] = [$title = $row->get('title')->value,
          $wk_1 = $row->get('field_week_1')->value,
          $wk_2 = $row->get('field_week_2')->value];
        }

    $header = [
      'title' => t('Entry'),
      'wk_1' => t('Week 1'),
      'wk_2' => t('Week 2'),
    ];
    $build['table'] = [
     '#type' => 'table',
     '#header' => $header,
     '#rows' => $rows,
     '#empty' => t('No content has been found.'),
    ];
    return [
      '#type' => '#markup',
      '#markup' => render($build)
    ];

  }

}
