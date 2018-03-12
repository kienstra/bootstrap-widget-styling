<?php
/**
 * Tests for class BWS_Nav_Menu_Widget.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Nav_Menu_Widget.
 */
class Test_BWS_Nav_Menu_Widget extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Nav_Menu_Widget.
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
		$this->instance = new BWS_Nav_Menu_Widget();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Nav_Menu_Widget::widget().
	 */
	public function test_widget() {
		$name            = 'Foo Menu';
		$menu            = wp_create_nav_menu( $name );
		$menu_item_title = 'Bar Title';
		$count           = 4;
		for ( $i = 0; $i < $count; $i++ ) {
			wp_update_nav_menu_item(
				$menu,
				0,
				array(
					'menu-item-title'  => $menu_item_title,
					'menu-item-url'    => get_home_url(),
					'menu-item-status' => 'publish',
				)
			);
		}

		ob_start();
		$this->instance->widget(
			array(
				'before_title'  => '',
				'after_title'   => '',
				'before_widget' => '',
				'after_widget'  => '',
			),
			array(
				'nav_menu' => $menu,
			)
		);
		$output = ob_get_clean();
		$this->assertContains( $menu_item_title, $output );
		$this->assertEquals( 0, strpos( $output, '<div class="list-group">' ) );
		$this->assertContains( '<a class="list-group-item"', $output );
	}

}
