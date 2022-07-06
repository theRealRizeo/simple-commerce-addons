<?php
/**
 * Version and plugin compatibility checking.
 *
 * @package SimpleWooAddons;
 */

namespace SimpleWooAddons;

defined( 'ABSPATH' ) || exit;

/**
 * Compatibility class.
 *
 * Handles checking for required environment factors, and any compatibility fixes for WooCommerce or other plugins.
 */
class Compatibility {

	/**
	 * Check if WooCommerce is installed and in a supported version.
	 *
	 * Optionally displays admin notices if not.
	 *
	 * @param bool $skip_notices Whether to skip adding admin notices (even if the environment is not valid).
	 *
	 * @return bool
	 */
	public static function is_valid_environment( $skip_notices = false ) {
		if ( ! class_exists( 'WooCommerce' ) || ! defined( 'WC_VERSION' ) ) {
			if ( ! $skip_notices ) {
				add_action( 'admin_notices', __NAMESPACE__ . '\Compatibility::woocommerce_missing_notice' );
			}

			return false;
		} elseif ( version_compare( WC_VERSION, SIMPLE_WOO_ADDONS_MIN_WC_VER, '<' ) ) {
			if ( ! $skip_notices ) {
				add_action( 'admin_notices', __NAMESPACE__ . '\Compatibility::woocommerce_unsupported_notice' );
			}

			return false;
		}

		return true;
	}

	/**
	 * Render admin notice for inactive/uninstalled WooCommerce.
	 */
	public static function woocommerce_missing_notice() {
		$message = sprintf(
			/* translators: Link to WooCommerce. */
			esc_html__( 'Simple Commerce Addons requires WooCommerce to be installed and active. You can download %s here.', 'simple-commerce-addons' ),
			'<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>'
		);

		echo '<div class="error"><p><strong>' . $message . '</strong></p></div>'; // phpcs:ignore -- escaped above.
	}

	/**
	 * Render admin notice for unsupported version of WooCommerce.
	 */
	public static function woocommerce_unsupported_notice() {
		/* translators: $1. Minimum WooCommerce version. $2. Current WooCommerce version. */
		$template = __( 'Simple Commerce Addons requires WooCommerce %1$s or greater to be installed and active. WooCommerce %2$s is no longer supported.', 'simple-commerce-addons' );
		$message  = sprintf( $template, SIMPLE_WOO_ADDONS_MIN_WC_VER, defined( 'WC_VERSION' ) ? WC_VERSION : '?' );
		echo '<div class="error"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
	}

}

