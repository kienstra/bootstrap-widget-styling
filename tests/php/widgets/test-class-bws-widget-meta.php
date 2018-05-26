<?php
/**
 * Tests for class BWS_Widget_Meta.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Meta.
 */
class Test_BWS_Widget_Meta extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Meta.
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
		$this->instance = new BWS_Widget_Meta();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Meta::widget().
	 */
	public function test_widget() {
		ob_start();
		$this->instance->widget(
			array(
				'before_title'  => '',
				'after_title'   => '',
				'before_widget' => '<aside>',
				'after_widget'  => '</aside>',
			),
			array(
				'count' => 1,
			)
		);
		$output = ob_get_clean();
		$this->assertContains( '<div class="list-group">', $output );
		$this->assertContains( 'class="list-group-item"', $output );
		$this->assertNotContains( '<ul', $output );
	}

}
