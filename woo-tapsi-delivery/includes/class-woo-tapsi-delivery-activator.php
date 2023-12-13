<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    0.1.0
     */
    public static function activate()
    {
        $encrypted_options = array(
            'woocommerce_tapsi_production_signing_secret',
            'woocommerce_tapsi_sandbox_signing_secret',
            'woocommerce_tapsi_production_key_id',
            'woocommerce_tapsi_sandbox_key_id',
        );
        foreach ($encrypted_options as $option) {
            if (!get_option($option)) update_option($option, '', false);
        }
    }

}
