<?php
/**
 * Tests for class BWS_Widget_Recent_Comments.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class BWS_Widget_Recent_Comments.
 */
class Test_BWS_Widget_Recent_Comments extends \WP_UnitTestCase {

	/**
	 * Instance of BWS_Widget_Recent_Comments.
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
		$this->instance = new BWS_Widget_Recent_Comments();
	}

	/**
	 * Test widget().
	 *
	 * @covers BWS_Widget_Recent_Comments::widget().
	 */
	public function test_widget() {
		$post_id = $this->factory()->post->create();
		$author  = 'Fred';
		$count   = 5;
		for ( $i = 0; $i < $count; $i++ ) {
			wp_insert_comment( array(
				'comment_post_ID' => $post_id,
				'comment_author'  => $author,
			) );
		}
		ob_start();
		$this->instance->widget(
			array(
				'before_title'  => '',
				'after_title'   => '',
				'before_widget' => '<section>',
				'after_widget'  => '</section>',
			),
			array(
				'count' => 1,
			)
		);
		$output   = ob_get_clean();
		$comments = get_comments();
		$comment  = array_shift( $comments );
		$this->assertContains( '<div class="list-group">', $output );
		$this->assertContains( 'class="list-group-item"', $output );
		$this->assertContains( get_comment_link( $comment ), $output );
		$this->assertContains( get_the_title( $comment->comment_post_ID ), $output );
		$this->assertNotContains( '<ul', $output );
	}

}
