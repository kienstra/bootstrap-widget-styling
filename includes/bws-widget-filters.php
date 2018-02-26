<?php

function bws_add_closing_div_to_archives_widget( $params ) {
	if ( isset( $params[ 0 ][ 'widget_name' ] ) && 'Archives' == $params[ 0 ][ 'widget_name' ] ) {
		$params[ 0 ][ 'after_widget' ] = '</div>' . $params[ 0 ][ 'after_widget' ] ;
	}
	return $params ;
}

// Filter search form widget
add_action( 'init', 'bws_add_search_form_filter_if_option_allows' );
function bws_add_search_form_filter_if_option_allows() {
	$options = get_option( 'bws_plugin_options' );
	if ( ( isset( $options[ 'disable_search_widget' ] ) ) && ( '1' === $options[ 'disable_search_widget' ] ) ) {
		return;
	} else {
		add_filter( 'get_search_form', array( 'BWS_Search_Widget', 'filter' ) , '1' );
	}
}


// Filter tag cloud widget
add_filter( 'wp_tag_cloud', 'bwp_filter_tag_cloud' );
function bwp_filter_tag_cloud( $markup ) {
	$regex = '/(<a[^>]+?>)([^<]+?)(<\/a>)/';
	$replace_with = "$1<span class='label label-primary'>$2</span>$3" ;
	$markup = preg_replace( $regex , $replace_with , $markup );
	return $markup ;
}

// Filter menu widget
add_filter( 'widget_display_callback', 'bws_search_for_menu_widget', 4 , 10 );
function bws_search_for_menu_widget( $instance , $widget , $args ) {
	if ( ( isset( $widget->widget_options[ 'classname' ] ) ) && ( 'widget_nav_menu' == $widget->widget_options[ 'classname' ] ) ) {
		add_filter( 'wp_nav_menu_items', 'bws_filter_widget_menu', 2 , 10 );
	}
	return $instance ;
}

function bws_filter_widget_menu( $menu_markup , $args ) {
	if ( ( ! isset( $args->fallback_cb ) ) || ( "" == $args->fallback_cb ) ) {
		$menu_markup = BWS_Menu::filter( $menu_markup );
	}
	return $menu_markup ;
}

// If there' a "Recent Posts" widget, enqueue bws-change-markup.js
add_filter( 'widget_posts_args', 'bws_posts_enqueue_javascript' );
function bws_posts_enqueue_javascript( $args ) {
	wp_enqueue_script( BootstrapWidgetStyling\Plugin::VERSION . '-script', plugins_url( '/' . BootstrapWidgetStyling\Plugin::VERSION . '/js/bws-change-markup.js' ), array( 'jquery' ) );
	return $args ;
}

// If there' a "Meta" widget, enqueue bws-change-markup.js
add_filter( 'widget_meta_poweredby', 'bws_meta_enqueue_javascript' );
function bws_meta_enqueue_javascript( $args ) {
	wp_enqueue_script( BootstrapWidgetStyling\Plugin::VERSION . '-script', plugins_url( '/' . BootstrapWidgetStyling\Plugin::VERSION . '/js/bws-change-markup.js' ), array( 'jquery' ) );
	return $args ;
}

// If there' a "Comments" widget, enqueue bws-change-markup.js
add_filter( 'widget_comments_args', 'bws_comments_enqueue_javascript' );
function bws_comments_enqueue_javascript( $args ) {
	wp_enqueue_script( BootstrapWidgetStyling\Plugin::VERSION . '-script', plugins_url( '/' . BootstrapWidgetStyling\Plugin::VERSION . '/js/bws-change-markup.js' ), array( 'jquery' ) );
	return $args ;
}
