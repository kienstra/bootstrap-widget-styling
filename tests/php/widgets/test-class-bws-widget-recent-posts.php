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
		$args        = array(
			'before_title'  => '',
			'after_title'   => '',
			'before_widget' => '',
			'after_widget'  => '',
		);

		ob_start();
		$this->instance->widget(
			$args,
			array(
				'count' => 5,
			)
		);
		$output = ob_get_clean();

		$this->assertNotFalse( strpos( $output, '<div class="list-group">' ) );
		$this->assertContains( 'class="list-group-item"', $output );
		$this->assertContains( $first_post->post_title, $output );
		$this->assertContains( $second_post->post_title, $output );
		$this->assertNotContains( '<ul', $output );
		$this->assertNotContains( '<span class="post-date label label-primary pull-right', $output );

		// Test for when the 'Display post date?' checkbox is checked.
		ob_start();
		$this->instance->widget(
			$args,
			array(
				'show_date' => true,
				'count'     => 5,
			)
		);
		$output = ob_get_clean();

		$this->assertContains( '<span class="post-date label label-primary pull-right', $output );
		$this->assertNotFalse( strpos( $output, '<div class="list-group">' ) );
		$this->assertContains( $first_post->post_title, $output );
		$this->assertContains( $second_post->post_title, $output );
	}

}
