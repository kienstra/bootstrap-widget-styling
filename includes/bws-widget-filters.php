<?php

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
