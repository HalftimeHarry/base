<?php

namespace Drupal\bolt\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\bolt\Entity\EquipmentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EquipmentController.
 *
 *  Returns responses for Equipment routes.
 */
class EquipmentController extends ControllerBase implements ContainerInjectionInterface {


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
   * Constructs a new EquipmentController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Equipment revision.
   *
   * @param int $equipment_revision
   *   The Equipment revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($equipment_revision) {
    $equipment = $this->entityTypeManager()->getStorage('equipment')
      ->loadRevision($equipment_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('equipment');

    return $view_builder->view($equipment);
  }

  /**
   * Page title callback for a Equipment revision.
   *
   * @param int $equipment_revision
   *   The Equipment revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($equipment_revision) {
    $equipment = $this->entityTypeManager()->getStorage('equipment')
      ->loadRevision($equipment_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $equipment->label(),
      '%date' => $this->dateFormatter->format($equipment->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Equipment.
   *
   * @param \Drupal\bolt\Entity\EquipmentInterface $equipment
   *   A Equipment object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(EquipmentInterface $equipment) {
    $account = $this->currentUser();
    $equipment_storage = $this->entityTypeManager()->getStorage('equipment');

    $langcode = $equipment->language()->getId();
    $langname = $equipment->language()->getName();
    $languages = $equipment->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $equipment->label()]) : $this->t('Revisions for %title', ['%title' => $equipment->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all equipment revisions") || $account->hasPermission('administer equipment entities')));
    $delete_permission = (($account->hasPermission("delete all equipment revisions") || $account->hasPermission('administer equipment entities')));

    $rows = [];

    $vids = $equipment_storage->revisionIds($equipment);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\bolt\EquipmentInterface $revision */
      $revision = $equipment_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $equipment->getRevisionId()) {
          $link = $this->l($date, new Url('entity.equipment.revision', [
            'equipment' => $equipment->id(),
            'equipment_revision' => $vid,
          ]));
        }
        else {
          $link = $equipment->link($date);
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
              Url::fromRoute('entity.equipment.translation_revert', [
                'equipment' => $equipment->id(),
                'equipment_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.equipment.revision_revert', [
                'equipment' => $equipment->id(),
                'equipment_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.equipment.revision_delete', [
                'equipment' => $equipment->id(),
                'equipment_revision' => $vid,
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

    $build['equipment_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
