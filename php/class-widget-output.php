<?php
/**
 * Widget output class.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Widget_Output class.
 */
class Widget_Output {

	/**
	 * Plugin instance
	 *
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Widget_Output constructor.
	 *
	 * @param Plugin $plugin Instance of the plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Add the widget output filters.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'get_search_form', array( $this, 'search_form' ) );
		add_filter( 'wp_tag_cloud', array( $this, 'tag_cloud' ) );
		add_action( 'widgets_init', array( $this, 'load_widget_files' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Filters the markup of the search form.
	 *
	 * @param string $form The markup of the search form.
	 * @return string $form The filtered markup of the search form.
	 */
	public function search_form( $form ) {
		if ( ! $this->plugin->components->setting->is_disabled( 'search' ) ) {
			return \BWS_Search_Widget::filter( $form );
		}
		return $form;
	}

	/**
	 * Filters the tag cloud markup.
	 *
	 * @param string $markup The tag cloud markup.
	 * @return string $markup The filtered tag cloud markup.
	 */
	public function tag_cloud( $markup ) {
		return preg_replace( '/(<a[^>]+?>)([^<]+?)(<\/a>)/', "$1<span class='label label-primary'>$2</span>$3", $markup );
	}

	/**
	 * Gets the markup in Bootstrap format.
	 *
	 * @param string $markup The HTML to reformat.
	 * @return string $markup The reformatted markup of the widget.
	 */
	public function reformat( $markup ) {
		$markup = preg_replace( '/<\/?ul[^>]*>/', '', $markup );
		$markup = preg_replace( '/<\/?li[^>]*>/', '', $markup );
		$markup = preg_replace( '/\((\d{1,3})\)/', "<span class='badge pull-right'>$1</span>", $markup );
		$markup = str_replace( '<a', '<a class="list-group-item"', $markup );
		$markup = preg_replace( '/(<\/a>).*?(<span.+?<\/span>)/', '$2$1', $markup );
		return sprintf( '<div class="list-group">%s</div>', $markup );
	}

	/**
	 * Loads the subclass widgets, based on whether their parent classes are present.
	 *
	 * Separate from Plugin::load_file() because this checks whether the parent class exists.
	 * The class files are included later than Plugin::init(), with the core function wp_maybe_load_widgets().
	 *
	 * @return void
	 */
	public function load_widget_files() {
		foreach ( $this->plugin->widgets as $widget ) {
			$core_widget     = 'WP_' . ucwords( str_replace( '-', '_', $widget ), '_' );
			$new_widget_file = __DIR__ . "/widgets/class-bws-{$widget}.php";
			if ( class_exists( $core_widget ) && file_exists( $new_widget_file ) ) {
				include_once $new_widget_file;
			}
		}
	}

	/**
	 * Unregisters the native WP widgets, and registers subclasses that output Bootstrap-formatted markup.
	 *
	 * @return void
	 */
	public function register_widgets() {
		foreach ( $this->plugin->widgets as $widget ) {
			if ( ! $this->plugin->components->setting->is_disabled( $widget ) ) {
				$uppercase_widget = ucwords( str_replace( '-', '_', $widget ), '_' );
				$new_widget       = __NAMESPACE__ . '\BWS_' . $uppercase_widget;
				if ( class_exists( $new_widget ) ) {
					unregister_widget( 'WP_' . $uppercase_widget );
					register_widget( $new_widget );
				}
			}
		}
	}

}
