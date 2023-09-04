<?php

/**
 * Fired during plugin activation
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/includes
 * @author     CodeRockz <admin@coderockz.com>
 */
class Coderockz_Woo_Delivery_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option('coderockz-woo-delivery-activation-time',time());
	}

}
