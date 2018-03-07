<?php
/**
 * Tests for class Bootstrap_Markup.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class Bootstrap_Markup.
 */
class Test_Bootstrap_Markup extends \WP_UnitTestCase {

	/**
	 * Instance of Bootstrap_Markup.
	 *
	 * @var object
	 */
	public $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		$plugin         = Plugin::get_instance();
		$this->instance = $plugin->components->bootstrap_markup;
	}

	/**
	 * Test reformat().
	 *
	 * @covers Bootstrap_Markup::reformat()
	 */
	public function test_reformat() {
	}

}
