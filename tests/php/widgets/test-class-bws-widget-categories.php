<?php
/**
 * Tests for class BWS_Widget_Categories.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Categories.
 */
class Test_BWS_Widget_Categories extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Categories.
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
		$this->instance = new BWS_Widget_Categories();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Categories::widget().
	 */
	public function test_widget() {
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
