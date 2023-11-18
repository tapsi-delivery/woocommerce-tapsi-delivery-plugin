<?php

/**
 * Tapsi Pickup_Location Object
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Tapsi Pickup Location Object
 *
 * Represents a Tapsi delivery, and contains all the datapoints
 * needed to create a delivery in the Drive API
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Pickup_Location
{

    protected $data = array(
        'ID' => 0,
        'name' => '',
        'address_1' => '',
        'latitude' => '',
        'longitude' => '',
        'should_hide' => true,
        'city' => '',
        'postcode' => '',
        'country' => '',
        'email' => '',
        'phone' => '',
        'pickup_instructions' => '',
        'has_hours' => false,
        'weekly_hours' => array(
            'sunday' => '',
            'monday' => '',
            'tuesday' => '',
            'wednesday' => '',
            'thursday' => '',
            'friday' => '',
            'saturday' => '',
        ),
        'special_hours' => array(),
        'enabled' => true,
    );

    /**
     * Create the pickup location object given the pickup location data
     *
     * @param int|WC_Order|array $data Post ID, Post Object, or array of pickup location data
     */
    public function __construct($data)
    {
        if (is_string($data)) $data = intval($data);
        if (is_int($data)) {
            $data = get_post($data); // Get the post object
        }
        if (is_a($data, 'WP_Post')) $this->create_from_post($data);
        else if (is_array($data)) $this->create_from_array($data);
        else return false;
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

    public function update($data)
    {
        $this->data = wp_parse_args($data, $this->data);
        return $this->save();
    }

    /**
     * Populate the delivery data from an order object
     *
     * @param WP_Post $post
     * @return void
     */
    public function create_from_post($post)
    {
        $data = array(
            'ID' => $post->ID,
            'name' => $post->post_title,
            'address_1' => $post->address_1,
            'latitude' => $post->latitude,
            'longitude' => $post->longitude,
            'should_hide' => $post->should_hide,
            'city' => $post->city,
            'state' => $post->state,
            'postcode' => $post->postcode,
            'country' => $post->country,
            'email' => $post->email,
            'phone' => $post->phone,
            'pickup_instructions' => '',
            'has_hours' => $post->has_hours,
            'weekly_hours' => $post->weekly_hours,
            'special_hours' => $post->special_hours,
            'enabled' => ('publish' == get_post_status($post)),
        );
        $this->create_from_array($data);
    }

    /**
     * Saves the delivery data to an order
     *
     * @return int Post ID of new post
     */
    public function save()
    {
        $postdata = array(
            'ID' => $this->get_id(),
            'post_title' => $this->get_name(),
            'post_type' => 'dd_pickup_location',
            'meta_input' => $this->get_data(),
            'post_status' => $this->get_post_status(),
        );
        $this->data['ID'] = wp_insert_post($postdata);
        return $this->get_id();
    }

    /**
     * Enable pickup from this location
     *
     * @return int Post ID of location
     */
    public function enable()
    {
        $this->data['enabled'] = true;
        return $this->save();
    }

    /**
     * Disable pickup from this location
     *
     * @return int Post ID of location
     */
    public function disable()
    {
        $this->data['enabled'] = false;
        return $this->save();
    }

    /**
     * Delete this location
     *
     * @return WP_Post|false|null Post data on success, false or null on failure.
     */
    public function delete()
    {
        return wp_delete_post($this->get_id(), true);
    }

    /**
     * Retrieve a JSON string representing this location
     *
     * @return string JSON data
     */
    public function json()
    {
        return json_encode($this->data);
    }

    /**
     * Retrieve all data as an array
     *
     * @return array Location data
     */
    public function get_data()
    {
        return $this->data;
    }

    /**
     * Retrieve location post ID
     *
     * @return int Post ID
     */
    public function get_id()
    {
        return $this->data['ID'];
    }

    /**
     * Retrieve location name
     *
     * @return string Location name
     */
    public function get_name()
    {
        return $this->data['name'];
    }

    /**
     * Retrieve location enabled status
     *
     * @return boolean True if enabled, false if disabled
     */
    public function is_enabled()
    {
        return $this->data['enabled'];
    }

    /**
     * Get the post status of this location
     *
     * @return string
     */
    public function get_post_status()
    {
        return $this->is_enabled() ? 'publish' : 'draft';
    }

    /**
     * Get the average delivery time in minutes for this location
     *
     * @return int
     */
    public function get_average_delivery_time()
    {
        return intval(apply_filters('wcdd_average_delivery_time', 20, $this->data['ID']));
    }

    /**
     * Get an array of address data for this location
     *
     * @return array Array containing address parts
     */
    public function get_address()
    {
        return array(
            'address_1' => $this->data['address_1'],
            'latitude' => $this->data['latitude'],
            'longitude' => $this->data['longitude'],
            'should_hide' => $this->data['should_hide'],
            'city' => $this->data['city'],
            'state' => $this->data['state'],
            'postcode' => $this->data['postcode'],
            'country' => $this->data['country'],
        );
    }

    /**
     * Get a Tapsi-formatted address for the location
     *
     * @return string Comma separated address parts
     */
    public function get_formatted_address()
    {
        if ($this->data['should_hide']) return __('Shop', 'woo-tapsi-delivery');
        if (empty($this->data['address_1'])) return '';
        $formatted = '';
        $formatted .= $this->data['address_1'];
        $formatted .= ', ' . $this->data['city'] . ', ' . __('Building Number', 'woo-tapsi-delivery')
            . $this->data['state'] . ', ' . __('Postcode', 'woo-tapsi-delivery') . $this->data['postcode'];

        return $formatted;
    }

    /**
     * Get the location email address
     *
     * @return string Email
     */
    public function get_email()
    {
        return $this->data['email'];
    }

    /**
     * Get the location's phone number
     *
     * @return string Phone number
     */
    public function get_phone_number()
    {
        return $this->data['phone'];
    }

    /**
     * Get the location's pickup instructions
     *
     * @return string Pickup instructions
     */
    public function get_pickup_instructions()
    {
        return '';
    }

    /**
     * Check if the location has hours enabled
     *
     * @return boolean True if the location is using custom hours
     */
    public function has_hours()
    {
        return $this->data['has_hours'];
    }

    /**
     * Get the weekly hours for the location, or default hours if location does not have hours enabled
     *
     * @param string $key Day of week for hours
     * @return mixed
     */
    public function get_weekly_hours($key)
    {
        $key = strtolower($key);
        if ($this->has_hours() && array_key_exists($key, $this->data['weekly_hours'])) return $this->data['weekly_hours'][$key];
        else return get_option("woocommerce_tapsi_{$key}_hours");
    }

    /**
     * Get the weekly hours for the location meta editor screen
     *
     * @param string $key Day of week for hours
     * @return void
     */
    public function get_weekly_hours_meta($key)
    {
        $key = strtolower($key);
        if (array_key_exists($key, $this->data['weekly_hours'])) return $this->data['weekly_hours'][$key];
        else return '';
    }

    /**
     * Gets values and labels for the available delivery days for the location
     *
     * @return array Array with timestamp => labels
     */
    public function get_delivery_days(): array
    {
        $raw_response = WCDD()->api->get_available_dates();
        $data = json_decode(wp_remote_retrieve_body($raw_response));

        $days = array();

        if ($data) {
            if (isset($data->availableDatesTimestamp) && is_array($data->availableDatesTimestamp)) {
                foreach ($data->availableDatesTimestamp as $int_timestamp) {
                    $int_timestamp /= 1000;
                    $obj_timestamp = new jDateTime('@' . $int_timestamp, true, 'Asia/Tehran');
                    $timeslot_display = $obj_timestamp->date('l - d F', $int_timestamp);

                    $days[$int_timestamp] = $timeslot_display;
                }
            } else {
                $days[0] = "Invalid response structure.";
            }
            return $days;
        } else {
            echo 'Failed to parse API response';
        }

        return $days;
    }

    /**
     * Check if the provided timestamp is a valid delivery time for this location
     *
     * @param int $timestamp Requested timestamp to check
     * @return boolean True if valid, false if invalid
     */
    public function is_valid_time($timestamp)
    {
        $day_of_week = date('l', $timestamp);
        $date = strtotime(date('Y-m-d', $timestamp));
        $day_hours = $this->get_weekly_hours($day_of_week);
        $lead_time = intval(get_option('woocommerce_tapsi_lead_time')) * MINUTE_IN_SECONDS;

        if (empty($day_hours)) return false;

        $ddhours = new Woocommerce_Tapsi_Hours();

        $ranges = $ddhours->get_hour_ranges($day_hours);

        foreach ($ranges as $range) {
            $open = $range[0] + $date + $lead_time;
            $close = $range[1] + $date;

            if ($timestamp >= $open && $timestamp <= $close) return true;
        }

        return false;
    }

    /**
     * For immediate deliveries, get the next valid delivery time.
     * Takes into account location opening hours and lead times
     *
     * @return int|false Timestamp for delivery time, or false if no delivery time is available
     */
    public function get_next_valid_time()
    {
        // Get the GMT offset from the options
        $gmt_offset = get_option('gmt_offset') * HOUR_IN_SECONDS;
        // We need to add the offset so the current LOCAL day is reported
        $today = time() + $gmt_offset;
        // Get the Lead time in seconds
        $lead_time = intval(get_option('woocommerce_tapsi_lead_time')) * MINUTE_IN_SECONDS;
        // Get the current day to start checking hours. Gets the time at midnight on the correct day
        $current_day = floor(($today + $lead_time) / DAY_IN_SECONDS) * DAY_IN_SECONDS;

        $ddhours = new Woocommerce_Tapsi_Hours();
        $number_of_days = apply_filters('wcdd_delivery_number_of_days_ahead', intval(get_option('woocommerce_tapsi_number_of_days_ahead')) ?? 14);

        // Set target delivery time to current time + lead time + average delivery time
        $time = time() + $lead_time + $gmt_offset + ($this->get_average_delivery_time() * MINUTE_IN_SECONDS);

        $i = 1;

        while ($i <= $number_of_days) {

            // Get a day string for the current weekday
            $day_of_week = date('l', $current_day);// + $gmt_offset );
            // Get the hours for the current day
            $day_hours = $this->get_weekly_hours($day_of_week);

            // Look at the hours for this day
            if (!empty($day_hours)) {

                // Get the hour ranges for the current day
                $ranges = $ddhours->get_hour_ranges($day_hours);

                // Loop each range
                foreach ($ranges as $range) {
                    // Get the opening and closing times for this range
                    $open = $range[0] + $current_day;
                    $close = $range[1] + $current_day;

                    // If the target time is between the opening and closing hours, we can deliver immediately
                    if ($time >= $open && $time <= $close) {
                        return $time - $gmt_offset;
                    }

                    // If the target time is less than the open and close time, we can delivery at the open time (plus lead time and average delivery time)
                    if ($time <= $open && $time <= $close) {
                        return $open - $gmt_offset + ($this->get_average_delivery_time() * MINUTE_IN_SECONDS);
                    }
                }

            }

            $i++; // Iterate the counter
            $current_day += DAY_IN_SECONDS; // Iterate the day

        }

        return false;

    }

}