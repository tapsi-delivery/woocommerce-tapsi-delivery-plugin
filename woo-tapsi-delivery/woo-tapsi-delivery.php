<?php

/**
 * @link              https://www.inverseparadox.com
 * @since             1.0.0
 * @package           Woocommerce_Tapsi
 *
 * @wordpress-plugin
 * Plugin Name:       Tapsi Delivery
 * Plugin URI:        https://developer.tapsi.com/wordpress-plugin
 * Description:       Let Tapsi power your delivery. Use Tapsi as a shipping provider to offer local, scheduled delivery for your WooCommerce store. Configure multiple pickup locations, delivery hours, tip amounts, and more.
 * Version:           0.1.5
 * Author:            Tapsi
 * Author URI:        https://developer.tapsi.com/en-US/wordpress-plugin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-tapsi-delivery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'WOOCOMMERCE_TAPSI_VERSION', '0.1.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-tapsi-delivery-activator.php
 */
function activate_woocommerce_tapsi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-tapsi-delivery-activator.php';
	Woocommerce_Tapsi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-tapsi-delivery-deactivator.php
 */
function deactivate_woocommerce_tapsi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-tapsi-delivery-deactivator.php';
	Woocommerce_Tapsi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_tapsi' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_tapsi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-tapsi-delivery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 * @return Woocommerce_Tapsi Static instance of the plugin
 */
function WCDD() {
	// Only run the plugin if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		// If WooCommerce is not active, display a notice.
		add_action( 'admin_notices', function() {
			printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html__( 'Tapsi Delivery requires WooCommerce to be installed and active.', 'woo-tapsi-delivery' ) ); 
		} );
		return false;
	}

	// Retrieve static instance of plugin
	static $plugin;

	// If static instance isn't set, run the plugin
	if ( ! isset( $plugin ) ) {
		$plugin = new Woocommerce_Tapsi();
		$plugin->run();
	}

	// Return the Static Instance
	return $plugin;
}

// Load the plugin
add_action( 'plugins_loaded', 'WCDD' );

/**
 * Adds settings link to plugin listing
 *
 * @param array $links Links for plugin
 * @return array Filtered links
 */
function wcdd_add_action_links ( $links ) {
	array_unshift( $links, sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=woo-tapsi-delivery' ), __( 'Settings', 'woo-tapsi-delivery' ) ) );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcdd_add_action_links' );
