<?php

/**
 * Tapsi Delivery Object
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Tapsi Delivery Object
 *
 * Represents a Tapsi delivery, and contains all the datapoints
 * needed to create a delivery in the Drive API
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Delivery
{

    /**
     * Data store for the delivery
     * Overridden by API requests
     *
     * @var array
     */
    protected $data = array(
        // 'external_delivery_id' => '',
        // 'locale' => '',
        // 'pickup_address' => '',
        // 'pickup_business_name' => '',
        // 'pickup_phone_number' => '',
        // 'pickup_instructions' => '',
        // 'pickup_reference_tag' => '',
        // 'dropoff_address' => '',
        // 'dropoff_business_name' => '',
        // 'dropoff_phone_number' => '',
        // 'dropoff_instructions' => '',
        // 'dropoff_contact_given_name' => '',
        // 'dropoff_contact_family_name' => '',
        // 'dropoff_contact_send_notifications' => true,
        // 'order_value' => 0,
        // 'currency' => '',
        // 'contactless_dropoff' => false,
        // 'tip' => 0,
        // 'pickup_time' => '',
        // 'dropoff_time' => '',
        // 'pickup_window' =>
        // array (
        // 	'start_time' => '',
        // 	'end_time' => '',
        // ),
        // 'dropoff_window' =>
        // array (
        // 	'start_time' => '',
        // 	'end_time' => '',
        // ),
    );

    /**
     * Create the delivery object given the delivery data
     *
     * @param int|WC_Order|array $data Order ID, Order Object, or array of delivery data
     */
    public function __construct($data = null)
    {
        if (is_null($data)) $this->create_from_session();
        if (is_int($data)) {
            $data = wc_get_order($data); // Get the order object
        }
        if (is_a($data, 'WC_Order')) $this->create_from_order($data);
        else if (is_array($data)) $this->create_from_array($data);
    }

    /**
     * Populate the delivery data from an array
     *
     * @param array $data Array of delivery data
     * @return void
     */
    public function create_from_array($data)
    {
        $this->data = wp_parse_args($data, $this->data);
    }

    /**
     * Populate the delivery data from an order object
     *
     * @param [type] $order
     * @return void
     */
    public function create_from_order($order)
    {
        // get the metadata from the order here
    }

    /**
     * Create the delivery object based on data saved in the user's session
     *
     * @return void
     */
    public function create_from_session()
    {
        // Get the location ID from the session and set up the location object
        $location_id = WC()->session->get('tapsi_pickup_location');
        $location = new Woocommerce_Tapsi_Pickup_Location($location_id);

        // Get the tip
        if (get_option('woocommerce_tapsi_tipping') == 'enabled') {
            $tip = intval(doubleval(WC()->session->get('tapsi_tip_amount')) * 100);
        } else {
            $tip = 0;
        }

        // Get the time
        if (WC()->session->get('tapsi_delivery_type') == 'immediate') {
            $delivery_time = $location->get_next_valid_time();
        } else {
            $delivery_time = intval(WC()->session->get('tapsi_delivery_time'));
        }

        // get customer data from session
        $customer_information = wp_parse_args(WC()->session->get('tapsi_customer_information'), array(
            'company' => '',
            'first_name' => '',
            'last_name' => '',
            'address_1' => '',
            'address_2' => '',
            'state' => '',
            'postcode' => '',
            'phone' => '',
        ));

        $data = array(
            'external_delivery_id' => time() . WC()->cart->get_cart_hash(),
            'locale' => $this->get_locale(),
            'pickup_address' => $location->get_formatted_address(),
            'pickup_business_name' => $location->get_name(),
            'pickup_phone_number' => $location->get_phone_number(),
            'pickup_instructions' => $location->get_pickup_instructions() ? $location->get_pickup_instructions() : get_option('woocommerce_tapsi_default_pickup_instructions'),
            'pickup_reference_tag' => '',
            'dropoff_address' => $this->format_address($customer_information),
            'dropoff_business_name' => $customer_information['company'],
            'dropoff_phone_number' => $this->format_phone($customer_information['phone']),
            'dropoff_instructions' => WC()->session->get('tapsi_dropoff_instructions'),
            'dropoff_contact_given_name' => $customer_information['first_name'],
            'dropoff_contact_family_name' => $customer_information['last_name'],
            'dropoff_contact_send_notifications' => apply_filters('wcdd_contact_send_notifications', true),
            'order_value' => intval(WC()->cart->get_cart_contents_total() * 100),
            'currency' => get_woocommerce_currency(),
            'contactless_dropoff' => apply_filters('wcdd_contactless_dropoff', false),
            'tip' => $tip,
            'dropoff_time' => gmdate("Y-m-d\TH:i:s\Z", $delivery_time),
        );

        //does cart contain alcohol or tobacco
        $alcohol_tobacco_in_cart = apply_filters('alcohol_tobacco_in_cart', (get_option('woocommerce_tapsi_enable_alcohol_tobacco') == 'enabled' ? 1 : 0), WC()->cart->get_cart());

        if ($alcohol_tobacco_in_cart) {
            $data['order_contains'] = array('alcohol' => true);
            $data['action_if_undeliverable'] = 'return_to_pickup';
            $data['contactless_dropoff'] = false;
        }

        $this->create_from_array($data);
    }

    /**
     * Formats the shipping address for the Drive API
     * API expects comma-separated address parts
     *
     * @param array $customer_information Customer's address information array
     * @return string Formatted address
     */
    public function format_address($customer_information)
    {
        // Set up empty string for address
        $address = '';

        // Build the stringified address from array parts
        if (is_array($customer_information)) {
            if (!empty($customer_information['address_1'])) $address .= $customer_information['address_1'] . ', ';
            if (!empty($customer_information['address_2'])) $address .= $customer_information['address_2'] . ', ';
            if (!empty($customer_information['state'])) $address .= $customer_information['state'] . ' ';
            if (!empty($customer_information['postcode'])) $address .= $customer_information['postcode'];
        }

        // Return the formatted address
        return $address;
    }

    /**
     * Formats a phone number for the Drive API
     *
     * @param string $phone
     * @return string Formatted phone number
     */
    public function format_phone($phone)
    {
        if (!empty($phone)) $phone = wc_sanitize_phone_number($phone);
        else $phone = '18004444444'; // Drive API requires phone, use a dummy if it's not set yet

        if (strlen($phone) == 10) $phone = '1' . $phone; // TODO: use the get_locale function to check the length and format
        return $phone;
    }

    /**
     * Saves the delivery data to an order
     *
     * @return void
     */
    public function save_to_order()
    {
        // save the metadata to the order here
    }

    /**
     * JSON encode the data from this delivery
     *
     * @return string JSON encoded delivery data
     */
    public function json()
    {
        return json_encode($this->data);
    }

    /**
     * Get the external delivery ID Tapsi uses to identify this delivery
     *
     * @return string External delivery ID
     */
    public function get_id()
    {
        return $this->data['external_delivery_id'];
    }

    /**
     * Set the external delivery ID to an arbitrary value
     *
     * @param string $id External delivery ID
     * @return string External delivery ID
     */
    public function set_id($id)
    {
        $this->data['external_delivery_id'] = $id;
        return $this->data['external_delivery_id'];
    }

    /**
     * Get the rate quoted by Tapsi for the delivery
     *
     * @return int Quoted rate in cents
     */
    public function get_quoted_rate()
    {
        return get_option('woocommerce_tapsi_quoted_fees');
        return (array_key_exists('fee', $this->data) && !empty($this->data['fee'])) ? $this->data['fee'] / 100 : 0;
    }

    /**
     * Retrieve the fee associated with this delivery
     * Gets the value that should be charged to the user based on admin settings
     *
     * @return int Fee in cents
     */
    public function get_fee()
    {
        $quoted = $this->get_quoted_rate();

        $fees_mode = get_option('woocommerce_tapsi_fees_mode');
        $delivery_fee = get_option('woocommerce_tapsi_delivery_fee');

        if ($fees_mode == 'no_rate') {
            return 0;
        } else if ($fees_mode == 'quoted_rate') {
            return $quoted + (float)$delivery_fee;
        } else if ($fees_mode == 'fixed_rate') {
            return $delivery_fee;
        }

        // If the option isn't set, return 0
        return 0;
    }

    /**
     * Check if this is a valid delivery object for quoting
     *
     * @return boolean True if valid
     */
    public function is_valid()
    {
        return !empty($this->data['pickup_address']) && !empty($this->data['dropoff_address']) && strlen($this->data['dropoff_address']) > 3;
    }

    /**
     * Retrieve the tracking URL from the delivery data
     *
     * @return string|false Returns the URL if it exists, otherwise false
     */
    public function get_tracking_url()
    {
        if (array_key_exists('tracking_url', $this->data) && !empty($this->data['tracking_url'])) return $this->data['tracking_url'];
        else return false;
    }

    /**
     * Get the configured lead time for orders
     * Used in calculating pickup times and order windows
     */
    public function get_lead_time()
    {
        $prefix = "woocommerce_tapsi_";

        // Get the developer ID
        $lead_time = get_option($prefix . 'lead_time');

        if (!empty($lead_time)) return intval($lead_time) * MINUTE_IN_SECONDS;
        else return 0;
    }

    /**
     * Retrieve the estimated pickup time for the order.
     *
     * @param bool $timestamp True to return a UNIX timestamp, false to return raw value from API
     * @return string|false String with pickup time or false if no time is available
     */
    public function get_pickup_time($timestamp = false)
    {
        if (array_key_exists('pickup_time_estimated', $this->data) && !empty($this->data['pickup_time_estimated'])) {
            $pickup_time = $this->data['pickup_time_estimated'];
            if ($timestamp) {
                $pickup_time = strtotime($pickup_time);
            }
            return $pickup_time;
        } else {
            return false;
        }
    }

    /**
     * Retrieve the estimated dropoff time for the order
     *
     * @param bool $timestamp True to return a UNIX timestamp, false to return raw value from API
     * @return string|false String with dropoff time or false if no time is available
     */
    public function get_dropoff_time($timestamp = false)
    {
        if (array_key_exists('dropoff_time_estimated', $this->data) && !empty($this->data['dropoff_time_estimated'])) {
            $dropoff_time = $this->data['dropoff_time_estimated'];
            if ($timestamp) {
                $dropoff_time = strtotime($dropoff_time);
            }
            return $dropoff_time;
        } else {
            return false;
        }
    }

    /**
     * Get the locale for the delivery
     *
     * @param integer $user_id Optional user ID to pass to get_user_locale
     * @return string Locale string
     */
    public function get_locale($user_id = 0)
    {
        return str_replace('_', '-', get_user_locale($user_id));
    }

    /**
     * Get the support reference ID for the delivery
     *
     * @return string|false String with support ref if set, false if not found
     */
    public function get_support_reference()
    {
        if (array_key_exists('support_reference', $this->data) && !empty($this->data['support_reference'])) {
            return $this->data['support_reference'];
        } else {
            return false;
        }
    }

}