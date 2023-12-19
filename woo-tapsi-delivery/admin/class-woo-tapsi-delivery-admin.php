<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    0.1.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.1.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Tapsi_Loader as all the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Tapsi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-tapsi-delivery-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    0.1.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Tapsi_Loader as all the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Tapsi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-tapsi-delivery-admin.js', array('jquery', 'wp-i18n'), $this->version, false);

        wp_register_script('woo-tapsi-delivery-admin-locations', plugin_dir_url(__FILE__) . 'js/woo-tapsi-delivery-admin-locations.js', array('jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-backbone-modal'), $this->version, false);

        // register admin map handler
        wp_register_script('woo-tapsi-delivery-admin-map', plugin_dir_url(__FILE__) . 'js/woo-tapsi-delivery-admin-map.js', array('jquery', 'wctd-tapsi-pack-maplibre-library-source'), $this->version, false);
    }

    /**
     * Add the settings class to the WooCommerce settings array
     *
     * @param array $settings Currently defined settings
     * @return array Filtered settings array containing new section
     */
    public function add_settings($settings)
    {
        $settings[] = include_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-tapsi-delivery-settings.php';
        return $settings;
    }

    /**
     * Register a custom post type for the Pickup Locations
     *
     * @return void
     */
    public function register_pickup_location_cpt()
    {

        $labels = array(
            'name' => __('Pickup Locations', 'woo-tapsi-delivery'),
            'singular_name' => __('Pickup Location', 'woo-tapsi-delivery'),
        );
        $args = array(
            'label' => __('Pickup Location', 'woo-tapsi-delivery'),
            'description' => __('Tapsi Pickup Location', 'woo-tapsi-delivery'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
            'show_in_rest' => true,
        );
        register_post_type('dd_pickup_location', $args);

    }

    /**
     * Add the WooCommerce Tapsi Shipping Method to the registered methods array
     *
     * @param array $methods Array of registered methods
     * @return array Filtered array
     */
    public function register_shipping_method($methods)
    {
        $methods['woocommerce_tapsi'] = 'Woocommerce_Tapsi_Shipping_Method';
        return $methods;
    }

    /**
     * Change the displayed meta key of the pickup location to something human-readable
     *
     * @param string $displayed_key Meta key to display to the user
     * @param WC_Meta $meta Meta object
     * @param WC_Order_Item $item Current item
     * @return string Filtered meta key for display
     */
    public function filter_order_item_displayed_meta_key($displayed_key, $meta, $item)
    {
        if ('shipping' === $item->get_type()) {
            switch ($meta->key) {
                case '_tapsi_pickup_location':
                    $displayed_key = __('Pickup Location', 'woo-tapsi-delivery');
                    break;
                case 'tapsi_external_delivery_id':
                    $displayed_key = __('Delivery ID', 'woo-tapsi-delivery');
                    break;
                case 'tapsi_pickup_time':
                    $displayed_key = __('Estimated Pickup', 'woo-tapsi-delivery');
                    break;
                case 'tapsi_dropoff_time':
                    $displayed_key = __('Estimated Dropoff', 'woo-tapsi-delivery');
                    break;
            }
        }
        return $displayed_key;
    }

    /**
     * Change the displayed meta value for the pickup location to include the location name and address
     *
     * @param string $displayed_value Meta value to display to the user
     * @param WC_Meta $meta Meta object
     * @param WC_Order_Item $item Current item
     * @return string Filtered meta value
     */
    public function filter_order_item_displayed_meta_value($displayed_value, $meta, $item)
    {
        if ('shipping' === $item->get_type()) {
            $gmt_offset = get_option('gmt_offset') * HOUR_IN_SECONDS;
            switch ($meta->key) {
                case '_tapsi_pickup_location':
                    $location = new Woocommerce_Tapsi_Pickup_Location(intval($meta->value));
                    $displayed_value = $location->get_name() . '<br>' . $location->get_formatted_address();
                    break;
                case 'tapsi_pickup_time':
                    $time = strtotime($meta->value);
                    $displayed_value = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time + $gmt_offset);
                    break;
                case 'tapsi_dropoff_time':
                    $time = strtotime($meta->value);
                    $displayed_value = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time + $gmt_offset);
                    break;
            }
        }
        return $displayed_value;
    }

    /**
     * Adjust the format of the default hours options before they are saved
     *
     * @param string $value Value entered by user
     * @param string $option Name of option being saved
     * @param string $old_value Old value of option
     * @return string Filtered value in a normalized format
     */
    public function update_default_hours($value, $option, $old_value)
    {
        if (substr($option, 0, 21) == 'woocommerce_tapsi_' && substr($option, -6) == '_hours') {
            $hours = new Woocommerce_Tapsi_Hours();
            $value = $hours->normalize_hour_ranges($value);
        }
        return $value;
    }

    /**
     * Accept the delivery quote when the order is paid
     *
     * @param int $order_id Order ID being processed
     * @return void
     */
    public function accept_delivery_quote(int $order_id)
    {
        // Get the WC_Order object
        $order = wc_get_order($order_id);

        // Get the shipping method for the order
        $methods = $order->get_shipping_methods();
        $method = array_shift($methods);

        // Get the delivery ID and object from the shipping method
        $delivery_id = $method->get_meta("tapsi_external_delivery_id");

        /**
         * @var Woocommerce_Tapsi_Delivery $delivery
         */
        $delivery = $method->get_meta("tapsi_delivery");

        // If the delivery ID isn't set, bail out here
        if (empty($delivery_id)) return;

        // Update the delivery object stored in the shipping method's meta
        $method->update_meta_data('tapsi_delivery', $delivery);

        // Build the order note
        $note = __('Tapsi Delivery Submitted.', 'woo-tapsi-delivery');

        // Get the GMT offset for formatting our times
        $gmt_offset = get_option('gmt_offset') * HOUR_IN_SECONDS;

        // Add pickup time to order note
        if ($delivery->get_pickup_time()) {
            $time = strtotime($delivery->get_pickup_time());
            $displayed_value = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time + $gmt_offset);
            $note .= sprintf(' Estimated pickup at %s.', $displayed_value);
            $order->add_meta_data('tapsi_pickup_time', $delivery->get_pickup_time());
        }

        // Add dropoff time to order note
        if ($delivery->get_dropoff_time()) {
            $time = strtotime($delivery->get_dropoff_time());
            $displayed_value = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time + $gmt_offset);
            $note .= sprintf(' Estimated dropoff at %s.', $displayed_value);
            $order->add_meta_data('tapsi_dropoff_time', $delivery->get_dropoff_time());
        }

        if ($delivery->get_tracking_url()) {
            // If there is a tracking number set, add it to the order note
            $note .= sprintf(' <a href="%s" target="_blank">%s</a>', $delivery->get_tracking_url(), __('Track Delivery', 'woo-tapsi-delivery'));
            $order->add_meta_data('tapsi_tracking_url', $delivery->get_tracking_url());

            // Compat for WooCommerce Shipment Tracking plugin
            if (function_exists('wc_st_add_tracking_number')) {
                $tracking_code = basename(parse_url($delivery->get_tracking_url(), PHP_URL_PATH));
                wc_st_add_tracking_number($order->get_id(), $tracking_code, 'Tapsi', strtotime($delivery->get_pickup_time())); // phpcs:ignore
            }
        }


        $location_id = (int)$method->get_meta("_tapsi_pickup_location");
        $note = '';

        if ($location_id) {
            $pickup_location = new Woocommerce_Tapsi_Pickup_Location($location_id);
            $response = $this->submit_delivery_order($order, $delivery, $pickup_location);

            try {
                $response = json_decode(wp_remote_retrieve_body($response));
                if (property_exists($response, 'successfulOrderSubmission')) {
                    $note = __('Tapsi Delivery Submitted Successfully. ID: ', 'woo-tapsi-delivery') . $response->successfulOrderSubmission->orderId;
                } elseif (property_exists($response, 'details') && property_exists($response->details[0], 'message')) {
                    $note = __('Tapsi Delivery Submission: ', 'woo-tapsi-delivery') . print_r($response->details[0]->message, true);
                } elseif (property_exists($response, 'failedOrderSubmission')) {
                    $note = __('Tapsi Delivery Submission Failed.', 'woo-tapsi-delivery') . ' | ';

                    $failed_response = $response->failedOrderSubmission;
                    if (property_exists($failed_response, 'creditDifference')) {
                        $note .= __('Failure reason: ', 'woo-tapsi-delivery')
                            . __('Balance deficit', 'woo-tapsi-delivery') . ' | '
                            . __('Balance deficit amount: ', 'woo-tapsi-delivery') . $failed_response->creditDifference . ' | ';
                    }

                    if (property_exists($failed_response, 'orderId')) {
                        $note .= __('Order ID: ', 'woo-tapsi-delivery') . $failed_response->orderId . ' | ';
                    }

                } else {
                    $note = __('Tapsi Delivery Submission: ', 'woo-tapsi-delivery') . print_r($response, true);
                }

            } catch (Exception $e) {
                WCDD()->log->debug('Error while parsing submission response! response is: ', $response);
            }
        }

        if ($note == '') {
            $note = __('Could not submit delivery! Try Again', 'woo-tapsi-delivery');
        }

        $order->add_order_note($note);

        // Clear delivery details from session. Leave the selected location.
        WC()->session->set('tapsi_external_delivery_id', '');
        WC()->session->set('tapsi_dropoff_instructions', '');
        WC()->session->set('tapsi_delivery_type', '');
        WC()->session->set('tapsi_delivery_date', '');
        WC()->session->set('tapsi_delivery_time', '');
        WC()->session->set('tapsi_tip_select', '');
        WC()->session->set('tapsi_tip_amount', '');
        WC()->session->set('tapsi_customer_information', '');

        do_action('wcdd_delivery_quote_accepted', $delivery, $order);
    }

    /**
     * Adds the email address configured on the selected pickup location to the admin new order email
     *
     * @param string $recipient Comma separated list of email recipients
     * @param WC_Order $order Order object
     * @param WC_Email_New_Order $email The WooCommerce email being processed
     * @return string Filtered list of recipients
     */
    public function new_order_email_recipient($recipient, $order, $email)
    {
        // Allow developers to disable this functionality
        if (!apply_filters('wcdd_email_new_order_to_location', true, $recipient, $order)) return $recipient;

        // Only run this when dealing with a real order (fixes fatal error on WooCommerce > Settings > Emails screen)
        if (!is_a($order, 'WC_Order')) return $recipient;

        // Get the shipping method for the order
        $methods = $order->get_shipping_methods();
        $method = array_shift($methods);

        // Get the location ID from the meta if it exists
        $location_id = (int)$method->get_meta("_tapsi_pickup_location");

        if ($location_id) {
            // Get the location object
            $location = new Woocommerce_Tapsi_Pickup_Location($location_id);
            // Get the email from the location and add it to the recipient list
            $recipient .= ',' . $location->get_email();
        }

        // Send the list of recipients back to the email class
        return $recipient;
    }

    /**
     * @param Woocommerce_Tapsi_Delivery $delivery
     * @param WC_Order $order
     * @param Woocommerce_Tapsi_Pickup_Location $pickup_location
     * @return void
     */
    public function submit_delivery_order(
        WC_Order                          $order,
        Woocommerce_Tapsi_Delivery        $delivery,
        Woocommerce_Tapsi_Pickup_Location $pickup_location
    ): array
    {
        $receiver_location_description = $order->get_shipping_city() . '، ' .
            $order->get_shipping_address_1() . '، ' .
            $order->get_shipping_address_2() . '، ' .
            $order->get_shipping_company() . '.';

        $destination_lat = $delivery->get_destination_lat();
        $destination_long = $delivery->get_destination_long();

        $sender_address = $pickup_location->get_address();
        $sender_location_description = $sender_address['city'] . '، ' .
            $sender_address['address_1'] . '.';

        $origin_lat = $sender_address['latitude'];
        $origin_long = $sender_address['longitude'];

        $sender = array(
            'location' => array(
                'coordinate' => array(
                    'latitude' => $origin_lat,
                    'longitude' => $origin_long
                ),
                'description' => $sender_location_description,
                'buildingNumber' => $sender_address['postcode'],
                'apartmentNumber' => $sender_address['state']
            )
        );

        $receiver = array(
            'fullName' => $order->get_formatted_shipping_full_name(),
            'phoneNumber' => $order->get_shipping_phone(),
            'location' => array(
                'coordinate' => array(
                    'latitude' => $destination_lat,
                    'longitude' => $destination_long
                ),
                'description' => $receiver_location_description,
                'buildingNumber' => $order->get_shipping_postcode(),
                'apartmentNumber' => '',
            )
        );

        $pack_description = $this->get_pack_description(
            $delivery->get_pickup_instructions(), $delivery->get_dropoff_instructions());

        $pack = array('description' => $pack_description);
        $time_slot_id = $delivery->get_time_slot_id();
        $preview_token = $delivery->get_preview_token();

        if ($sender["location"]["buildingNumber"] == null || $sender["location"]["buildingNumber"] == "") {
            $sender["location"]["buildingNumber"] = "0";
        }
        if ($receiver["location"]["buildingNumber"] == null || $receiver["location"]["buildingNumber"] == "") {
            $receiver["location"]["buildingNumber"] = "0";
        }

        return WCDD()->api->submit_delivery_order($receiver, $sender, $pack, $time_slot_id, $preview_token);
    }

    /**
     * @param $pickup_instructions
     * @param $dropoff_instructions
     * @return string
     */
    public function get_pack_description($pickup_instructions, $dropoff_instructions): string
    {
        $instructions = '';

        if ($pickup_instructions && $pickup_instructions != '') {
            $instructions .= __('Sender Instructions', 'woo-tapsi-delivery') . ': ' . $pickup_instructions . ' | ';
        }

        if ($dropoff_instructions && $dropoff_instructions != '') {
            $instructions .= __('Receiver Instructions', 'woo-tapsi-delivery') . ': ' . $dropoff_instructions;
        }

        return $instructions;
    }
}
