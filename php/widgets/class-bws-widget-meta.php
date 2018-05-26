<?php
/**
 * BWS_Widget_Meta
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * BWS_Widget_Meta class.
 */
class BWS_Widget_Meta extends \WP_Widget_Meta {

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

		/**
		 * DOMDocument::loadHTML() raises an error in parsing some HTML5 elements, like <aside> and <section>.
		 *
		 * Themes can add HTML5 elements via $args['before_widget'] and $args['before_widget'].
		 * So this removes them from the markup that DOMDocument::loadHTML() parses.
		 * The 'before_widget' and 'after_widget' markup will still be part of the final widget markup in the echo statement.
		 */
		$markup_to_search = str_replace(
			array( $args['before_widget'], $args['after_widget'] ),
			'',
			$output
		);
		$plugin           = Plugin::get_instance();
		$list_group       = $plugin->components->widget_output->reformat_dom_document( $markup_to_search );
		echo preg_replace( '/<ul[\s\S]*<\/ul>/', $list_group, $output ); // WPCS: XSS ok.
	}

}
