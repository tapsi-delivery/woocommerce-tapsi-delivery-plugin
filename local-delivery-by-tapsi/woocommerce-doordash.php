<?php

/**
 * @link              https://www.inverseparadox.com
 * @since             1.0.0
 * @package           Woocommerce_Doordash
 *
 * @wordpress-plugin
 * Plugin Name:       Local Delivery by Tapsi
 * Plugin URI:        https://developer.doordash.com/wordpress-plugin
 * Description:       Let Tapsi power your delivery. Use Tapsi as a shipping provider to offer local, on-demand delivery for your WooCommerce store. Configure multiple pickup locations, delivery hours, tip amounts, and more.
 * Version:           1.0.8
 * Author:            Tapsi
 * Author URI:        https://developer.doordash.com/en-US/wordpress-plugin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       local-delivery-by-doordash
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'WOOCOMMERCE_DOORDASH_VERSION', '1.0.8' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-doordash-activator.php
 */
function activate_woocommerce_doordash() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-doordash-activator.php';
	Woocommerce_Doordash_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-doordash-deactivator.php
 */
function deactivate_woocommerce_doordash() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-doordash-deactivator.php';
	Woocommerce_Doordash_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_doordash' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_doordash' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-doordash.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 * @return Woocommerce_Doordash Static instance of the plugin
 */
function WCDD() {
	// Only run the plugin if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		// If WooCommerce is not active, display a notice.
		add_action( 'admin_notices', function() {
			printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html__( 'Local Delivery by Tapsi requires WooCommerce to be installed and active.', 'local-delivery-by-doordash' ) ); 
		} );
		return false;
	}

	// Retrieve static instance of plugin
	static $plugin;

	// If static instance isn't set, run the plugin
	if ( ! isset( $plugin ) ) {
		$plugin = new Woocommerce_Doordash();
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
	array_unshift( $links, sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash' ), __( 'Settings', 'local-delivery-by-doordash' ) ) );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcdd_add_action_links' );
