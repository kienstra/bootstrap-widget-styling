<?php
/**
 * Tests for class Setting.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class Setting.
 */
class Test_Setting extends \WP_UnitTestCase {

	/**
	 * Instance of Setting.
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
		wp_maybe_load_widgets();
		$plugin         = Plugin::get_instance();
		$this->instance = $plugin->components->setting;
	}

	/**
	 * Test init().
	 *
	 * @covers Setting::init()
	 */
	public function test_init() {
		$this->instance->init();
		$this->assertEquals( 10, has_action( 'admin_menu', array( $this->instance, 'options_page' ) ) );
		$this->assertEquals( 10, has_action( 'admin_init', array( $this->instance, 'register' ) ) );
		$this->assertEquals( 10, has_action( 'admin_init', array( $this->instance, 'add_settings_fields' ) ) );
		$this->assertEquals( 10, has_action( 'plugin_action_links', array( $this->instance, 'settings_link' ) ) );
	}

	/**
	 * Test options_page().
	 *
	 * @covers Setting::options_page()
	 */
	public function test_options_page() {
		global $submenu;
		wp_set_current_user( $this->factory->user->create( array(
			'role' => 'administrator',
		) ) );
		$this->instance->options_page();
		$options = end( $submenu['options-general.php'] );

		$this->assertEquals( 'Widget Styling', $options[0] );
		$this->assertEquals( 'manage_options', $options[1] );
		$this->assertEquals( Setting::SUBMENU_SLUG, $options[2] );
		$this->assertEquals( 'Bootstrap Widget Styling Settings', $options[3] );
	}

	/**
	 * Test options_markup().
	 *
	 * @covers Setting::options_markup()
	 */
	public function test_options_markup() {
		ob_start();
		$this->instance->options_markup();
		$markup = ob_get_clean();
		$this->assertContains( '<div class="wrap">', $markup );
		$this->assertContains( 'Bootstrap Widget Styling', $markup );
		$this->assertContains( 'type="submit"', $markup );
	}

	/**
	 * Test plugin_section().
	 *
	 * @covers Setting::plugin_section()
	 */
	public function test_plugin_section() {
		ob_start();
		$this->instance->plugin_section();
		$output = ob_get_clean();
		$this->assertContains( 'This plugin does not work well when the top navbar has a', $output );
	}

	/**
	 * Test validate_options().
	 *
	 * @covers Setting::validate_options()
	 */
	public function test_validate_options() {
		$expected_validated_options = array(
			'disable_categories_widget' => '1',
			'disable_pages_widget'      => '0',
		);
		$options                    = array_merge(
			$expected_validated_options,
			array(
				'disable_foo-invalid_widget' => '0',
			)
		);
		$validated_options          = $this->instance->validate_options( $options );
		$this->assertEquals( $expected_validated_options, $validated_options );
	}

	/**
	 * Test register().
	 *
	 * @covers Setting::register()
	 */
	public function test_register() {
		global $wp_registered_settings, $wp_settings_sections;
		$option_name   = 'bws_plugin_options';
		$expected_args = array(
			'type'              => 'string',
			'group'             => Setting::OPTION_NAME,
			'description'       => '',
			'sanitize_callback' => array(
				$this->instance,
				'validate_options',
			),
			'show_in_rest'      => false,
		);
		$this->instance->register();
		$this->assertEquals( $expected_args, $wp_registered_settings[ $option_name ] );

		$id      = Setting::SETTINGS_SECTION;
		$page    = Setting::SUBMENU_SLUG;
		$section = $wp_settings_sections[ $page ][ $id ];

		$expected_section = array(
			'id'       => $id,
			'callback' => array(
				$this->instance,
				'section_text',
			),
			'title'    => 'Settings',
		);
		$this->assertEquals( $expected_section, $section );
	}

	/**
	 * Test add_settings_fields().
	 *
	 * @covers Setting::add_settings_fields()
	 */
	public function test_add_settings_fields() {
		global $wp_settings_fields;
		$this->instance->add_settings_fields();
		$widget  = 'pages';
		$id      = "bws_plugin_disable_{$widget}_widget";
		$title   = ucwords( $widget ) . ' widget';
		$page    = Setting::SUBMENU_SLUG;
		$section = Setting::SETTINGS_SECTION;
		$field   = $wp_settings_fields[ $page ][ $section ][ $id ];
		$this->assertEquals( $id, $field['id'] );
		$this->assertEquals( $title, $field['title'] );
		$this->assertEmpty( $field['args'] );
		$this->assertTrue( is_callable( $field['callback'] ) );
	}

	/**
	 * Test is_disabled().
	 *
	 * @covers Setting::is_disabled()
	 */
	public function test_is_disabled() {
		$options = array(
			'disable_categories_widget' => Setting::DISABLED_VALUE,
			'disable_pages_widget'      => 0,
		);
		update_option( Setting::OPTION_NAME, $options );
		$this->assertTrue( $this->instance->is_disabled( 'categories' ) );
		$this->assertFalse( $this->instance->is_disabled( 'pages' ) );
	}

	/**
	 * Test section_text().
	 *
	 * @covers Setting::section_text()
	 */
	public function test_section_text() {
		ob_start();
		$this->instance->section_text();
		$output = ob_get_clean();
		$this->assertContains( 'This plugin does not work well when the top navbar has a', $output );
		$this->assertContains( 'Disable', $output );
	}

	/**
	 * Test settings_link().
	 *
	 * @covers Setting::settings_link()
	 */
	public function test_settings_link() {
		$plugin_file = 'other-plugin/other-plugin.php';
		$this->assertEquals( array(), $this->instance->settings_link( array(), $plugin_file ) );
		$correct_plugin_file = Plugin::SLUG . '/' . Plugin::SLUG . '.php';
		$actions             = $this->instance->settings_link( array(), $correct_plugin_file );
		$this->assertContains( '<a href=', $actions['settings'] );
		$this->assertContains( admin_url( 'options-general.php?page=' . Setting::SUBMENU_SLUG ), $actions['settings'] );
		$this->assertContains( 'Settings', $actions['settings'] );
	}

}
