<?php

namespace Drupal\lms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\lms\TeamListService;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Session\AccountInterface;

/**
 * Class SecondForm.
 *
 * @package Drupal\lms\Form
 */
class SecondForm extends LoserForm {
}