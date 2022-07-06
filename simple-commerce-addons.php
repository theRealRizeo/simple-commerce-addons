<?php
/**
 * Plugin Name:          Simple Commerce Addons
 * Plugin URI:           https://wordpress.org/plugins/simple-commerce-addons/
 * Description:          Simple WooCommerce Addons to enhance your customer experienc.
 * Version:              1.0.0
 * Requires at least:    4.6
 * Tested up to:         6.0
 * WC requires at least: 5.0
 * WC tested up to:      6.5
 * Author:               rixeo
 * Author URI:           https://www.hubloy.com
 * License:              GPLv2
 * License URI:          https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:          simple-commerce-addons
 * Domain Path:          /languages/
 *
 * @package HubloyMembership
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SIMPLE_WOO_ADDONS_VERSION', '1.0.0' );
define( 'SIMPLE_WOO_ADDONS_MIN_PHP_VER', '5.6' );
define( 'SIMPLE_WOO_ADDONS_MIN_WC_VER', '5.0' );
define( 'SIMPLE_WOO_ADDONS_MAIN_FILE', __FILE__ );
define( 'SIMPLE_WOO_ADDONS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'SIMPLE_WOO_ADDONS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define autoloader for plugin classes.
spl_autoload_register(
	function ( $class ) {
		// Skip any classes outside this namespace.
		if ( false === strpos( $class, __NAMESPACE__ ) ) {
			return false;
		}

		// Assume WP coding standards for class naming - class Some_Class would go in class-some-class.php.
		$class_name = strtolower( str_replace( array( '_', __NAMESPACE__ . '\\' ), array( '-', '' ), $class ) );
		$class_path = SIMPLE_WOO_ADDONS_PLUGIN_PATH . '/includes/';
		require_once $class_path . 'class-' . $class_name . '.php';

		return true;
	}
);

/**
 * Load plugin translations and initiate classes if in supported configuration.
 *
 * @return void
 */
function bootstrap() {
	if ( Compatibility::is_valid_environment() ) {
		new Integration();
	}
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\bootstrap' );

