<?php
/**
 * Bootstrap_Markup setting.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Bootstrap_Markup class.
 */
class Bootstrap_Markup {

	/**
	 * The instance of the plugin.
	 *
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * The types of widgets output.
	 *
	 * @var array
	 */
	public static $types_of_widgets_called = array();

	/**
	 * Bootstrap_Markup constructor.
	 *
	 * @param Plugin $plugin The instance of the plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

}
