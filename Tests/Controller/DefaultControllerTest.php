<?php

/**
 * @file
 * Contains \Drupal\config_quickedit\Tests\DefaultController.
 */

namespace Drupal\config_quickedit\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides automated tests for the config_quickedit module.
 */
class DefaultControllerTest extends WebTestBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity_type_manager;
  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "config_quickedit DefaultController's controller functionality",
      'description' => 'Test Unit for module config_quickedit and controller DefaultController.',
      'group' => 'Other',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests config_quickedit functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module config_quickedit.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }

}
