<?php
/**
 * Tests for main plugin file.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Test for bootstrap-widget-styling.php.
 */
class Test_Bootstrap_Widget_Styling extends \WP_UnitTestCase {
	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		require dirname( __DIR__ ) . '/bootstrap-widget-styling.php';
	}

	/**
	 * Test main plugin file
	 *
	 * @see widget-live-editor.php
	 */
	public function test_class_exists() {
		$this->assertTrue( class_exists( __NAMESPACE__ . '\Plugin' ) );
	}
}
