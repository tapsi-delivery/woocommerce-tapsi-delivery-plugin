<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/public
 */

use Automattic\WooCommerce\Admin\Overrides\Order;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/public
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private string $plugin_name;

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
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    0.1.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    0.1.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Tapsi_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Tapsi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-tapsi-delivery-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    0.1.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Tapsi_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Tapsi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-tapsi-delivery-public.js', array('jquery', 'selectWoo'), $this->version, false);
    }

    public function get_destination($stringify = false)
    {
        $azadi_coordinate = array(51.337762, 35.699927);

        $destination_long = floatval(WC()->session->get('wctd_tapsi_destination_long'));
        if ($destination_long != null && $destination_long != 0 && $destination_long != '0') $lng_1 = $destination_long;
        else $lng_1 = $azadi_coordinate[0];
        $lng_2 = $lng_1 + 0.0000001;

        $destination_lat = floatval(WC()->session->get('wctd_tapsi_destination_lat'));
        if ($destination_lat != null && $destination_lat != 0 && $destination_lat != '0') $lat_1 = $destination_lat;
        else $lat_1 = $azadi_coordinate[1];
        $lat_2 = $lat_1 + 0.0000001;


        if ($stringify) return $lng_1 . ',' . $lat_1 . '|' . $lng_2 . ',' . $lat_2;
        else return array($lng_1, $lat_1);
    }

    /**
     * Display the dropdown selector for users to choose a delivery location
     *
     * @param WC_Shipping_rate $shipping_rate
     * @param $index
     * @return void
     */
    public function show_available_locations_dropdown(WC_Shipping_rate $shipping_rate, $index)
    {
        // Get the selected method
        $chosen_shipping_rate_id = WC()->session->get('chosen_shipping_methods')[0]; // [0]

        // Get the meta data from the rate
        $meta = $shipping_rate->get_meta_data();
        // Set up the delivery object
        if (array_key_exists('tapsi_delivery', $meta)) $delivery = $meta['tapsi_delivery'];
        else $delivery = false;

        // Only output the fields in the checkout page
        if (is_checkout()) {
            // Only output the field if the selected method is a WooCommerce Tapsi method
            if (false !== strpos($chosen_shipping_rate_id, 'woocommerce_tapsi') && $shipping_rate->id === $chosen_shipping_rate_id) {
                echo '<div class="wcdd-delivery-options">';

                // Get the enabled locations
                $locations = $this->get_enabled_locations();

                if (is_countable($locations) && count($locations) == 1) {
                    //there's a single location available...so make it default here
                    $selected_location = $locations[0]->get_id();
                } else {
                    $selected_location = WC()->checkout->get_value('tapsi_pickup_location') ? WC()->checkout->get_value('tapsi_pickup_location') : WC()->session->get('tapsi_pickup_location');
                }

                $location = new Woocommerce_Tapsi_Pickup_Location($selected_location);

                $this->disable_problematic_filters();

                // Output pickup locations field
                if (is_countable($locations) && count($locations) == 1) {
                    //hidden field + display for single location
                    woocommerce_form_field('tapsi_pickup_location', array(
                        'type' => 'hidden',
                        'label' => __('Origin', 'woo-tapsi-delivery'),
                        'class' => array('wcdd-pickup-location-select', 'update_totals_on_change'), // add 'wc-enhanced-select'?
                        'label_class' => 'wcts-tapsi-pack-checkout-form-label',
                        'default' => $selected_location,
                    ), $selected_location);

                    echo '<p>' . $locations[0]->get_name() . ' - ' . $locations[0]->get_formatted_address() . '</p>';
                } else {
                    woocommerce_form_field('tapsi_pickup_location', array(
                        'type' => 'select',
                        'label' => __('Origin', 'woo-tapsi-delivery'),
                        'placeholder' => __('Select...', 'woo-tapsi-delivery'),
                        'label_class' => 'wcts-tapsi-pack-checkout-form-label',
                        'class' => array('wcdd-pickup-location-select', 'update_totals_on_change'), // add 'wc-enhanced-select'?
                        'required' => true,
                        'default' => $selected_location,
                        'options' => $this->generate_locations_options($locations), // Use the enabled locations to generate an option array
                    ), $selected_location); // $checkout->get_value( 'tapsi_pickup_location' ) );
                }


                echo '<section class="wctd-tapsi-pack-destination-shard">';
                echo '<p class="wcts-tapsi-pack-checkout-form-label">' . __('Destination', 'woo-tapsi-delivery') . '</label>&nbsp;<abbr class="required" title="' . esc_attr__('required', 'woocommerce') . '">*</abbr></p>';// open map modal with this button
                echo '<button id="wctd-tapsi-pack-show-map-button-checkout-page" type="button">' . __('Choose Destination on Map', 'woo-tapsi-delivery') . '</button>';
                echo '<div id="wctd-tapsi-pack-maplibre-map-public-preview-img-container">';
                echo '<img src="https://tap30.services/styles/passenger/static/auto/500x500@2x.png?path=' . $this->get_destination(true) . '&stroke=black&width=200&padding=50000" width="500" height="500"  id="wctd-tapsi-pack-maplibre-map-public-preview-img" alt="destination-preview"/>';
                echo '<img src="https://static.tapsi.cab/pack/wp-plugin/map/dot.svg" width="24" height="24" id="wctd-tapsi-pack-maplibre-map-public-preview-img-dot"/>';
                echo '</div>';
                echo '<p id="wctd-tapsi-pack-maplibre-map-public-warning"><img src="https://static.tapsi.cab/pack/wp-plugin/map/warning.svg" width="24" height="24" alt="!!!"/>' . __('Please make sure that the coordinates on the map match your destination. Tapsi Pack delivers the package to the chosen coordinates regardless of the provided address.', 'woo-tapsi-delivery') . '</p>';

                $destination = $this->get_destination(false);

                woocommerce_form_field('wctd_tapsi_destination_lat', array(
                    'id' => 'wctd_tapsi_destination_lat',
                    'type' => 'hidden',
                    'class' => array('wcdd-delivery-destination-lat', 'update_totals_on_change'),
                    'required' => true,
                    'default' => $destination[1],
                ), $destination[1]);

                woocommerce_form_field('wctd_tapsi_destination_long', array(
                    'id' => 'wctd_tapsi_destination_long',
                    'type' => 'hidden',
                    'class' => array('wcdd-delivery-destination-long', 'update_totals_on_change'),
                    'required' => true,
                    'default' => $destination[0],
                ), $destination[0]);

                wp_nonce_field('wcdd_set_pickup_location', 'wcdd_set_pickup_location_nonce');

                if ($selected_location != 0) {

                    echo '<div class="wcdd-delivery-schedule">';

                    $delivery_days = $location->get_delivery_days();
                    woocommerce_form_field('tapsi_delivery_date', array(
                        'type' => 'select',
                        'label' => __('Day', 'woo-tapsi-delivery'),
                        'class' => array('wcdd-delivery-date-select', 'update_totals_on_change'),
                        'required' => true,
                        'default' => WC()->session->get('tapsi_delivery_date'),
                        'options' => $delivery_days,
                    ), WC()->session->get('tapsi_delivery_date'));

                    $delivery_days_keys = array_keys($delivery_days);
                    $selected_date = !empty(WC()->session->get('tapsi_delivery_date')) ? WC()->session->get('tapsi_delivery_date') : array_shift($delivery_days_keys);
                    $delivery_times_for_date = $this->get_time_slots($selected_date);

                    $selected_time_key = WC()->session->get('tapsi_delivery_time');
                    $this->update_fee($selected_time_key);

                    woocommerce_form_field('tapsi_delivery_time', array(
                        'type' => 'select',
                        'label' => __('Time', 'woo-tapsi-delivery'),
                        'class' => array('wcdd-delivery-time-select', 'update_totals_on_change'),
                        'required' => true,
                        'default' => $selected_time_key,
                        'options' => $delivery_times_for_date,
                    ), $selected_time_key);
                    $this->enable_problematic_filters();

                    echo '</div>';
                    $gmt_offset = get_option('gmt_offset') * HOUR_IN_SECONDS;

                    // Output the Dropoff Instructions field
                    woocommerce_form_field('tapsi_dropoff_instructions', array(
                        'type' => 'text',
                        'label' => __('Dropoff Instructions', 'woo-tapsi-delivery'),
                        'class' => array('wcdd-dropoff-instructions', 'update_totals_on_change'),
                        'default' => WC()->session->get('tapsi_dropoff_instructions'),
                        'placeholder' => __('(Optional)', 'woo-tapsi-delivery'),
                    ), WC()->checkout->get_value('tapsi_dropoff_instructions'));
                }

                woocommerce_form_field('tapsi_external_delivery_id', array(
                    // 'type' => 'text',
                    'type' => 'hidden',
                    'default' => WC()->session->get('tapsi_external_delivery_id'),
                ), WC()->checkout->get_value('tapsi_external_delivery_id'));

                // Render the rules when the user has seen the price
                if (!empty($delivery_times_for_date)) {
                    echo '<section class="wcts-tapsi-pack-rules-section" >
						<button id="wctd-tapsi-pack-rules-button">' . __('Rules', 'woo-tapsi-delivery') . '</button>
						<ul id="wctd-tapsi-pack-rules">
							<li>- ' . __('Package delivery is done by car, so the packages are delivered only at the door of the building and the driver will wait for you for a maximum of 5 minutes.', 'woo-tapsi-delivery') . '</li>
							<li>- ' . __('After starting the trip, the driver\'s information and the approximate arrival time will be sent to you via SMS.', 'woo-tapsi-delivery') . '</li>
						</ul>
					 </section>';
                }

                if (apply_filters('wcdd_show_tapsi_logo', true)) {
                    echo '<div id="wcdd-delivery-options-powered">';
                    echo '<a id="wcdd-delivery-options-powered-tapsi-pack-link" target="_blank" href="' . "https://pack.tapsi.ir/landing" . '" >'; // TODO: MARYAM think about this link
                    echo '<img src="' . plugin_dir_url(__FILE__) . '/img/tapsi-pack.png" alt="Tapsi" width="100px" height="56.5px"></img>';
                    echo '</a>';
                    echo '</div>';
                }

                echo '</div>';
            }
        }
    }

    /**
     * Validate the selected pickup location
     *
     * @return void
     */
    public function validate_pickup_location()
    {
        $chosen_shipping_rate_id = WC()->session->get('chosen_shipping_methods')[0];

        // Only run this if Tapsi is the selected shipping method
        if (false !== strpos($chosen_shipping_rate_id, 'woocommerce_tapsi')) {
            $external_delivery_id = WC()->session->get('tapsi_external_delivery_id');

            // Fail if a location is not selected or a quote hasn't been retrieved
            if (empty($external_delivery_id)) {
                wc_add_notice(__('Tapsi Delivery: Please choose a valid location.', 'woo-tapsi-delivery'), 'error');
                return;
            }

            $delivery = new Woocommerce_Tapsi_Delivery();

            if (!$delivery->get_time_slot_id()) {
                wc_add_notice(__('Tapsi Delivery: Please choose a valid time slot.', 'woo-tapsi-delivery'), 'error');
            }

            if ($this->location_is_not_valid($delivery->get_destination_lat(), $delivery->get_destination_long())) {
                wc_add_notice(__('Tapsi Delivery: Please choose a valid location.', 'woo-tapsi-delivery'), 'error');
            }

            if (!$delivery->get_preview_token() || $delivery->get_preview_token() == '') {
                wc_add_notice(__('Tapsi Delivery: Delivery is not selected properly. Please try again.', 'woo-tapsi-delivery'), 'error');
            }
        }
    }

    /**
     * Disables the Cash on Delivery gateway when WCDD is selected
     *
     * @param array $available_gateways
     * @return array Filtered gateways
     */
    public function disable_cod($available_gateways)
    {
        if (is_admin() || is_null(WC()->session)) {
            return $available_gateways;
        }
        // Get the selected method
        $chosen_shipping_rate = WC()->session->get('chosen_shipping_methods');
        if (is_array($chosen_shipping_rate)) {
            $chosen_shipping_rate_id = $chosen_shipping_rate[0]; // [0]

            // Unset the CoD method if WCDD is selected for shipping
            if (isset($available_gateways['cod']) && false !== strpos($chosen_shipping_rate_id, 'woocommerce_tapsi')) {
                unset($available_gateways['cod']);
            }
        }

        return $available_gateways;
    }

    /**
     * Adds a shipping phone number field to the shipping address
     *
     * @param array $fields Checkout fields
     * @return array Filtered fields
     */
    public function add_shipping_phone($fields)
    {
        $fields['shipping']['shipping_phone'] = apply_filters('wcdd_shipping_phone_field', array(
            'label' => __('Phone', 'woocommerce'),
            'placeholder' => _x('Phone', 'placeholder', 'woocommerce'),
            'type' => 'tel',
            'required' => true,
            'class' => array('form-row-wide', 'update_totals_on_change'),
            'clear' => true,
            'validate' => array('phone'),
            'autocomplete' => 'tel',
        ));
        return $fields;
    }

    /**
     * Adds the update_totals_on_change class to the phone number field
     *
     * @param array $fields Checkout fields
     * @return array Filtered fields
     */
    public function add_update_totals_to_phone($fields)
    {
        $fields['billing']['billing_phone']['class'][] = 'update_totals_on_change';
        $fields['billing']['billing_phone']['class'][] = 'address-field';
        $fields['shipping']['shipping_phone']['class'][] = 'update_totals_on_change';
        $fields['shipping']['shipping_phone']['class'][] = 'address-field';
        return $fields;
    }

    /**
     * Save the pickup location as order meta
     *
     * @hooked woocommerce_checkout_create_order - 10
     *
     * @param WC_Order $order WooCommerce order that was created
     * @param $data Order data?
     */
    public function save_pickup_location_to_order($order, $data)
    {
        if (isset($_REQUEST['tapsi_pickup_location']) && !empty($_REQUEST['tapsi_pickup_location'])) {
            $order->update_meta_data('tapsi_pickup_location', intval($_REQUEST['tapsi_pickup_location']));
        }
    }

    /**
     * Save the pickup location as shipping item meta
     *
     * @param WC_Order_Item $item Current order item
     * @param string $package_key Array key of the current package
     * @param array $package Array of package data
     * @param WC_Order $order Current order
     * @return void
     */
    public function save_pickup_location_to_order_item_shipping($item, $package_key, $package, $order)
    {
        if (isset($_REQUEST['tapsi_pickup_location']) && !empty($_REQUEST['tapsi_pickup_location'])) {
            $item->update_meta_data('_tapsi_pickup_location', intval($_REQUEST['tapsi_pickup_location']));
        }
    }

    /**
     * Shows the pickup location on the Order Item Totals screen
     *
     * @hooked woocommerce_get_order_item_totals - 10
     *
     * @param array $total_rows
     * @param WC_Order $order
     * @param bool $tax_display
     * @return array Rows with Tapsi Pickup Location added
     */
    public function display_pickup_location_on_order_item_totals($total_rows, $order, $tax_display)
    {
        // Get the selected pickup location
        $tapsi_pickup_location = $order->get_meta('tapsi_pickup_location');
        $new_total_rows = array();

        // Bail if location not set
        if (empty($tapsi_pickup_location)) return $total_rows;

        // Set up the location object
        $location = new Woocommerce_Tapsi_Pickup_Location(intval($tapsi_pickup_location));

        // Get the shipping lines from the order
        $items = $order->get_items('shipping');
        foreach ($items as $item) {
            $delivery = $item->get_meta('tapsi_delivery');
            if ($delivery) break; // Delivery found
        }

        foreach ($total_rows as $key => $value) {
            if ('shipping' == $key) {
                // Add the row with information on the pickup location
                $new_total_rows['tapsi_pickup_location'] = array(
                    'label' => __('Tapsi from:', 'woo-tapsi-delivery'),
                    'value' => $location->get_name() . '<br>' . $location->get_formatted_address(),
                );
                if ($delivery && $delivery->get_dropoff_time()) {
                    // If the delivery exists, display the dropoff time
                    $gmt_offset = get_option('gmt_offset') * HOUR_IN_SECONDS;
                    $time = strtotime($delivery->get_dropoff_time());
                    $displayed_dropoff_time = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time + $gmt_offset);

                    $new_total_rows['tapsi_delivery_time'] = array(
                        'label' => __('Estimated Delivery Time:', 'woo-tapsi-delivery'),
                        'value' => $displayed_dropoff_time,
                    );
                }
            } else {
                $new_total_rows[$key] = $value;
            }
        }

        // Return the modified rows
        return $new_total_rows;
    }

    /**
     * Retrieve an array of Pickup Location objects that are currently enabled
     *
     * @return array Array of Woocommerce_Tapsi_Pickup_Location objects
     */
    public function get_enabled_locations()
    {
        // Set up the query, allow filtering the query arguments
        $args = apply_filters('wcdd_enabled_locations_query_args', array(
            'post_type' => 'dd_pickup_location',
            'post_status' => array('publish'), // Post status publish are enabled
            'numposts' => -1,
        ));
        $locations = get_posts($args);

        foreach ($locations as &$location) {
            $location = new Woocommerce_Tapsi_Pickup_Location($location); // Set the array with the location object instead of the post
        }

        return apply_filters('wcdd_enabled_locations', $locations);
    }

    /**
     * Gets the tip percentages to show on checkout page
     *
     * @return array Keys are strings with a decimal representation of the discount, values are the labels to display for the buttons
     */
    public function get_tip_options()
    {
        return apply_filters('wcdd_tip_options', array(
            '.15' => '15%',
            '.20' => '20%',
            '.25' => '25%',
            'other' => 'Custom',
        ));
    }

    /**
     * Create ID => Name array of available locations
     *
     * @param array $locations Array of Woocommerce_Tapsi_Pickup_Location objects
     * @return array New array with ID => Name
     */
    public function generate_locations_options($locations)
    {
        // Add a placeholder option
        $options = array(0 => __('Select', 'woo-tapsi-delivery'));

        foreach ($locations as $location) {
            $options[$location->get_id()] = apply_filters('wcdd_location_option_name', $location->get_name() . ' - ' . $location->get_formatted_address(), $location);
        }

        return $options;
    }

    /**
     * Saves the pickup location, tip select, and tip amount to the session on update_order_review
     *
     * @hooked woocommerce_checkout_update_order_review - 10
     * @hooked wp_ajax_nopriv_tapsi_save_data_to_session - 10
     *
     * @param string $data String with passed data from checkout
     * @return void
     */
    public function save_data_to_session(string $data_string)
    {
        // Parse the data from a string to an array
        parse_str($data_string, $data);

        // check to see if we should pull from billing or shipping fields, and set the field prefix
        $prefix = 'billing_';
        if (array_key_exists('ship_to_different_address', $data)) {
            $prefix = 'shipping_';
        }

        //grab delivery type setting
        $woocommerce_tapsi_delivery_scheduling = get_option('woocommerce_tapsi_delivery_scheduling');

        // Has the date changed?
        $preview_changed = false;

        // Store the customer contact info in an array, then save to the session
        $customer_data_keys = array($prefix . 'first_name', $prefix . 'last_name', $prefix . 'company', $prefix . 'country', $prefix . 'address_1', $prefix . 'address_2', $prefix . 'city', $prefix . 'state', $prefix . 'postcode', $prefix . 'phone');
        $customer_contact_information = array();
        foreach ($customer_data_keys as $key) {
            if (array_key_exists($key, $data)) {
                $base_key = str_replace($prefix, '', $key);
                $customer_contact_information[$base_key] = $data[$key];
            }
        }
        WC()->session->set('tapsi_customer_information', $customer_contact_information);

        // Save the Pickup Location
        if (array_key_exists('tapsi_pickup_location', $data)) { // phpcs:ignore String is parsed to array
            $tapsi_pickup_location = $data['tapsi_pickup_location'];
            if ($tapsi_pickup_location != WC()->session->get('tapsi_pickup_location') && WC()->session->get('tapsi_pickup_location') != '') $preview_changed = true;
            WC()->session->set('tapsi_pickup_location', $tapsi_pickup_location);
        } else {
            //make pickup location gets set if possible
            $locations = $this->get_enabled_locations();

            if (is_countable($locations) && count($locations) > 0) {
                //first location as default if nothing is set
                $tapsi_pickup_location = $locations[0]->get_id();
                WC()->session->set('tapsi_pickup_location', $tapsi_pickup_location);
            } else {
                //there are no locations
            }
        }


        // Save the dropoff instructions
        if (array_key_exists('tapsi_dropoff_instructions', $data)) { // phpcs:ignore
            $tapsi_dropoff_instructions = $data['tapsi_dropoff_instructions'];
            WC()->session->set('tapsi_dropoff_instructions', $tapsi_dropoff_instructions);
        }

        // Save destination coordinate latitude
        if (array_key_exists('wctd_tapsi_destination_lat', $data)) { // phpcs:ignore
            $wctd_tapsi_destination_lat = $data['wctd_tapsi_destination_lat'];
            if ($wctd_tapsi_destination_lat != WC()->session->get('wctd_tapsi_destination_lat') && WC()->session->get('wctd_tapsi_destination_lat') != '') $preview_changed = true;
            WC()->session->set('wctd_tapsi_destination_lat', $wctd_tapsi_destination_lat);
        }
        // Save destination coordinate longitude
        if (array_key_exists('wctd_tapsi_destination_long', $data)) { // phpcs:ignore
            $wctd_tapsi_destination_long = $data['wctd_tapsi_destination_long'];
            if ($wctd_tapsi_destination_long != WC()->session->get('wctd_tapsi_destination_long') && WC()->session->get('wctd_tapsi_destination_long') != '') $preview_changed = true;
            WC()->session->set('wctd_tapsi_destination_long', $wctd_tapsi_destination_long);
        }

        // Save the delivery type
        if (array_key_exists('tapsi_delivery_type', $data)) { // phpcs:ignore
            $tapsi_delivery_type = $data['tapsi_delivery_type'];
            // If the delivery type has been set to immediate, make sure to remove the delivery date value.
            if ($tapsi_delivery_type == 'immediate') $data['tapsi_delivery_date'] = '';
            WC()->session->set('tapsi_delivery_type', $tapsi_delivery_type);
        } elseif ($woocommerce_tapsi_delivery_scheduling == 'scheduled') {
            $tapsi_delivery_type = 'scheduled';
            WC()->session->set('tapsi_delivery_type', $tapsi_delivery_type);
        }

        $tapsi_delivery_date = null;
        if (array_key_exists('tapsi_delivery_date', $data)) { // phpcs:ignore
            $tapsi_delivery_date = $data['tapsi_delivery_date'];
            // Set $date_changed if the user changed the date since the last request.
            if ($tapsi_delivery_date != WC()->session->get('tapsi_delivery_date') && WC()->session->get('tapsi_delivery_date') != '') $preview_changed = true;
            WC()->session->set('tapsi_delivery_date', $tapsi_delivery_date);
        }

        // Save the delivery time
        if (array_key_exists('tapsi_delivery_time', $data)) { //phpcs:ignore
            if ($preview_changed) {
                // If the date changed, we need to manually get the first available time for that day
                $available_times = array_keys($this->get_time_slots($tapsi_delivery_date));
                $tapsi_delivery_time = array_shift($available_times);
            } else {
                // If the date didn't change, carry on
                $tapsi_delivery_time = $data['tapsi_delivery_time'];
            }
            WC()->session->set('tapsi_delivery_time', $tapsi_delivery_time);
            $this->update_fee($tapsi_delivery_time);
        } elseif (array_key_exists('tapsi_delivery_date', $data) && $data['tapsi_delivery_date'] == '') {
            //if this doesn't exist, set it to earliest. The form fields probably didn't exist in the html for this update
            $location = new Woocommerce_Tapsi_Pickup_Location($tapsi_pickup_location);
            $tapsi_delivery_time = $location->get_next_valid_time();

            //catch tip here too
            $data['tapsi_tip_select'] = '.20';

            WC()->session->set('tapsi_delivery_time', $tapsi_delivery_time);
        }

        // Save the selected tip value
        if (array_key_exists('tapsi_tip_select', $data)) { // phpcs:ignore
            $tapsi_tip_select = $data['tapsi_tip_select'];
            WC()->session->set('tapsi_tip_select', $tapsi_tip_select);
        } else {
            $tapsi_tip_select = 'other';
        }

        // Handle the actual tip amount from the options or the number input
        if ('other' != $tapsi_tip_select) {
            if (strpos($tapsi_tip_select, '%') !== false) $tapsi_tip_select = floatval($tapsi_tip_select) / 100;
            $tapsi_tip_amount = WC()->cart->get_subtotal() * floatval($tapsi_tip_select);
        } elseif (array_key_exists('tapsi_tip_amount', $data)) { // phpcs:ignore
            // Save the entered tip amount
            $tapsi_tip_amount = floatval($data['tapsi_tip_amount']);
        } else {
            $tapsi_tip_amount = 0;
        }
        WC()->session->set('tapsi_tip_amount', number_format($tapsi_tip_amount, 2, '.', ''));

        return;
    }

    /**
     * Update the user selected delivery pickup location in the user's session
     *
     * @hooked wp_ajax_wcdd_update_pickup_location - 10
     * @hooked wp_ajax_nopriv_wcdd_update_pickup_location - 10
     *
     * @return void
     */
    public function save_pickup_location_to_session()
    {

        if (array_key_exists('location_id', $_POST) && !empty($_POST['location_id'])) {
            // Sanitize
            $location_id = intval($_POST['location_id']);

            // Set the location ID in the session
            WC()->session->set('tapsi_pickup_location', $location_id);
        }

        if (array_key_exists('wctd_tapsi_destination_long', $_POST) && !empty($_POST['wctd_tapsi_destination_long'])) {
            // Sanitize
            $wctd_tapsi_destination_long = $_POST['wctd_tapsi_destination_long'];

            // Set the location ID in the session
            WC()->session->set('wctd_tapsi_destination_long', $wctd_tapsi_destination_long);
        }

        if (array_key_exists('wctd_tapsi_destination_lat', $_POST) && !empty($_POST['wctd_tapsi_destination_lat'])) {
            // Sanitize
            $wctd_tapsi_destination_lat = $_POST['wctd_tapsi_destination_lat'];

            // Set the location ID in the session
            WC()->session->set('wctd_tapsi_destination_lat', $wctd_tapsi_destination_lat);
        }

        exit;
    }

    /**
     * Clear the stored rates when updating the cart
     *
     * @hooked woocommerce_checkout_update_order_review - 10
     *
     * @return void
     */
    public function trigger_shipping_calculation($data)
    {
        $packages = WC()->cart->get_shipping_packages();
        foreach ($packages as $package_key => $package) {
            $session_key = 'shipping_for_package_' . $package_key;
            $stored_rates = WC()->session->__unset($session_key);
        }
    }

    private function get_time_slots($datestamp): array
    {
        if ($datestamp == null) {
            echo __('First select a date', 'woo-tapsi-delivery');
            WC()->session->set('tapsi_preview_token', '');
            return [];
        }

        if (is_string($datestamp)) {
            $datestamp = intval($datestamp);
        }

        if ($datestamp == 0) {
            echo __('Selected date is not valid', 'woo-tapsi-delivery');
            WC()->session->set('tapsi_preview_token', '');
            return [];
        }

        $preview = $this->get_delivery_preview($datestamp);

        WC()->session->set('tapsi_preview_token', $preview['token']);
        return $preview['time_slots'];
    }


    /**
     * Given a datestamp, retrieve the user-selectable pickup time options for that date
     *
     * @param ?int $datestamp Date to get options for
     * @return array Array containing timestamp keys and formatted time values
     */
    private function get_delivery_preview(int $datestamp): array
    {
        $preview = [
            'time_slots' => array(),
            'token' => ''
        ];

        $selected_location = WC()->checkout->get_value('tapsi_pickup_location') ? WC()->checkout->get_value('tapsi_pickup_location') : WC()->session->get('tapsi_pickup_location');
        $pickup_Location = new Woocommerce_Tapsi_Pickup_Location($selected_location);

        $origin_lat = $pickup_Location->get_address()['latitude'];
        $origin_long = $pickup_Location->get_address()['longitude'];
        $destination_lat = WC()->session->get('wctd_tapsi_destination_lat');
        $destination_long = WC()->session->get('wctd_tapsi_destination_long');
        $date_timestamp = $datestamp * 1000;

        if ($origin_lat == null || $origin_long == null || $destination_lat == null || $destination_long == null) {
            echo __('First select origin and destination', 'woo-tapsi-delivery');
            return $preview;
        }

        $raw_response = WCDD()->api->get_preview($origin_lat, $origin_long, $destination_lat, $destination_long, $date_timestamp);

        if (is_wp_error($raw_response)) {
            wc_add_notice(__('Failed to fetch delivery times. Please try again later.', 'woo-tapsi-delivery'), 'error');
            echo __('Failed to fetch delivery times. Please try again later.', 'woo-tapsi-delivery');
        } else {
            $data = json_decode(wp_remote_retrieve_body($raw_response));

            if ($data) {
                $preview['token'] = $data->token;

                if (property_exists($data, 'invoicePerTimeslots')) {
                    $raw_timeslots = $data->invoicePerTimeslots;
                    $all_timeslots = $this->process_timeslots($raw_timeslots, $pickup_Location);

                    foreach ($all_timeslots as $timeslot) {
                        if ($timeslot['status'] == 'ok') {
                            $preview['time_slots'][$timeslot['key']] = $timeslot['display'];
                        }
                    }

                    if (count($preview['time_slots']) == 0) {
                        $no_time_slot_alert = __('No available time slot', 'woo-tapsi-delivery');

                        foreach ($all_timeslots as $timeslot) {
                            if ($timeslot['status'] == 'balance_deficit') {
                                $no_time_slot_alert = __('Balance is not enough', 'woo-tapsi-delivery');
                                break;
                            }
                            if ($timeslot['status'] == 'store_unavailable') {
                                $no_time_slot_alert = __('Store is not available', 'woo-tapsi-delivery');;
                                break;
                            }
                        }

                        echo $no_time_slot_alert;
                        return $preview;
                    }

                } elseif (property_exists($data, 'details') && property_exists($data->details[0], 'message')) {
                    echo $data->details[0]->message;
                    return $preview;
                } else {
                    echo 'No available delivery times found.';
                    return $preview;
                }
            } else {
                echo 'Failed to parse API response.';
                return $preview;
            }
        }

        return $preview;
    }

    public function render_checkout_map_modal()
    {
        $current_url = $_SERVER['REQUEST_URI'];

        if (strpos($current_url, 'checkout') !== false) {
            wp_enqueue_style('wctd-tapsi-pack-map-modal-public-stylesheet', 'https://static.tapsi.cab/pack/wp-plugin/map/map-public.css');
            // Map Libre Js and Map Libre CSS where previously added by enqueue script function
            require_once 'partials/wctd-taps-pack-maplibre-map-modal.php';
            // Map Js is handled inside the woo-tapsi-delivery-public file
        }
    }

    /**
     * @param $timeslot
     * @return array
     */
    public function get_timeslot_interval($timeslot): array
    {
        $timezone = new DateTimeZone('Asia/Tehran'); // +3:30 timezone

        $int_start_timestamp = $timeslot->startTimestamp / 1000;
        $int_end_timestamp = $timeslot->endTimestamp / 1000;

        $obj_start_timestamp = new DateTime('@' . $int_start_timestamp);
        $obj_end_timestamp = new DateTime('@' . $int_end_timestamp);

        $obj_start_timestamp->setTimezone($timezone);
        $obj_end_timestamp->setTimezone($timezone);

        return array(
            'start_hour' => intval($obj_start_timestamp->format('H')),
            'start_min' => intval($obj_start_timestamp->format('i')),
            'end_hour' => intval($obj_end_timestamp->format('H')),
            'end_min' => intval($obj_end_timestamp->format('i')),
        );
    }

    /**
     * @param $timeslot
     * @return string|null
     */
    public function get_weekday($timeslot): ?string
    {
        $timezone = new DateTimeZone('Asia/Tehran');
        $int_timestamp = $timeslot->startTimestamp / 1000;
        try {
            $obj_timestamp = new DateTime('@' . $int_timestamp);
        } catch (Exception $e) {
            return null;
        }
        $obj_timestamp->setTimezone($timezone);
        return $obj_timestamp->format('l');
    }

    private function get_working_interval($working_hour): array
    {
        $times = explode('-', $working_hour);
        $start_time = explode(':', $times[0]);
        $end_time = explode(':', $times[1]);


        return array(
            'start_hour' => intval($start_time[0]),
            'start_min' => intval($start_time[1]),
            'end_hour' => intval($end_time[0]),
            'end_min' => intval($end_time[1])
        );

    }

    private function is_timeslot_on_working(array $timeslot_interval, array $working_interval): bool
    {
        $is_timeslot_start_working = ($timeslot_interval['start_hour'] > $working_interval['start_hour'])
            || (($timeslot_interval['start_hour'] == $working_interval['start_hour'])
                && ($timeslot_interval['start_min'] >= $working_interval['start_min']));

        $is_timeslot_end_working = ($timeslot_interval['end_hour'] < $working_interval['end_hour'])
            || (($timeslot_interval['end_hour'] == $working_interval['end_hour'])
                && ($timeslot_interval['end_min'] <= $working_interval['end_min']));

        return $is_timeslot_start_working && $is_timeslot_end_working;
    }

    private function make_timeslot_display(array $interval): string
    {
        return sprintf("%02d:%02d-%02d:%02d", $interval['start_hour'], $interval['start_min'], $interval['end_hour'], $interval['end_min']);
    }

    /**
     * @param $lat
     * @param $long
     * @return bool
     */
    private function location_is_not_valid($lat, $long): bool
    {
        $azadi_coordinate = [
            'long' => 51.337762,
            'lat' => 35.699927
        ];

        return (!$lat || !$long || ($lat == $azadi_coordinate['lat'] && $long == $azadi_coordinate['long']));
    }

    private function process_timeslots($raw_timeslots, $pickup_location): array
    {
        $timeslots = [];

        foreach ($raw_timeslots as $raw_timeslot) {

            $timeslot = [
                'status' => null,
                'key' => null,
                'display' => null
            ];

            if ($raw_timeslot->isAvailable) {
                $payment_in_advance = $raw_timeslot->invoice->paymentInAdvance;

                if ($payment_in_advance > 0) {
                    $timeslot['status'] = 'balance_deficit';
                    $timeslots[] = $timeslot;
                    continue;
                }

                $timeslotId = $raw_timeslot->timeslotId;
                $timeslot_interval = $this->get_timeslot_interval($raw_timeslot);

                if ($pickup_location->has_hours()) {
                    $weekday = $this->get_weekday($raw_timeslot);
                    $working_hour = $pickup_location->get_weekly_hours($weekday);
                    if ($working_hour == '') {
                        $timeslot['status'] = 'store_unavailable';
                        $timeslots[] = $timeslot;
                        continue;
                    }
                    $working_interval = $this->get_working_interval($working_hour);
                    $is_timeslot_working = $this->is_timeslot_on_working($timeslot_interval, $working_interval);

                    if (!$is_timeslot_working) {
                        $timeslot['status'] = 'store_unavailable';
                        $timeslots[] = $timeslot;
                        continue;
                    }
                }

                $timeslot_display = $this->make_timeslot_display($timeslot_interval);

                $price = $raw_timeslot->invoice->amount;
                $displayText = $timeslot_display . ' (' . __('Price', 'woo-tapsi-delivery') . ': ' . $price . ' ' . __('Toman', 'woo-tapsi-delivery') . ')';
                $timeslot_key = $timeslotId . '--' . $price;

                $timeslot['status'] = 'ok';
                $timeslot['key'] = $timeslot_key;
                $timeslot['display'] = $displayText;

                $timeslots[] = $timeslot;
            }
        }

        return $timeslots;
    }

    /**
     * @param $time_slot_key
     * @return void
     */
    public function update_fee($time_slot_key): void
    {
        $selected_time_key_parts = explode("--", $time_slot_key);

        if (isset($selected_time_key_parts[1])) {
            $new_price = $selected_time_key_parts[1];
            $last_price = WC()->session->get('tapsi_delivery_fee');
            if ($new_price != $last_price) {
                WC()->session->set('tapsi_delivery_fee', $new_price);
            }
        }
    }

    /**
     * @return void
     */
    public function disable_problematic_filters(): void
    {
        try {
            remove_filter('woocommerce_form_field_select', array(\QuadLayers\WOOCCM\View\Frontend\Fields_Filter::instance(), 'custom_field'), 10);
        } catch (Error $e) {
        }
    }

    /**
     * @return void
     */
    public function enable_problematic_filters(): void
    {
        try {
            add_filter('woocommerce_form_field_select', array(\QuadLayers\WOOCCM\View\Frontend\Fields_Filter::instance(), 'custom_field'), 10, 4);
        } catch (Error $e) {
        }
    }

}
