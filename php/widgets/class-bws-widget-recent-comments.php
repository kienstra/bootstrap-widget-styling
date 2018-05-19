<?php
/**
 * BWS_Widget_Recent_Comments
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * BWS_Widget_Recent_Comments class.
 */
class BWS_Widget_Recent_Comments extends \WP_Widget_Recent_Comments {

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
		$dom              = new \DOMDocument();
		$dom->loadHTML( $markup_to_search );
		$list_group = '<div class="list-group">';
		foreach ( $dom->getElementsByTagName( 'li' ) as $li ) {
			$anchor = $li->getElementsByTagName( 'a' );
			if ( ! empty( $anchor->item( $anchor->length - 1 )->attributes->getNamedItem( 'href' )->nodeValue ) ) {
				$list_group .= sprintf( '<a href="%s" class="list-group-item">%s</a>', esc_url( $anchor->item( $anchor->length - 1 )->attributes->getNamedItem( 'href' )->nodeValue ), esc_html( $li->textContent ) ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			}
		}
		$list_group .= '</div>';
		echo preg_replace( '/<ul[\s\S]*<\/ul>/', $list_group, $output ); // WPCS: XSS ok.
	}

}
