<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_shipping_zone
{


    private static string $tapsi_zone_name = 'Tapsi-Zone';
    private static string $tapsi_shipping_method_id = 'woocommerce_tapsi';
    private static array $tapsi_shipping_locations = array(
        array(
            'code' => 'IR:THR',
            'type' => 'state'
        ),
        array(
            'code' => 'IR:276',
            'type' => 'state'
        )
    );


    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.1.0
     */
    public function set_shipping_zone()
    {
        $tapsi_zone_exists = self::tapsi_zone_exists();

        if (!$tapsi_zone_exists) {
            self::create_tapsi_zone();
        }
    }


    /**
     * Check if Tehran Zone exists.
     *
     * @return bool
     */
    public static function tapsi_zone_exists()
    {
        $zones = WC_Shipping_Zones::get_zones();

        foreach ($zones as $zone) {
            if ($zone['zone_name'] === self::$tapsi_zone_name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Create Tehran Zone.
     *
     * @return WC_Shipping_Zone
     */
    public static function create_tapsi_zone(): WC_Shipping_Zone
    {
        $new_zone = new WC_Shipping_Zone();

        $new_zone->set_zone_name(self::$tapsi_zone_name);

        foreach (self::$tapsi_shipping_locations as $location) {
            $new_zone->add_location($location['code'], $location['type']);
        }

        $new_zone->add_shipping_method(self::$tapsi_shipping_method_id);

        $new_zone_id = $new_zone->save();
        return new WC_Shipping_Zone($new_zone_id);
    }
}
