<?php
/**
 * BWS_Widget_Recent_Posts
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * BWS_Widget_Recent_Posts class.
 */
class BWS_Widget_Recent_Posts extends \WP_Widget_Recent_Posts {

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
			if ( $anchor->length > 0 ) {
				$initial_post_date = $li->getElementsByTagName( 'span' )->item( 0 ); // phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
				$post_date_text    = ( isset( $initial_post_date ) && property_exists( $initial_post_date, 'textContent' ) ) ? $initial_post_date->textContent : '';
				$post_date_element = ! empty( $post_date_text ) ? sprintf( '<span class="post-date label label-primary pull-right">%s</span>', esc_html( $post_date_text ) ) : '';
				$post_title        = str_replace( $post_date_text, '', $li->textContent );
				if ( ! empty( $anchor->item( $anchor->length - 1 )->attributes->getNamedItem( 'href' )->nodeValue ) ) {
					$list_group .= sprintf(
						'<a href="%s" class="list-group-item">%s %s</a>',
						esc_url( $anchor->item( $anchor->length - 1 )->attributes->getNamedItem( 'href' )->nodeValue ),  // phpcs:enable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
						esc_html( $post_title ),
						wp_kses_post( $post_date_element )
					);
				}
			}
		}

		$list_group .= '</div>';
		echo preg_replace( '/<ul[\s\S]*\/ul>/', $list_group, $output ); // WPCS: XSS ok.
	}
}
