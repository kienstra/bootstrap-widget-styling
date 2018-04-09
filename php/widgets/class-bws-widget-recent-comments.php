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
		$dom    = new \DOMDocument();
		$dom->loadHTML( $output );
		$list_group = '<div class="list-group">';
		foreach ( $dom->getElementsByTagName( 'li' ) as $li ) {
			$anchor = $li->getElementsByTagName( 'a' );
			if ( $anchor->length > 0 ) {
				$list_group .= sprintf( '<a href="%s" class="list-group-item">%s</a>', esc_url( $anchor->item( $anchor->length - 1 )->attributes->getNamedItem( 'href' )->nodeValue ), esc_html( $li->textContent ) ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			}
		}
		$list_group .= '</div>';
		echo preg_replace( '/<ul[\s\S]*<\/ul>/', $list_group, $output ); // WPCS: XSS ok.
	}

}
