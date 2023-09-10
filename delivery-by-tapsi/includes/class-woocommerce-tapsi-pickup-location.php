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
		'postcode'   => '',
		'country'    => '',
			'tuesday' => '',
			'wednesday' => '',
class Woocommerce_Tapsi_Pickup_Location
{

    protected $data = array(
        'ID' => 0,
        'name' => '',
        'address_1' => '',
        'address_2' => '',
        'city' => '',
        'state' => '',
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
            'address_2' => $post->address_2,
            'city' => $post->city,
            'state' => $post->state,
            'postcode' => $post->postcode,
            'country' => $post->country,
            'email' => $post->email,
            'phone' => $post->phone,
            'pickup_instructions' => $post->pickup_instructions,
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
            'address_2' => $this->data['address_2'],
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
        if (empty($this->data['address_1']) && empty($this->data['address_2'])) return '';
        $formatted = '';
        $formatted .= $this->data['address_1'];
        if ($this->data['address_2']) $formatted .= ', ' . $this->data['address_2'];
        $formatted .= ', ' . $this->data['city'] . ', ' . $this->data['state'] . ' ' . $this->data['postcode'];

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
        return $this->data['pickup_instructions'];
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
     * @return void
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
     * Gets values and labels for the available delivery days
     *
     * @return array Array with timestamp => labels
     */
    private function get_available_dates(): array
    {
        $days = array();
        $day_fmt = apply_filters('wcdd_delivery_day_format', 'D, n/j');

        $api_url = 'https://api.tapsi.ir/api/v1/delivery/available-dates';
        $request_url = $api_url;

        $token = 'accessToken='; // TODO
        $headers = ['cookie' => $token];
        $request_args = ['headers' => $headers];
        $response = wp_remote_get($request_url, $request_args);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if ($data) {
            if (isset($data->availableDatesTimestamp) && is_array($data->availableDatesTimestamp)) {
                foreach ($data->availableDatesTimestamp as $timestamp) {
                    $timestamp /= 1000;
                    $timeslot_display = date('m-d', $timestamp);
                    $days[$timestamp] = $timeslot_display;
                }
            } else {
                $days[0] = "Invalid response structure.";
            }
            return $days;
        } else {
            echo 'Failed to parse API response. Body: ' . $body;
        }

        return $days;
    }

    /**
     * Gets values and labels for the available delivery days for the location
     *
     * @return array Array with timestamp => labels
     */
    public function get_delivery_days()
    {
        return $this->get_available_dates();

        // Set up return array
        $days = array();
        $day_fmt = apply_filters('wcdd_delivery_day_format', 'D, n/j');

        // Set today and tomorrow so we can custom-label them
        $today = strtotime('today');
        $tomorrow = strtotime('tomorrow');

        // Set the first valid day based on lead time and hours
        $current_day = floor($this->get_next_valid_time() / DAY_IN_SECONDS) * DAY_IN_SECONDS;

        // Get the number of days out that we should allow orders. Defaults to 14
        $number_of_days = apply_filters('wcdd_delivery_number_of_days_ahead', intval(get_option('woocommerce_tapsi_number_of_days_ahead')) ?? 14);

        $i = 1;

        while ($i <= $number_of_days) {
            // Get a day string for the current weekday
            $day_of_week = date('l', $current_day);
            // Get the hours for the current day
            $day_hours = $this->get_weekly_hours($day_of_week);

            // Only add the day if it has hours assigned
            if (!empty($day_hours)) {
                if ($current_day == $today) {
                    // Custom label for Today
                    $days[$current_day] = __('Today', 'local-delivery-by-tapsi');
                } else if ($current_day == $tomorrow) {
                    // Custom label for Tomorrow
                    $days[$current_day] = __('Tomorrow', 'local-delivery-by-tapsi');
                } else {
                    // Otherwise, use a standard format, Tue, 5/7
                    $days[$current_day] = date($day_fmt, $current_day);
                }
            }
            $i++; // Iterate the counter
            $current_day += DAY_IN_SECONDS; // Iterate the day
        }

        // Return the array
        return $days;
    }

    /**
     * Given a datestamp, retrieve the user-selectable pickup time options for that date
     *
     * @param int $datestamp Date to get preview for
     * @return array Array containing timestamp keys and formatted time values
     */
    public function get_preview(int $datestamp): array
    {
        $days = array();

        $api_url = 'https://api.tapsi.ir/api/v1/delivery/order/preview';
        $originLat = 35.63064956665039;
        $originLong = 51.36489486694336;
        $destinationLat = 35.632899231302616;
        $destinationLong = 51.36615198055347;
        $dateTimestamp = $datestamp * 1000;

        $request_url = $api_url . '?originLat=' . $originLat . '&originLong=' . $originLong . '&destinationLat=' . $destinationLat . '&destinationLong=' . $destinationLong . '&dateTimestamp=' . $dateTimestamp;

        $token = 'accessToken=';  // TODO
        $headers = ['cookie' => $token];
        $request_args = ['headers' => $headers];
        $response = wp_remote_get($request_url, $request_args);

        if (is_wp_error($response)) {
            echo 'Failed to fetch delivery times. Please try again later.';
        } else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);

            if ($data) {
                $timeslots = $data->invoicePerTimeslots;

                if (!empty($timeslots)) {
                    foreach ($timeslots as $timeslot) {
                        $timeslotId = $timeslot->timeslotId;
                        $startTimestamp = $timeslot->startTimestamp / 1000;
                        $endTimestamp = $timeslot->endTimestamp / 1000;
                        $timeslot_display = date('H:i', $startTimestamp) . ' - ' . date('H:i', $endTimestamp);

                        if ($timeslot->isAvailable) {
                            $price = $timeslot->invoice->amount;
                            update_option('woocommerce_tapsi_quoted_fees', $price / 1000);
                            $displayText = $timeslot_display . ' (Price: ' . $price . ' toman)';
                            $option_attributes = 'value="' . $timeslotId . '"';

                        } else {
                            $displayText = $timeslot_display . ' is not available';
                            $option_attributes = 'disabled="disabled"';
                        }

                        $days[$timeslotId] = $displayText;
                    }
                } else {
                    echo 'No available delivery times found.';
                }
            } else {
                echo 'Failed to parse API response. Body: ' . $body;
            }
        }

        return $days;
    }

    /**
     * Given a datestamp, retrieve the user-selectable pickup time options for that date
     *
     * @param int $datestamp Date to get options for
     * @return array Array containing timestamp keys and formatted time values
     */
    public function get_delivery_times_for_date($datestamp)
    {
        return $this->get_preview($datestamp);

        if (is_null($datestamp)) $datestamp = time();

        // Get the day of the week for the datestamp
        $day_of_week = date('l', $datestamp);

        // Get the hours saved in the location for that day
        $day_hours = $this->get_weekly_hours($day_of_week);

        // Set up an empty array to store our times
        $options = array();

        // If there are no hours for this day, return false
        if (empty($day_hours)) return $options;

        // Set up access to hours functions
        $ddhours = new Woocommerce_Tapsi_Hours();

        // Get the hour ranges for the selected day
        $ranges = $ddhours->get_hour_ranges($day_hours);

        // Loop each range
        foreach ($ranges as $range) {
            // Fill an array with times between the start and end values of the range
            $range_values = $ddhours->fill_range($range[0], $range[1], $datestamp, $this->get_average_delivery_time());

            // Add the values to the return value
            $options = $options + $range_values;
        }

        // Return the pickup time options
        return $options;
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