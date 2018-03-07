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
	 * Widgets to filter.
	 *
	 * @var array
	 */
	public $widgets = array(
		'categories',
		'pages',
		'archives',
	);

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
		add_filter( 'wp_nav_menu_items', array( $this, 'menu_widget' ) );

		foreach ( $this->widgets as $widget ) {
			if ( ! $this->plugin->components->setting->is_disabled( $widget ) ) {
				if ( 'archives' === $widget ) {
					add_filter( 'get_archives_link', function( $markup ) {
						return \BWS_Filter::reformat( $markup, 'archives' );
					} );
					add_filter( 'dynamic_sidebar_params', array( $this, 'add_closing_div' ) );
				} else {
					add_filter( 'wp_list_' . $widget, function( $markup ) use ( $widget ) {
						return \BWS_Filter::reformat( $markup, $widget );
					} );
				}
			}
		}
	}

	/**
	 * Adds a closing </div> to the 'Archives' widget.
	 *
	 * @param array $params The parameters for the widget output callback.
	 * @return array $params The filtered parameters.
	 */
	public function add_closing_div( $params ) {
		if ( isset( $params[0]['widget_name'] ) && ( 'Archives' === $params[0]['widget_name'] ) ) {
			$params[0]['after_widget'] = '</div>' . $params[0]['after_widget'];
		}
		return $params;
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
	 * Filters the tag cloud markup.
	 *
	 * @param string $markup The menu cloud markup.
	 * @return string $markup The filtered menu markup.
	 */
	public function menu_widget( $markup ) {
		return $this->plugin->components->bootstrap_markup->reformat( $markup, 'menu' );
	}

}
