<?php
/**
 * Instantiates the Bootstrap Widget Styling plugin
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/*
Plugin Name: Bootstrap Widget Styling
Plugin URI: www.ryankienstra.com/bootstrap-widget-styling
Description: Make widgets mobile. Bigger click area and better styling for 9 default widgets. Only one small file sent to the browser. Disable this for selected widgets by clicking "Settings." Must have Bootstrap 3.
Version: 1.0.4
Author: Ryan Kienstra
Author URI: www.ryankienstra.com
License: GPL2
Requires PHP: 5.3

*/

require_once dirname( __FILE__ ) . '/php/class-plugin.php';
$plugin = Plugin::get_instance();
$plugin->init();
