<?php
/**
 * Tests for class Widget_Output.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class Widget_Output.
 */
class Test_Widget_Output extends \WP_UnitTestCase {

	/**
	 * Instance of Widget_Output.
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
		$this->instance = $plugin->components->widget_output;
	}

	/**
	 * Test init().
	 *
	 * @see Widget_Output::init()
	 */
	public function test_init() {
		$this->instance->init();
		$this->assertEquals( 10, has_action( 'init', array( $this->instance, 'add_filters' ) ) );
	}

	/**
	 * Test add_filters().
	 *
	 * @see Widget_Output::add_filters()
	 */
	public function test_add_filters() {
		$options = array(
			'disable_categories_widget' => Setting::DISABLED_VALUE,
			'disable_pages_widget'      => 0,
		);
		remove_filter( 'wp_list_categories', 'BWS_Categories::filter' );
		remove_filter( 'wp_list_pages', 'BWS_Pages::filter' );

		update_option( Setting::OPTION_NAME, $options );
		$this->instance->add_filters();
		$this->assertFalse( has_filter( 'wp_list_categories', 'BWS_Categories::filter' ) );
		$this->assertEquals( 10, has_filter( 'wp_list_pages', 'BWS_Pages::filter' ) );
	}

	/**
	 * Test add_closing_div().
	 *
	 * @see Widget_Output::add_closing_div()
	 */
	public function test_add_closing_div() {
		$initial_markup  = '<div>Example Content';
		$params          = array(
			array(
				'widget_name'  => 'Archives',
				'after_widget' => $initial_markup,
			),
		);
		$filtered_params = $this->instance->add_closing_div( $params );
		$this->assertEquals( '</div>' . $initial_markup, $filtered_params[0]['after_widget'] );
	}

}
