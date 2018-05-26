<?php
/**
 * BWS_Nav_Menu_Widget
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * BWS_Nav_Menu_Widget class.
 */
class BWS_Widget_Categories extends \WP_Widget_Categories {

	/**
	 * Gets the markup in Bootstrap format.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance The widget instance.
	 */
	public function widget( $args, $instance ) {
		ob_start();
		parent::widget( $args, $instance );
		$output = ob_get_clean();
		$plugin = Plugin::get_instance();
		echo $plugin->components->widget_output->reformat( $output ); // WPCS: XSS ok.
	}

}
