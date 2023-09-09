<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Doordash_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$encrypted_options = array( 
			'woocommerce_doordash_production_signing_secret',
			'woocommerce_doordash_sandbox_signing_secret',
			'woocommerce_doordash_production_key_id',
			'woocommerce_doordash_sandbox_key_id',
		);
		foreach ( $encrypted_options as $option ) {
			if ( ! get_option( $option ) ) update_option( $option, '', false );
		}
	}

}
