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
		$this->assertEquals( 10, has_filter( 'wp_nav_menu_items', array( $this->instance, 'reformat' ) ) );

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
		$should_have_callbacks = array(
			'wp_list_pages',
			'wp_list_categories',
			'get_archives_link',
		);
		foreach ( $should_have_callbacks as $tag_name ) {
			$callback = array_shift( $wp_filter[ $tag_name ]->callbacks[10] );
			$this->assertEquals( 'BootstrapWidgetStyling\Widget_Output', get_class( $callback['function'][0] ) );
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
	 * Test reformat().
	 *
	 * @covers Widget_Output::reformat()
	 */
	public function test_reformat() {
		remove_all_filters( 'wp_list_categories' );
		$first_category  = $this->factory()->category->create();
		$second_category = $this->factory()->category->create();
		$this->factory()->post->create( array(
			'post_category' => array( $first_category ),
		) );

		$count = 5;
		for ( $i = 0; $i < $count; $i++ ) {
			$this->factory()->post->create( array(
				'post_category' => array( $second_category ),
			) );
		}
		$list         = $this->instance->reformat( wp_list_categories( array(
			'echo'       => false,
			'show_count' => 1,
		) ) );
		$not_expected = array(
			'</ul>',
			'<li>',
			'</li>',
		);
		foreach ( $not_expected as $tag ) {
			$this->assertNotContains( $tag, $list );
		}
		$this->assertEquals( 0, strpos( $list, '<div class="list-group">' ) );
		$this->assertContains( sprintf( "<span class='badge pull-right'>%s</span>", $count ), $list );
		$this->assertContains( '<a class="list-group-item"', $list );
	}

}
