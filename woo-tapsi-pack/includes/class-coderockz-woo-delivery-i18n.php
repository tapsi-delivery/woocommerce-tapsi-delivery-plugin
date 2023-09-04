<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tapsi.com
 * @since      1.0.0
 *
 * @package    Tapsi_Woo_Delivery
 * @subpackage Tapsi_Woo_Delivery/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tapsi_Woo_Delivery
 * @subpackage Tapsi_Woo_Delivery/includes
 * @author     CodeRockz <admin@tapsi.com>
 */
class Tapsi_Woo_Delivery_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-delivery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
