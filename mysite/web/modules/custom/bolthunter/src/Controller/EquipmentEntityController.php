<?php

namespace Drupal\bolthunter\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\bolthunter\Entity\EquipmentEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EquipmentEntityController.
 *
 *  Returns responses for Equipment entity routes.
 */
class EquipmentEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Equipment entity revision.
   *
   * @param int $equipment_entity_revision
   *   The Equipment entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($equipment_entity_revision) {
    $equipment_entity = $this->entityTypeManager()->getStorage('equipment_entity')
      ->loadRevision($equipment_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('equipment_entity');

    return $view_builder->view($equipment_entity);
  }

  /**
   * Page title callback for a Equipment entity revision.
   *
   * @param int $equipment_entity_revision
   *   The Equipment entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($equipment_entity_revision) {
    $equipment_entity = $this->entityTypeManager()->getStorage('equipment_entity')
      ->loadRevision($equipment_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $equipment_entity->label(),
      '%date' => $this->dateFormatter->format($equipment_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Equipment entity.
   *
   * @param \Drupal\bolthunter\Entity\EquipmentEntityInterface $equipment_entity
   *   A Equipment entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(EquipmentEntityInterface $equipment_entity) {
    $account = $this->currentUser();
    $equipment_entity_storage = $this->entityTypeManager()->getStorage('equipment_entity');

    $langcode = $equipment_entity->language()->getId();
    $langname = $equipment_entity->language()->getName();
    $languages = $equipment_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $equipment_entity->label()]) : $this->t('Revisions for %title', ['%title' => $equipment_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all equipment entity revisions") || $account->hasPermission('administer equipment entity entities')));
    $delete_permission = (($account->hasPermission("delete all equipment entity revisions") || $account->hasPermission('administer equipment entity entities')));

    $rows = [];

    $vids = $equipment_entity_storage->revisionIds($equipment_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\bolthunter\EquipmentEntityInterface $revision */
      $revision = $equipment_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $equipment_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.equipment_entity.revision', [
            'equipment_entity' => $equipment_entity->id(),
            'equipment_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $equipment_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.equipment_entity.translation_revert', [
                'equipment_entity' => $equipment_entity->id(),
                'equipment_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.equipment_entity.revision_revert', [
                'equipment_entity' => $equipment_entity->id(),
                'equipment_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.equipment_entity.revision_delete', [
                'equipment_entity' => $equipment_entity->id(),
                'equipment_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['equipment_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
