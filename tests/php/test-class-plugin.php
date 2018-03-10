<?php
/**
 * Tests for class Plugin.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class Plugin.
 */
class Test_Plugin extends \WP_UnitTestCase {
	/**
	 * Instance of plugin.
	 *
	 * @var object
	 */
	public $plugin;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		wp_maybe_load_widgets();
		$this->plugin = Plugin::get_instance();
	}

	/**
	 * Test get_instance().
	 *
	 * @covers Plugin::get_instance().
	 */
	public function test_get_instance() {
		$this->assertEquals( Plugin::get_instance(), $this->plugin );
		$this->assertEquals( __NAMESPACE__ . '\Plugin', get_class( Plugin::get_instance() ) );
		$this->assertEquals( plugins_url( Plugin::SLUG ), $this->plugin->location );

		Plugin::$instance = null;
		$this->assertEquals( __NAMESPACE__ . '\Plugin', get_class( Plugin::get_instance() ) );
	}

	/**
	 * Test init().
	 *
	 * @covers Plugin::init().
	 */
	public function test_init() {
		$this->plugin->init();
		$this->assertTrue( class_exists( __NAMESPACE__ . '\Plugin' ) );
		$this->assertTrue( class_exists( __NAMESPACE__ . '\Setting' ) );
		$this->assertTrue( class_exists( __NAMESPACE__ . '\Widget_Output' ) );
	}

	/**
	 * Test load_files().
	 *
	 * @covers Plugin::load_files().
	 */
	public function test_load_files() {
		$classes = array(
			__NAMESPACE__ . '\Setting',
			__NAMESPACE__ . '\Widget_Output',
			__NAMESPACE__ . '\BWS_Widget_Categories',
		);

		foreach ( $classes as $class ) {
			$this->assertTrue( class_exists( $class ) );
		}
	}

	/**
	 * Test init_classes().
	 *
	 * @covers Plugin::init_classes().
	 */
	public function test_init_classes() {
		$this->plugin->init();
		$this->assertEquals( 10, has_action( 'admin_menu', array( $this->plugin->components->setting, 'options_page' ) ) );
	}

	/**
	 * Test add_actions().
	 *
	 * @covers Plugin::add_actions().
	 */
	public function test_add_actions() {
		$this->plugin->add_actions();
		$this->assertEquals( 10, has_action( 'init', array( $this->plugin, 'textdomain' ) ) );
	}

	/**
	 * Test textdomain().
	 *
	 * @covers Plugin::textdomain().
	 */
	public function test_textdomain() {
		$this->plugin->textdomain();
		$this->assertNotEquals( false, did_action( 'load_textdomain' ) );
	}
}
