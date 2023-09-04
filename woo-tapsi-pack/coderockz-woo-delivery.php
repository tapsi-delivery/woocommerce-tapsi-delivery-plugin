<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tapsi.com
 * @since             0.1.0
 * @package           Tapsi_Woo_Delivery
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Delivery by Tapsi Pack!
 * Description:       Seamlessly integrate your WooCommerce website with Tapsi Pack Delivery!
 * Version:           0.1.0
 * Author:            Tapsi
 * Author URI:        https://pack.tapsi.cab
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-tapsi-pack
 * Domain Path:       /languages
 * WC tested up to:   7.8
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!defined("TAPSI_WOO_DELIVERY_DIR"))
    define("TAPSI_WOO_DELIVERY_DIR",plugin_dir_path(__FILE__));
if(!defined("TAPSI_WOO_DELIVERY_URL"))
    define("TAPSI_WOO_DELIVERY_URL",plugin_dir_url(__FILE__));
if(!defined("TAPSI_WOO_DELIVERY"))
    define("TAPSI_WOO_DELIVERY",plugin_basename(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TAPSI_WOO_DELIVERY_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tapsi-woo-delivery-activator.php
 */
function activate_tapsi_woo_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tapsi-woo-delivery-activator.php';
	Tapsi_Woo_Delivery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tapsi-woo-delivery-deactivator.php
 */
function deactivate_tapsi_woo_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tapsi-woo-delivery-deactivator.php';
	Tapsi_Woo_Delivery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tapsi_woo_delivery' );
register_deactivation_hook( __FILE__, 'deactivate_tapsi_woo_delivery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tapsi-woo-delivery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tapsi_woo_delivery() {

	$plugin = new Tapsi_Woo_Delivery();
	$plugin->run();

}
run_tapsi_woo_delivery();

