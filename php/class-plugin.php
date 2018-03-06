<?php
/**
 * Main class for the Bootstrap Widget Styling plugin
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Main plugin class
 */
class Plugin {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.3';

	/**
	 * Plugin slug.
	 *
	 * @const string
	 */
	const SLUG = 'bootstrap-widget-styling';

	/**
	 * Plugin instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * URL of the plugin.
	 *
	 * @var object
	 */
	public $location;

	/**
	 * Instantiated plugin classes.
	 *
	 * @var object
	 */
	public $components;

	/**
	 * The PHP classes.
	 *
	 * @var array
	 */
	public $classes = array(
		'setting',
		'widget-output',
	);

	/**
	 * Get the instance of this plugin
	 *
	 * @return object $instance Plugin instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance instanceof Plugin ) {
			self::$instance = new Plugin();
		}

		return self::$instance;
	}

	/**
	 * Init the plugin.
	 *
	 * Load the files, instantiate the classes, and call their init() methods.
	 * And register the main plugin actions.
	 *
	 * @return void
	 */
	public function init() {
		$this->load_files();
		$this->init_classes();
		$this->location = plugins_url( self::SLUG );
		$this->add_actions();
	}

	/**
	 * Load the plugin files.
	 *
	 * @return void
	 */
	public function load_files() {
		$files = array(
			'bws-widget-filters',
			'class-bws-filter',
			'class-bws-search-widget',
		);
		foreach ( $files as $file ) {
			include_once dirname( plugin_dir_path( __FILE__ ) ) . "/includes/{$file}.php";
		}

		foreach ( $this->classes as $class ) {
			include_once __DIR__ . "/class-{$class}.php";
		}
	}

	/**
	 * Instantiate the plugin classes, and call their init() methods.
	 *
	 * @return void
	 */
	public function init_classes() {
		$this->components                = new \stdClass();
		$this->components->setting       = new Setting( $this );
		$this->components->widget_output = new Widget_Output( $this );
		$this->components->search_form   = new \BWS_Search_Widget( $this );
		$this->components->setting->init();
		$this->components->widget_output->init();
	}

	/**
	 * Add the plugin actions.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'init', array( $this, 'textdomain' ) );
	}

	/**
	 * Load the plugin's textdomain.
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( self::SLUG );
	}
}
