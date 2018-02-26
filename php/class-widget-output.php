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
	 * Add the actions.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( $this, 'add_filters' ) );
	}

	/**
	 * Adds the filters for the widgets.
	 *
	 * For the widgets in the property $widgets.
	 * Based on whether the filters are disabled in the settings.
	 *
	 * @return void
	 */
	public function add_filters() {
		foreach ( $this->widgets as $widget ) {
			if ( ! $this->plugin->components->setting->is_disabled( $widget ) ) {
				if ( 'archives' === $widget ) {
					add_filter( 'get_archives_link', array( 'BWS_Archives', 'filter' ) );
					add_filter( 'dynamic_sidebar_params', array( $this, 'add_closing_div' ) );
				} else {
					add_filter( 'wp_list_' . $widget, array( 'BWS_' . ucwords( $widget ), 'filter' ) );
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

}
