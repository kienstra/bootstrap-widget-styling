<?php
/**
 * Tests for class Widget_Output.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Tests for class Widget_Output.
 */
class Test_Widget_Output extends \WP_UnitTestCase {

	/**
	 * Instance of Widget_Output.
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
		$plugin = Plugin::get_instance();
		$plugin->init();
		$this->instance = $plugin->components->widget_output;
	}

	/**
	 * Test init().
	 *
	 * @covers Widget_Output::init()
	 */
	public function test_init() {
		$this->instance->init();
		$this->assertEquals( 10, has_filter( 'wp_tag_cloud', array( $this->instance, 'tag_cloud' ) ) );
		$this->assertEquals( 10, has_action( 'widgets_init', array( $this->instance, 'load_widget_files' ) ) );
		$this->assertEquals( 10, has_action( 'widgets_init', array( $this->instance, 'register_widgets' ) ) );
	}

	/**
	 * Test tag_cloud().
	 *
	 * @covers Widget_Output::tag_cloud()
	 */
	public function test_tag_cloud() {
		$first_tag  = $this->factory()->tag->create();
		$second_tag = $this->factory()->tag->create();
		$post       = $this->factory()->post->create();
		wp_set_post_tags( $post, array( $first_tag, $second_tag ) );

		remove_filter( 'wp_tag_cloud', array( $this->instance, 'tag_cloud' ) );
		$tag_cloud       = wp_tag_cloud( array(
			'echo' => false,
		) );
		$filtered_markup = $this->instance->tag_cloud( $tag_cloud );
		$this->assertContains( "<span class='label label-primary'>", $filtered_markup );
		$this->assertContains( get_tag_link( $first_tag ), $filtered_markup );
		$this->assertContains( get_tag_link( $second_tag ), $filtered_markup );
	}

	/**
	 * Test reformat().
	 *
	 * @covers Widget_Output::reformat()
	 */
	public function test_reformat() {
		remove_all_filters( 'wp_list_categories' );
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
		$list         = $this->instance->reformat( wp_list_categories( array(
			'echo'       => false,
			'show_count' => 1,
		) ) );
		$not_expected = array(
			'</ul>',
			'<li>',
			'</li>',
		);
		foreach ( $not_expected as $tag ) {
			$this->assertNotContains( $tag, $list );
		}
		$this->assertEquals( 0, strpos( $list, '<div class="list-group">' ) );
		$this->assertContains( sprintf( "<span class='badge pull-right'>%s</span>", $count ), $list );
		$this->assertContains( '<a class="list-group-item"', $list );
	}

	/**
	 * Test reformat_dom_document().
	 *
	 * Uses markup copied from WP_Widget_Meta.
	 *
	 * @covers Widget_Output::reformat_dom_document()
	 */
	public function test_reformat_dom_document() {
		ob_start();
		?>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<li><a href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"><?php esc_html_e( 'Entries <abbr title="Really Simple Syndication">RSS</abbr>' ); ?></a></li>
			<li><a href="<?php echo esc_url( get_bloginfo( 'comments_rss2_url' ) ); ?>"><?php esc_html_e( 'Comments <abbr title="Really Simple Syndication">RSS</abbr>' ); ?></a></li>
		</ul>
		<?php
		$markup = $this->instance->reformat_dom_document( ob_get_clean() );

		$this->assertEquals( 0, strpos( $markup, '<div class="list-group">' ) );
		$this->assertContains( 'class="list-group-item"', $markup );
		$this->assertContains( 'Entries RSS', $markup );
		$this->assertContains( 'Comments RSS', $markup );
	}

	/**
	 * Test load_widget_files().
	 *
	 * @covers Widget_Output::load_widget_files()
	 */
	public function test_load_widget_files() {
		$this->instance->load_widget_files();
		foreach ( $this->instance->plugin->widgets as $widget ) {
			$this->assertTrue( class_exists( __NAMESPACE__ . '\BWS_' . ucwords( str_replace( '-', '_', $widget ), '_' ) ) );
		}
	}

	/**
	 * Test register_widgets().
	 *
	 * @covers Widget_Output::register_widgets()
	 */
	public function test_register_widgets() {
		global $wp_widget_factory;
		$this->instance->register_widgets();
		foreach ( $this->instance->plugin->widgets as $widget ) {
			$widget_key = __NAMESPACE__ . '\BWS_' . ucwords( str_replace( '-', '_', $widget ), '_' );
			$this->assertTrue( isset( $wp_widget_factory->widgets[ $widget_key ] ) );
		}
	}

}
