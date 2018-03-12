<?php
/**
 * BWS_Widget_Pages
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * BWS_Widget_Pages class.
 */
class BWS_Widget_Pages extends \WP_Widget_Pages {

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
