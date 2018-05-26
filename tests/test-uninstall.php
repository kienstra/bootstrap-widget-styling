<?php
/**
 * Test for uninstall.php.
 *
 * @package BootstrapWidgetStyling
 */

namespace BootstrapWidgetStyling;

/**
 * Test for uninstall.php.
 */
class Test_Uninstall extends \WP_UnitTestCase {

	/**
	 * Test uninstall.php
	 *
	 * @see widget-live-editor.php
	 */
	public function test_settings_unistall() {
		update_option( Setting::OPTION_NAME, array( 'foo' => 'bar' ) );
		require_once dirname( __DIR__ ) . '/uninstall.php';
		$this->assertEmpty( get_option( Setting::OPTION_NAME ) );
	}

}
