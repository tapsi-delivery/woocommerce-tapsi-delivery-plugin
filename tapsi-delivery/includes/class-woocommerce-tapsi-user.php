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
class Woocommerce_Tapsi_Pack_User
{

    protected $data = array(
        'ID' => 0,
        'name' => '',
        'enabled' => true,
        'tapsi_phone' => '',
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
            'enabled' => ('publish' == get_post_status($post)),
            'tapsi_phone' => $post->tapsi_phone
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
            'post_type' => 'dd_pack_user',
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
     * Get phone number of user
     *
     * @return string phone number of user
     */
    public function get_phone()
    {
        return $this->data['tapsi_phone'];
    }
}