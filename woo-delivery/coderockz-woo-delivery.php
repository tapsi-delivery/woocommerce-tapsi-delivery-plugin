<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://coderockz.com
 * @since             1.0.0
 * @package           Coderockz_Woo_Delivery
 *
 * @wordpress-plugin
 * Plugin Name:       Delivery & Pickup Date Time for WooCommerce
 * Description:       Delivery & Pickup Date Time for WooCommerce is a WooCommerce plugin extension that gives the facility of selecting delivery/pickup date and time on order checkout page. Moreover, you don't need to worry about the styling because the plugin adjusts with your WordPress theme.
 * Version:           1.3.59
 * Author:            CodeRockz
 * Author URI:        https://coderockz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-delivery
 * Domain Path:       /languages
 * WC tested up to:   7.8
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!defined("CODEROCKZ_WOO_DELIVERY_DIR"))
    define("CODEROCKZ_WOO_DELIVERY_DIR",plugin_dir_path(__FILE__));
if(!defined("CODEROCKZ_WOO_DELIVERY_URL"))
    define("CODEROCKZ_WOO_DELIVERY_URL",plugin_dir_url(__FILE__));
if(!defined("CODEROCKZ_WOO_DELIVERY"))
    define("CODEROCKZ_WOO_DELIVERY",plugin_basename(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CODEROCKZ_WOO_DELIVERY_VERSION', '1.3.59' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-coderockz-woo-delivery-activator.php
 */
function activate_coderockz_woo_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coderockz-woo-delivery-activator.php';
	Coderockz_Woo_Delivery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-coderockz-woo-delivery-deactivator.php
 */
function deactivate_coderockz_woo_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coderockz-woo-delivery-deactivator.php';
	Coderockz_Woo_Delivery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_coderockz_woo_delivery' );
register_deactivation_hook( __FILE__, 'deactivate_coderockz_woo_delivery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-coderockz-woo-delivery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_coderockz_woo_delivery() {

	$plugin = new Coderockz_Woo_Delivery();
	$plugin->run();

}
run_coderockz_woo_delivery();

