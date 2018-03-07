<?php
/**
 * Tests for class Bootstrap_Markup.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class Bootstrap_Markup.
 */
class Test_Bootstrap_Markup extends \WP_UnitTestCase {

	/**
	 * Instance of Bootstrap_Markup.
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
		$this->instance = $plugin->components->bootstrap_markup;
	}

	/**
	 * Test reformat().
	 *
	 * @covers Bootstrap_Markup::reformat()
	 */
	public function test_reformat() {}

	/**
	 * Test maybe_remove_ul().
	 *
	 * @covers Bootstrap_Markup::maybe_remove_ul()
	 */
	public function test_maybe_remove_ul() {
		$li_element = '<li></li>';
		$simple_ul  = sprintf( '<ul>%s</ul>', $li_element );
		$this->assertEquals( $simple_ul, $this->instance->maybe_remove_ul( $simple_ul ) );
		$this->instance->type_of_filter = 'pages';
		$this->assertEquals( $li_element, $this->instance->maybe_remove_ul( $simple_ul ) );
		$ul_with_classes = sprintf( '<ul class="foo-class">%s</ul>', $li_element );
		$this->assertEquals( $li_element, $this->instance->maybe_remove_ul( $ul_with_classes ) );
	}

	/**
	 * Test maybe_close_ul().
	 *
	 * @covers Bootstrap_Markup::maybe_close_ul()
	 */
	public function test_maybe_close_ul() {
		$original_markup = '<article><span>Test Text</span></article>';
		$this->assertEquals( '</ul><div class="list-group">' . $original_markup, $this->instance->maybe_close_ul( $original_markup ) );
		$widget_type                    = 'categories';
		$this->instance->type_of_filter = $widget_type;
		Bootstrap_Markup::$types_of_widgets_called[ $widget_type ] = true;
		$this->assertEquals( $original_markup, $this->instance->maybe_close_ul( $original_markup ) );
	}

	/**
	 * Test badge_number().
	 *
	 * @covers Bootstrap_Markup::badge_number()
	 */
	public function test_badge_number() {
		$number = '3';
		$badge  = "<span class='badge pull-right'>%s</span>";

		// Without parentheses around the number, this shouldn't filter the markup.
		$this->assertNotContains( sprintf( $badge, $number ), $this->instance->badge_number( sprintf( '<div>%s</div>', $number ) ) );
		$this->assertContains( sprintf( $badge, $number ), $this->instance->badge_number( sprintf( '<div>(%s)</div>', $number ) ) );
	}

}
