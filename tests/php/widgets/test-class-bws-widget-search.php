<?php
/**
 * Tests for class BWS_Widget_Search.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Search.
 */
class Test_BWS_Widget_Search extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Search.
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
		$this->instance = new BWS_Widget_Search();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Search::widget().
	 */
	public function test_widget() {
		ob_start();
		$this->instance->widget(
			array(
				'before_title'  => '',
				'after_title'   => '',
				'before_widget' => '',
				'after_widget'  => '',
			),
			array()
		);
		$output = ob_get_clean();

		$this->assertContains( '<div class="input-group">', $output );
		$this->assertContains( '<input type="text" class="form-control"', $output );
		$this->assertContains( '<input type="text" class="form-control"', $output );
		$this->assertContains( '<input type="submit" class="btn btn-primary btn-med"', $output );
		$this->assertContains( '<div class="input-group-btn">', $output );
		$this->assertNotContains( '<label', $output );
	}

}
