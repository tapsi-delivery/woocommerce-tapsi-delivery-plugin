<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
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
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-tapsi-delivery-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-tapsi-delivery-admin.js', array('jquery', 'wp-i18n'), $this->version, false);

        wp_register_script('woo-tapsi-delivery-admin-locations', plugin_dir_url(__FILE__) . 'js/woo-tapsi-delivery-admin-locations.js', array('jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-backbone-modal'), $this->version, false);
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
     * Display a notice to administrators when Tapsi API is set to Sandbox mode
     *
     * @return void
     */
    public function admin_sandbox_notice()
    {
        if ('sandbox' == WCDD()->api->get_env()) {
            printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', __('Tapsi Delivery is in <strong>Sandbox mode</strong>. Switch to Production mode to enable deliveries.', 'woo-tapsi-delivery'));
        }
    }

    /**
     * Change the displayed meta key of the pickup location to something human readable
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
                case 'tapsi_support_reference':
                    $displayed_key = __('Support Reference', 'woo-tapsi-delivery');
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
        // if ( str_starts_with( $option, 'woocommerce_tapsi_' ) && str_ends_with( $option, '_hours' ) ) {
        // if ( strncmp( $option, 'woocommerce_tapsi_', strlen( $option ) ) === 0 && substr( $option, -6 ) === '_hours' ) {
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

        // Call the API to accept the delivery quote that was stored with the order
//		WCDD()->api->accept_delivery_quote( $delivery );

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

        // Add support reference
        if ($delivery->get_support_reference()) {
            $note .= sprintf(' Support Reference #%s.', $delivery->get_support_reference());
            $order->add_meta_data('tapsi_support_reference', $delivery->get_support_reference());
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
     * Adds a Tapsi tracking provider to the WooCommerce Shipment Tracking plugin
     *
     * @param array $providers Array of providers
     * @return array Filtered array of providers
     */
    public function wc_shipment_tracking_add_tapsi_provider($providers)
    {
        // $tracking_string = '%1$s';
        $tracking_string = 'https://www.tapsi.com/drive/portal/track/%1$s'; //?intl=en-US';

        $providers['United States']['Tapsi'] = $tracking_string;
        $providers['Canada']['Tapsi'] = $tracking_string;
        $providers['Japan']['Tapsi'] = $tracking_string;
        $providers['Australia']['Tapsi'] = $tracking_string;

        return $providers;

    }

    /**
     * Adds routing for custom REST API endpoint
     *
     * @return void
     */
    public function wc_tapsi_register_rest_route()
    {
        register_rest_route('wc/v3', '/tapsi/status_updated', [
            'methods' => 'POST',
            'callback' => array($this, 'status_updated'),
            'permission_callback' => array($this, 'authorize_tapsi_request'),
        ]);

        register_rest_route('wc/v3', '/tapsi/save_auth_header', [
            'methods' => 'POST',
            'callback' => array($this, 'save_auth_header'),
            'permission_callback' => array($this, 'authorize_save_auth_header'),
        ]);
    }

    /**
     * Permissions callback for users adding webhook creds
     *
     * @param HTTP_Request $request
     * @return bool True if user can perform this action
     */
    public function authorize_save_auth_header($request)
    {
        $body = json_decode($request->get_body());
        $perm = user_can($body->user_id, 'manage_woocommerce');
        return $perm;
    }

    /**
     * Authenticate the user accessing the custom endpoint
     *
     * @param array $request JSON request with updated Woocommerce_Tapsi_Delivery object data
     * @return bool True if user is authenticated, false otherwise
     */
    public function authorize_tapsi_request($request)
    {
        // get the headers and make sure this request is coming from tapsi before authenticating
        $headers = getallheaders();
        if ($headers && strpos($headers['User-Agent'], 'TapsiDriveWebhooks') !== false) {
            return current_user_can('manage_woocommerce');
        } else {
            return false;
        }
    }

    /**
     * Saves authorization header temporarily in a transient
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function save_auth_header($request)
    {
        $params = wp_parse_args($request->get_params());

        if ($params && $params['consumer_key'] && $params['consumer_secret']) {
            // save the data
            $header = base64_encode($params['consumer_key'] . ":" . $params['consumer_secret']);
            set_transient('woocommerce_tapsi_auth_header', "Bearer $header", 10 * MINUTE_IN_SECONDS);
            return true;
        }

        return false;
    }

    /**
     * Updates Order Statuses when Tapsi webhook is fired
     *
     * @param WP_REST_Request $request JSON request with updated Woocommerce_Tapsi_Delivery object data
     * @return string with success or error messages
     */
    public function status_updated(WP_REST_Request $request)
    {
        // parse out the request into an array
        $params = wp_parse_args($request->get_params());

        // check to make sure the request has both an external_delivery_id and event_name before moving forward
        if ($params && $params['external_delivery_id'] && $params['event_name']) {
            $external_delivery_id = $params['external_delivery_id'];

            WCDD()->log->info(__('Webhook: Received webhook event "' . $params['event_name'] . '" for ID ' . $external_delivery_id));

            // query the order with order item meta matching the external_delivery_id
            global $wpdb;
            $results = $wpdb->get_col(
                $wpdb->prepare(
                    "
					SELECT order_items.order_id
					FROM {$wpdb->prefix}woocommerce_order_items as order_items
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
					LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
					WHERE posts.post_type = 'shop_order'
					AND order_items.order_item_type = 'shipping'
					AND order_item_meta.meta_key = 'tapsi_external_delivery_id'
					AND order_item_meta.meta_value = %s
					LIMIT 1", $external_delivery_id
                )
            );

            // make sure we have found a post before moving forward
            if ($results) {
                $order = wc_get_order($results[0]);

                // Make sure we were able to get the order from it's ID
                if ($order) {
                    // Get the shipping method for the order
                    $methods = $order->get_shipping_methods();
                    $method = array_shift($methods);

                    // Get the delivery ID and object from the shipping method
                    $delivery = $method->get_meta("tapsi_delivery");

                    // if this order has tapsi delivery data, move forward with updating the delivery object, order status, and notes
                    if ($delivery) {
                        // Read the order status from the request, and update the order status/notes as needed
                        $dd_to_woo_status_map = array(
                            'DASHER_CONFIRMED' => array(
                                'note' => __('A Driver has accepted your delivery and is on the way to the pickup location.', 'woo-tapsi-delivery'),
                                'wc_status' => false,
                            ),
                            'DASHER_CONFIRMED_PICKUP_ARRIVAL' => array(
                                'note' => __('The Driver has confirmed that they arrived at the pickup location and are attempting to pick up the delivery.', 'woo-tapsi-delivery'),
                                'wc_status' => false,
                            ),
                            'DASHER_PICKED_UP' => array(
                                'note' => __('The Driver has picked up the delivery.', 'woo-tapsi-delivery'),
                                'wc_status' => 'wcdd-picked-up',
                            ),
                            'DASHER_CONFIRMED_DROPOFF_ARRIVAL' => array(
                                'note' => __('The Driver has confirmed that they arrived at the dropoff location.', 'woo-tapsi-delivery'),
                                'wc_status' => false,
                            ),
                            'DASHER_DROPPED_OFF' => array(
                                'note' => __('The Driver has dropped off the delivery at the dropoff location and the delivery is complete.', 'woo-tapsi-delivery'),
                                'wc_status' => 'completed',
                            ),
                            'DELIVERY_CANCELLED' => array(
                                'note' => __('The delivery has been cancelled.', 'woo-tapsi-delivery') . empty($params['cancellation_reason_message']) ? '' : sprintf(__('Reason: "%s"', 'woo-tapsi-delivery'), $params['cancellation_reason_message']),
                                'wc_status' => 'cancelled',
                            ),
                            'DELIVERY_RETURN_INITIALIZED' => array(
                                'note' => __('The Driver was unable to deliver your delivery to the dropoff location; they contacted support to arrange a return-to-pickup delivery and are returning to the pickup location.', 'woo-tapsi-delivery'),
                                'wc_status' => false,
                            ),
                            'DASHER_CONFIRMED_RETURN_ARRIVAL' => array(
                                'note' => __('The Driver has confirmed that they arrived at the pickup location and are attempting to return the delivery.', 'woo-tapsi-delivery'),
                                'wc_status' => false,
                            ),
                            'DELIVERY_RETURNED' => array(
                                'note' => __('The delivery has been returned successfully.', 'woo-tapsi-delivery'),
                                'wc_status' => 'wcdd-returned',
                            ),
                        );

                        // find the new status in the array map
                        if (array_key_exists($params['event_name'], $dd_to_woo_status_map)) {
                            $new_status_details = $dd_to_woo_status_map[$params['event_name']];
                        } else {
                            $new_status_details = false;
                        }
                        if ($new_status_details && $new_status_details['wc_status']) {
                            // status change event received from Tapsi, update the order status
                            $original_status = $order->get_status();
                            $order->update_status($new_status_details['wc_status'], $new_status_details['note']);
                        } else if ($new_status_details) {
                            // non status change event received from Tapsi, add a note to the order
                            $order->add_order_note($new_status_details['note']);
                        } else {
                            // status not found in the status map, do not make any update to the status or object
                            $note = sprintf(__('Tapsi status update: %s.', 'woo-tapsi-delivery'), $params['event_name']);
                            $order->add_order_note($note);
                        }

                        // Create delivery object based on the updated delivery data
                        $updated_delivery = new Woocommerce_Tapsi_Delivery($params);
                        if ($updated_delivery) {
                            $method->update_meta_data('tapsi_delivery', $updated_delivery);
                        }
                    } else {
                        WCDD()->log->error(sprintf(__('Webhook: Tapsi not found order #%s.', 'woo-tapsi-delivery'), $order->get_id()));
                        return false;
                    }

                    WCDD()->log->info(sprintf(__('Webhook: Order #%s updated successfully.', 'woo-tapsi-delivery'), $order->get_id()));
                    return true;
                }
            } else {
                WCDD()->log->error(sprintf(__('Webhook: Unable to find an order with Delivery ID %s.', 'woo-tapsi-delivery'), $external_delivery_id));
                return false;
            }
        } else {
            WCDD()->log->error(__('Webhook: Missing required parameters.', 'woo-tapsi-delivery'));
            WCDD()->log->error($request);
            return false;
        }
    }

    /**
     * Registers custom tapsi post statuses
     *
     * @return void
     */
    public function register_tapsi_order_statuses()
    {
        // register Delivery Picked Up status
        register_post_status('wc-wcdd-picked-up', array(
            'label' => __('Delivery Picked Up', 'woo-tapsi-delivery'),
            'public' => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list' => true,
            'exclude_from_search' => false
        ));
        // register Delivery Returned status
        register_post_status('wc-wcdd-returned', array(
            'label' => __('Delivery Returned', 'woo-tapsi-delivery'),
            'public' => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list' => true,
            'exclude_from_search' => false
        ));
    }

    /**
     * Adds Tapsi custom order statuses
     *
     * @param array $order_statuses Array with all existing order statuses
     * @return array with tapsi order statuses added
     */
    public function add_tapsi_order_statuses($order_statuses)
    {
        // add the custom order statuses to the woo drop down
        $order_statuses['wc-wcdd-picked-up'] = __('Delivery Picked Up', 'woo-tapsi-delivery');
        $order_statuses['wc-wcdd-returned'] = __('Delivery Returned', 'woo-tapsi-delivery');
        return $order_statuses;
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

        $pack = array('description' => $delivery->get_dropoff_instructions());  // TODO: add pickup instructions
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
}
