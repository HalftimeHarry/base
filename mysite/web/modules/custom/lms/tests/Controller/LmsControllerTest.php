<?php

namespace Drupal\lms\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Provides automated tests for the lms module.
 */
class LmsControllerTest extends WebTestBase {

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
  public static function getInfo() {
    return [
      'name' => "lms LmsController's controller functionality",
      'description' => 'Test Unit for module lms and controller LmsController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests lms functionality.
   */
  public function testLmsController() {
    // Check that the basic functions of module lms.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
