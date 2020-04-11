<?php

namespace Drupal\lms\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'LmsBlock' block.
 *
 * @Block(
 *  id = "lms_block",
 *  admin_label = @Translation("LMS Block"),
 * )
 */
class LmsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\DependencyInjection\ClassResolverInterface definition.
   *
   * @var \Drupal\Core\DependencyInjection\ClassResolverInterface
   */
  protected $classResolver;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->classResolver = $container->get('class_resolver');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Assuming that \Drupal\my_module\Controller\MyController::build is a
    // public function that returns the controller output.
    $controller = $this->classResolver->getInstanceFromDefinition('\Drupal\lms\Controller\LmsController');
    return $controller->poolList();
  }

}
