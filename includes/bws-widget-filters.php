<?php


// Maybe filter 'categories' , 'pages' , and 'archives' widgets
add_action( 'plugins_loaded' , 'bws_widget_filters' ) ;
function bws_widget_filters() { 
  bws_maybe_add_filters_of_types( array( 'categories' , 'pages' , 'archives' ) ) ;
}

function bws_maybe_add_filters_of_types( $types ) {
  foreach( $types as $type ) { 
    if ( bws_options_allow_adding_filter_for_widget_type( $type ) ) {
      bws_add_filter_for_widget_type( $type ) ;
    }
  }
}

function bws_options_allow_adding_filter_for_widget_type( $type_of_widget ) {
  $options = get_option( 'bws_plugin_options' ) ;
  $widget_key = 'disable_' . $type_of_widget . '_widget' ; 
  if ( ( isset( $options[ $widget_key ] ) ) && ( '1' === $options[ $widget_key ] ) ) {
    return false ; 
  }
  return true ;
}

function bws_add_filter_for_widget_type( $type ) {
  if ( 'archives' === $type ) {
    add_filter( 'get_archives_link' , array( 'BWS_Filter' , 'filter_html_archives' ) ) ;
  }
  else {
    add_filter( 'wp_list_' . $type , array( 'BWS_' . $type , 'filter' ) ) ; 
  }
}

add_filter( 'dynamic_sidebar_params' , 'bws_params_dynamic_sidebar' ) ;
function bws_params_dynamic_sidebar( $params ) {
  if ( isset( $params[ 0 ][ 'widget_name' ] ) && 'Archives' == $params[ 0 ][ 'widget_name' ] ) {
    $params[ 0 ][ 'after_widget' ] = '</div>' . $params[ 0 ][ 'after_widget' ] ; 
  }
  return $params ; 
} 

// if there' a "Recent Posts" widget, enqueue bws-change-markup.js 
add_filter( 'widget_posts_args' , 'bws_posts_enqueue_javascript' ) ; 
function bws_posts_enqueue_javascript( $args ) {
  wp_enqueue_script( BWS_PLUGIN_SLUG . '-script' , plugins_url( '/' . BWS_PLUGIN_SLUG . '/js/bws-change-markup.js' ) , array( 'jquery' ) ) ;  
  return $args ; 
}

// if there' a "Meta" widget, enqueue bws-change-markup.js 
add_filter( 'widget_meta_poweredby' , 'bws_meta_enqueue_javascript' ) ;
function bws_meta_enqueue_javascript( $args ) {
  wp_enqueue_script( BWS_PLUGIN_SLUG . '-script' , plugins_url( '/' . BWS_PLUGIN_SLUG . '/js/bws-change-markup.js' ) , array( 'jquery' ) ) ;  
  return $args ; 
}

// if there' a "Comments" widget, enqueue bws-change-markup.js 
add_filter( 'widget_comments_args' , 'bws_comments_enqueue_javascript' ) ;
function bws_comments_enqueue_javascript( $args ) {
  wp_enqueue_script( BWS_PLUGIN_SLUG . '-script' , plugins_url( '/' . BWS_PLUGIN_SLUG . '/js/bws-change-markup.js' ) , array( 'jquery' ) ) ;  
  return $args ; 
}

add_action( 'plugins_loaded' , 'bws_add_search_form_filter_if_option_allows' ) ; 
function bws_add_search_form_filter_if_option_allows() {
  $options = get_option( 'bws_plugin_options' ) ;
  if ( ( isset( $options[ 'disable_search_widget' ] ) ) && ( '1' === $options[ 'disable_search_widget' ] ) ) {
    return ;
  } else {
    add_filter( 'get_search_form' , array( 'BWS_Search_Widget' , 'filter' ) , '1' ) ;
  }
}





function bws_search_filter( $markup ) {
  if ( ! bws_theme_has_a_search_template() ) {
    $markup = bws_filter_search_form_markup( $markup ) ;
  }
  return $markup ; 
}

function bws_theme_has_a_search_template() {
  $template = locate_template( 'searchform.php' ) ;
  return ( '' != $template ) ; 
}

function bws_filter_search_form_markup( $markup ) { 
  $markup = bws_add_input_group_class_to_opening_div( $markup ) ;
  $markup = bws_remove_label( $markup ) ;
  $markup = bws_add_form_control_class_to_text_input( $markup ) ;
  $markup = bws_add_class_to_submit_button( $markup ) ; 
  $markup = bws_wrap_submit_button_in_div( $markup ) ;
  return $markup ;
}

function bws_add_input_group_class_to_opening_div( $html ) {
  $filtered_html = str_replace( '<div>' , '<div class="input-group">' , $html ) ;
  return $filtered_html ;
}

function bws_remove_label( $html ) {
  $filtered_html = preg_replace( '/<label.*?<\/label>/' , '' , $html ) ;
  return $filtered_html ;
}

function bws_add_form_control_class_to_text_input( $html ) {
  $filtered_html = str_replace( '<input type="text"' , '<input type="text" class="form-control"' , $html ) ;
  return $filtered_html ;
}

function bws_add_class_to_submit_button( $html ) {
  $class = apply_filters( 'bws_class_of_search_widget_submit_button' , 'btn btn-primary btn-med' ) ; 
  $filtered_html = str_replace( "<input type='submit'" , "<input type='submit' class='$class'" , $html ) ;
  return $filtered_html ;
}

function bws_wrap_submit_button_in_div( $html ) {
  $filtered_html = preg_replace( '/(<input type="submit".*?>)/' , '<div class="input-group-btn">$1</div>' , $html ) ;
  return $filtered_html ;
}


// Filter tag cloud widget
add_filter( 'wp_tag_cloud' , 'bwp_filter_tag_cloud' ) ; 
function bwp_filter_tag_cloud( $markup ) {
  $regex = '/(<a[^>]+?>)([^<]+?)(<\/a>)/' ;
  $replace_with = "$1<span class='label label-primary'>$2</span>$3" ;
  $markup = preg_replace( $regex , $replace_with , $markup ) ; 
  return $markup ;
}

// Filter menu widget
add_filter( 'widget_display_callback' , 'bws_search_for_menu_widget' , 4 , 10 ) ; 
function bws_search_for_menu_widget( $instance , $widget , $args ) {
  if ( ( isset( $widget->widget_options[ 'classname' ] ) ) && ( 'widget_nav_menu' == $widget->widget_options[ 'classname' ] ) ) {
    add_filter( 'wp_nav_menu_items' , 'bws_filter_widget_menu' , 2 , 10 ) ;
  }
  return $instance ;  
}

function bws_filter_widget_menu( $menu_markup , $args ) {
  if ( ( ! isset( $args->fallback_cb ) ) || ( "" == $args->fallback_cb ) ) {
    $menu_markup = BWS_Menu::filter( $menu_markup ) ;  
  }
  return $menu_markup ; 
}