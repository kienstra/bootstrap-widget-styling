<?php
/**
 * Tests for class BWS_Widget_Recent_Posts.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Recent_Posts.
 */
class Test_BWS_Widget_Recent_Posts extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Recent_Posts.
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
		$this->instance = new BWS_Widget_Recent_Posts();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Recent_Posts::widget().
	 */
	public function test_widget() {
		$first_post  = $this->factory()->post->create_and_get();
		$second_post = $this->factory()->post->create_and_get();
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
		$this->assertContains( $first_post->post_title, $output );
		$this->assertContains( $second_post->post_title, $output );
		$this->assertNotContains( '<ul', $output );
	}

}
