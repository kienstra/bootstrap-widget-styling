<?php
/**
 * Tests for class BWS_Widget_Pages.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Pages.
 */
class Test_BWS_Widget_Pages extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Pages.
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
		$this->instance = new BWS_Widget_Pages();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Pages::widget().
	 */
	public function test_widget() {
		$count = 4;
		for ( $i = 0; $i < $count; $i++ ) {
			$this->factory()->post->create( array(
				'post_type' => 'page',
			) );
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
		$this->assertContains( '<a class="list-group-item"', $output );
		$this->assertNotContains( '<ul', $output );
	}

}
