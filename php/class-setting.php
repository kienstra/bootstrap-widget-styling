<?php
/**
 * Widget setting class.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Setting class.
 */
class Setting {

	/**
	 * Name of the main plugin options.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'bws_plugin_options';

	/**
	 * Slug of the settings submenu.
	 *
	 * @var string
	 */
	const SUBMENU_SLUG = 'bws_options_page';

	/**
	 * Settings section name.
	 *
	 * @var string
	 */
	const SETTINGS_SECTION = 'bws_plugin_primary';

	/**
	 * The settings key to disable the search widget.
	 *
	 * @var string
	 */
	const DISABLE_SEARCH_WIDGET = 'disable_search_widget';

	/**
	 * Value of the setting if the plugin is disabled.
	 *
	 * @var string
	 */
	const DISABLED_VALUE = '1';

	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * The widgets to display on the settings page.
	 *
	 * Users can disable this plugin from altering the markup of a widget
	 * by checking the box for it on the settings page.
	 *
	 * @var array
	 */
	public $widgets = array(
		'categories',
		'pages',
		'archives',
		'search',
	);

	/**
	 * Setting constructor.
	 *
	 * @param Plugin $plugin Instance of the plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Add the actions.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'options_page' ) );
		add_filter( 'admin_init', array( $this, 'register' ) );
		add_filter( 'admin_init', array( $this, 'add_settings_fields' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
	}

	/**
	 * Add the options submenu page.
	 *
	 * Under the 'Settings' section in /wp-admin.
	 *
	 * @return void
	 */
	public function options_page() {
		add_options_page(
			__( 'Bootstrap Widget Styling Settings', 'bootstrap-widget-styling' ),
			__( 'Widget Styling', 'bootstrap-widget-styling' ),
			'manage_options',
			self::SUBMENU_SLUG,
			array( $this, 'options_markup' )
		);
	}

	/**
	 * Echoes the markup for the options page.
	 *
	 * @return void
	 */
	public function options_markup() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Bootstrap Widget Styling', 'bootstrap-widget-styling' ); ?></h2>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_NAME );
				do_settings_sections( self::SUBMENU_SLUG );
				submit_button();
			?>
			</form>
		</div>
	<?php
	}

	/**
	 * Outputs the markup for the plugin section.
	 *
	 * @return void
	 */
	public function plugin_section() {
		?>
		<h3><?php esc_html_e( 'This plugin does not work well when the top navbar has a "Categories" or "Pages" widget.', 'bootstrap-widget-styling' ); ?></h3>
		<h3><em><?php esc_html_e( 'Disable', 'bootstrap-widget-styling' ); ?></em><?php esc_html_e( 'plugin for: ', 'bootstrap-widget-styling' ); ?></h3>
	<?php
	}

	/**
	 * Validates the plugin options.
	 *
	 * @param array $input The options to be validated.
	 * @return array $validated The validated options.
	 */
	public function validate_options( $input ) {
		$validated = array();
		foreach ( $this->widgets as $widget_name ) {
			$widget_key      = 'disable_' . $widget_name . '_widget';
			$disable_setting = isset( $input[ $widget_key ] ) ? $input[ $widget_key ] : null;
			if ( ( '0' === $disable_setting ) || ( self::DISABLED_VALUE === $disable_setting ) ) {
				$validated[ $widget_key ] = $disable_setting;
			}
		}
		return $validated;
	}

	/**
	 * Registers the settings.
	 *
	 * @return void
	 */
	public function register() {
		register_setting(
			self::OPTION_NAME,
			self::OPTION_NAME,
			array( $this, 'validate_options' )
		);
		add_settings_section(
			self::SETTINGS_SECTION,
			__( 'Settings', 'bootstrap-widget-styling' ),
			array( $this, 'section_text' ),
			self::SUBMENU_SLUG
		);
	}

	/**
	 * Adds the settings fields.
	 *
	 * @return void
	 */
	public function add_settings_fields() {
		foreach ( $this->widgets as $widget ) {
			$callback = function () use ( $widget ) {
				$plugin                 = Plugin::get_instance();
				$disable_widget_setting = $plugin->components->setting->is_disabled( $widget );
				$name                   = 'bws_plugin_options[disable_' . $widget . '_widget]';

				?>
				<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" <?php checked( $disable_widget_setting, Setting::DISABLED_VALUE, true ); ?> value="<?php echo esc_attr( Setting::DISABLED_VALUE ); ?>"/>
				<?php
			};
			// translators: %s: the name of the widget.
			add_settings_field( "bws_plugin_disable_{$widget}_widget", sprintf( esc_html__( '%s widget' ), ucwords( $widget ) ), $callback, self::SUBMENU_SLUG, self::SETTINGS_SECTION );
		}
	}

	/**
	 * Whether filtering the output of a widget is disabled.
	 *
	 * @param string $widget The widget to check.
	 * @return boolean
	 */
	public function is_disabled( $widget ) {
		$options = get_option( 'bws_plugin_options' );
		$widget  = str_replace( 'widget-', '', $widget );
		$key     = "disable_{$widget}_widget";
		return ( isset( $options[ $key ] ) && ( self::DISABLED_VALUE === $options[ $key ] ) );
	}

	/**
	 * Outputs the text for the section.
	 *
	 * @return void
	 */
	public function section_text() {
		?>
		<h3>
			<?php esc_html_e( 'This plugin does not work well when the top navbar has a "Categories" or "Pages" widget.', 'bootstrap-widget-styling' ); ?>
		</h3>
		<h3>
			<em><?php esc_html_e( 'Disable', 'bootstrap-widget-styling' ); ?></em>
				<?php esc_html_e( 'plugin for: ', 'bootstrap-widget-styling' ); ?>
		</h3>
	<?php
	}

	/**
	 * Add the options submenu page.
	 *
	 * Under the 'Settings' section in /wp-admin.
	 *
	 * @param array  $actions     Links for plugin actions.
	 * @param string $plugin_file The file of the plugin.
	 * @return array $actions Filtered plugin action.
	 */
	public function settings_link( $actions, $plugin_file ) {
		$this_plugin = Plugin::SLUG . '/' . Plugin::SLUG . '.php';
		if ( $plugin_file === $this_plugin ) {
			$url                 = admin_url( 'options-general.php?page=' . self::SUBMENU_SLUG );
			$actions['settings'] = sprintf( '<a href="%s">%s</a>', esc_attr( $url ), esc_html__( 'Settings', 'bootstrap-widget-styling' ) );
		}
		return $actions;
	}

}
