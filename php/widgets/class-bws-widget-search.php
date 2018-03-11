<?php
/**
 * BWS_Widget_Search
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * BWS_Widget_Search class.
 */
class BWS_Widget_Search extends \WP_Widget_Search {

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

		$output = str_replace( '<div>', '<div class="input-group">', $output );
		$output = preg_replace( '/<label.*?<\/label>/', '', $output );
		$output = str_replace( '<input type="text"', '<input type="text" class="form-control"', $output );
		$output = str_replace( '<input type="submit"', '<input type="submit" class="btn btn-primary btn-med"', $output );
		$output = preg_replace( '/(<input type="submit"[^>]*>)/', '<div class="input-group-btn">$1</div>', $output );
		echo $output; // WPCS: XSS ok.
	}

}
