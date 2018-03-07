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

	protected static $instance;

	/**
	 * The types of widgets output.
	 *
	 * @var array
	 */
	public static $types_of_widgets_called = array();

	public $markup;
	public $type_of_filter;

	/**
	 * Bootstrap_Markup constructor.
	 *
	 * @param Plugin $plugin The instance of the plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Gets the markup in Bootstrap format.
	 *
	 * @param string $html_to_filter The HTML to filter.
	 * @param string $type_of_filter The type of filter.
	 * @return mixed
	 */
	public function reformat( $html_to_filter, $type_of_filter ) {
		$this->markup         = $html_to_filter;
		$this->type_of_filter = $type_of_filter;
		$this->filter_markup( $html_to_filter );
		return $this->markup;
	}

	/**
	 * Filters the markup to use Bootstrap format.
	 *
	 * @param string $markup The markup to filter.
	 * @$return void
	 */
	public function filter_markup( $markup ) {
		$markup       = $this->maybe_remove_ul( $markup );
		$this->markup = $this->maybe_close_ul( $markup );
		$this->replace_parenthesized_number_with_badge_number();
		$this->remove_li_tags();
		$this->add_list_group_class_to_anchor_tags();
		$this->move_span_inside_anchor_closing_tag();
		$this->add_closing_div_depending_on_filter_type();
	}

	/**
	 * Removes the <ul> elements if this is for a 'pages' widget.
	 *
	 * @param string $markup The markup to reformat.
	 * @return string $markup The markup without <ul> tags if this is a 'Pages' widget.
	 */
	public function maybe_remove_ul( $markup ) {
		if ( 'pages' === $this->type_of_filter ) {
			return preg_replace( '/<[\/]?ul[^>]*>/', '', $markup );
		}
		return $markup;
	}

	/**
	 * Closes the <ul> if it's the first instance of the widget.
	 *
	 * @param string $markup The widget markup.
	 * @return string $markup The filtered widget markup.
	 */
	public function maybe_close_ul( $markup ) {
		if ( ! isset( self::$types_of_widgets_called[ $this->type_of_filter ] ) ) {
			self::$types_of_widgets_called[ $this->type_of_filter ] = true;
			return '</ul><div class="list-group">' . $markup;
		}
		return $markup;
	}

	function replace_parenthesized_number_with_badge_number() {
		$regex            = '/\((\d{1,3})\)/';
		$new_count_markup = "<span class='badge pull-right'>$1</span>";
		$this->markup     = preg_replace( $regex, $new_count_markup, $this->markup );
	}

	function remove_li_tags() {
		$regex        = '/<li.*?>/';
		$this->markup = preg_replace( $regex, '', $this->markup );
	}

	function add_list_group_class_to_anchor_tags() {
		$this->markup = str_replace( '<a' , '<a class="list-group-item"' , $this->markup );
	}

	function move_span_inside_anchor_closing_tag() {
		$new_markup = "$2$1";
		$regex = "/(<\/a>).*?(<span.+?<\/span>)/";
		$this->markup = preg_replace( $regex , $new_markup , $this->markup );
	}

	function add_closing_div_depending_on_filter_type() {
		if ( 'archives' !== $this->type_of_filter )	{
			$this->markup .= '</div>';
		}
	}
}
