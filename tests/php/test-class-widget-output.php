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
	 * @covers Widget_Output::init()
	 */
	public function test_init() {
		global $wp_filter;
		$this->instance->init();
		$this->assertEquals( 10, has_filter( 'get_search_form', array( $this->instance, 'search_form' ) ) );
		$this->assertEquals( 10, has_filter( 'wp_tag_cloud', array( $this->instance, 'tag_cloud' ) ) );
		$this->assertEquals( 10, has_filter( 'wp_nav_menu_items', array( $this->instance, 'menu_widget' ) ) );

		update_option(
			Setting::OPTION_NAME,
			array(
				'disable_categories_widget' => Setting::DISABLED_VALUE,
				'disable_pages_widget'      => 0,
			)
		);
		remove_filter( 'wp_list_categories', 'BWS_Categories::filter' );
		remove_filter( 'wp_list_pages', 'BWS_Pages::filter' );

		$this->assertEquals( 10, has_filter( 'dynamic_sidebar_params', array( $this->instance, 'add_closing_div' ) ) );
		$should_have_closures = array(
			'wp_list_pages',
			'wp_list_categories',
			'get_archives_link',
		);
		foreach ( $should_have_closures as $tag_name ) {
			$callback = array_shift( $wp_filter[ $tag_name ]->callbacks[10] );
			$this->assertEquals( 'Closure', get_class( $callback['function'] ) );
		}
	}

	/**
	 * Test add_closing_div().
	 *
	 * @covers Widget_Output::add_closing_div()
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

	/**
	 * Test search_form().
	 *
	 * @covers Widget_Output::search_form()
	 */
	public function test_search_form() {
		remove_all_filters( 'get_search_form' );
		$bootstrap_classes = 'class="btn btn-primary btn-med"';
		$form              = get_search_form( false );
		$filtered_form     = $this->instance->search_form( $form );
		$this->assertContains( $bootstrap_classes, $filtered_form );
		update_option(
			Setting::OPTION_NAME,
			array(
				Setting::DISABLE_SEARCH_WIDGET => Setting::DISABLED_VALUE,
			)
		);
		$filtered_form = $this->instance->search_form( $form );
		$this->assertNotContains( $bootstrap_classes, $filtered_form );
	}

	/**
	 * Test tag_cloud().
	 *
	 * @covers Widget_Output::tag_cloud()
	 */
	public function test_tag_cloud() {
		$first_tag  = $this->factory()->tag->create();
		$second_tag = $this->factory()->tag->create();
		$post       = $this->factory()->post->create();
		wp_set_post_tags( $post, array( $first_tag, $second_tag ) );

		remove_filter( 'wp_tag_cloud', array( $this->instance, 'tag_cloud' ) );
		$tag_cloud       = wp_tag_cloud( array(
			'echo' => false,
		) );
		$filtered_markup = $this->instance->tag_cloud( $tag_cloud );
		$this->assertContains( "<span class='label label-primary'>", $filtered_markup );
		$this->assertContains( get_tag_link( $first_tag ), $filtered_markup );
		$this->assertContains( get_tag_link( $second_tag ), $filtered_markup );
	}

	/**
	 * Test menu_widget().
	 *
	 * @covers Widget_Output::menu_widget()
	 */
	public function test_menu_widget() {
		$menu_items        = array();
		$number_menu_items = 5;
		for ( $i = 0; $i < $number_menu_items; $i++ ) {
			$menu_items[] = $this->factory()->post->create_and_get( array(
				'post_type' => 'menu_item',
			) );
		}
		$args            = (object) array(
			'before'      => '',
			'link_before' => '',
			'after'       => '',
			'link_after'  => '',
		);
		$menu_markup     = walk_nav_menu_tree( $menu_items, 0, $args );
		$filtered_markup = $this->instance->menu_widget( $menu_markup, $args );
		$this->assertContains( '</ul><div class="list-group">', $filtered_markup );
		$this->assertContains( '<a class="list-group-item"', $filtered_markup );
		$this->assertNotContains( '<li', $filtered_markup );
	}

}
