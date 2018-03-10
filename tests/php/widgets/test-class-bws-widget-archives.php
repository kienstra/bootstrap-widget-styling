<?php
/**
 * Tests for class BWS_Widget_Archives.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Archives.
 */
class Test_BWS_Widget_Archives extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Archives.
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
		$this->instance = new BWS_Widget_Archives();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Archives::widget().
	 */
	public function test_widget() {
		remove_all_filters( 'wp_list_archives' );
		$count = 4;
		for ( $i = 0; $i < $count; $i++ ) {
			$this->factory()->post->create();
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
				'count' => 1,
			)
		);
		$output = ob_get_clean();
		$this->assertEquals( 0, strpos( $output, '<div class="list-group">' ) );
		$this->assertContains( sprintf( "<span class='badge pull-right'>%s</span>", $count ), $output );
		$this->assertContains( '<a class="list-group-item"', $output );
		$this->assertNotContains( '<ul', $output );
	}

}
