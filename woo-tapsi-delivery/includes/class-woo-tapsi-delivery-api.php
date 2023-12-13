<?php

/**
 * Tapsi API
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Tapsi API
 *
 * Contains functionality to create and accept delivery quotes,
 * and create, read, update, and delete deliveries in the Drive API.
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_API
{
    protected string $cookie;
    protected string $x_agw_user_role = 'SCHEDULED_DELIVERY_SENDER';
    protected string $x_agent = 'v0.1.0|WOOCOMMERCE_PLUGIN|WEB|0.1.10';
    protected string $base_url = "https://api.tapsi.ir/api/";

    public function __construct()
    {
        $this->get_keys();
    }

    protected function get_keys(): bool
    {
        // If the JWT already exists we're set
        if (!empty($this->cookie)) return true;

        $this->get_cookie();

        $encryption = new Woocommerce_Tapsi_Encryption();
        if ($encryption->is_encrypted($this->cookie)) {
            $this->cookie = $encryption->decrypt($this->cookie);
        }

        if (empty($this->cookie)) {
            return false;
        }

        return true;
    }


    /**
     * Gets values and labels for the available delivery days
     *
     * @return array Array with timestamp => labels
     */
    public function get_available_dates()
    {
        $request_path = 'v1/delivery/available-dates';
        $request_args = array('method' => 'GET', 'timeout' => 20);
        return $this->client_request($request_path, $request_args);
    }


    /**
     * @return bool
     */
    public function is_token_valid(): bool
    {
        $request_path = 'v1/delivery/available-dates';
        $request_args = array('method' => 'GET', 'timeout' => 20);
        $response = $this->request_with_credentials($request_path, $request_args);
        $response_code = wp_remote_retrieve_response_code($response);
        return $response_code == 200;
    }


    /**
     * Given a datestamp, retrieve the user-selectable pickup time options for that date
     *
     * @param int $date_timestamp Date to get preview for
     * @return array|false|WP_Error Array containing timestamp keys and formatted time values
     */
    public function get_preview(float $origin_lat, float $origin_long, float $destination_lat, float $destination_long, int $date_timestamp): array
    {

        $api_url = 'v1/delivery/order/preview';
        $request_path = $api_url . '?originLat=' . $origin_lat . '&originLong=' . $origin_long . '&destinationLat=' . $destination_lat . '&destinationLong=' . $destination_long . '&dateTimestamp=' . $date_timestamp;
        $request_args = array('method' => 'GET', 'timeout' => 20);
        return $this->client_request($request_path, $request_args);
    }


    /**
     * Send a message to phone number of user, containing OTP
     *
     * @param string $phone phone number of user
     * @return object containing `result` key, and value of `result` would be `OK` on success.
     */
    public function send_otp(string $phone): object
    {

        $request_path = 'v2/user';
        $request_body = array(
            'credential' => array(
                'phoneNumber' => $phone,
                'role' => 'SCHEDULED_DELIVERY_SENDER'
            )
        );
        $request_args = array(
            'method' => 'POST',
            'body' => json_encode($request_body),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'timeout' => 20
        );

        return $this->admin_request($request_path, $request_args);
    }


    /**
     * confirms if OTP is correct or not
     *
     * @param string $phone
     * @param string $otp
     * @return array|object|WP_Error containing `result` key, and value of `result` would be `OK` on success.
     */
    public function confirm_otp(string $phone, string $otp)
    {
        $request_path = 'v2.2/user/confirm/web';
        $request_body = array(
            'credential' => array(
                'phoneNumber' => $phone,
                'role' => 'SCHEDULED_DELIVERY_SENDER'
            ),
            'confirmation' => array(
                'code' => $otp
            ),
            'deviceInfo' => array(
                'product' => 'SCHEDULED_DELIVERY_SENDER'
            )
        );

        $request_args = array(
            'method' => 'POST',
            'body' => json_encode($request_body),
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-agent' => 'v0.1.0|SCHEDULED_DELIVERY_SENDER|WEB|0.1.10'
            ),
            'timeout' => 20
        );

        return $this->request_token($request_path, $request_args);
    }


    public function submit_delivery_order(array $receiver, array $sender, array $pack, string $time_slot_id, string $token): array
    {
        $request_path = 'v1/delivery/order/submit';
        $request_body = array(
            'receiver' => $receiver,
            'sender' => $sender,
            'pack' => $pack,
            'timeslotId' => $time_slot_id,
            'token' => $token
        );
        $request_args = array(
            'method' => 'POST',
            'body' => json_encode($request_body),
            'timeout' => 20
        );

        return $this->client_request($request_path, $request_args);
    }

    /**
     * @param string $request_url
     * @param array $request_args
     * @return array|WP_Error
     */
    public function remote_request(string $request_url, array $request_args)
    {
        // Log the request
        WCDD()->log->debug('$request_url', $request_url);
        WCDD()->log->debug('$request_args', $request_args);

        // Run the remote request
        $response = wp_remote_request($request_url, $request_args);

        // Log the response
        try {
            WCDD()->log->debug('decoded $response', json_decode(wp_remote_retrieve_body($response)));
        } catch (Exception $e) {
            WCDD()->log->debug('raw $response', $response);
        }

        return $response;
    }

    /**
     * @return string saved cookie
     */
    public function get_cookie()
    {
        if (!empty($this->cookie)) return $this->cookie;
        $this->cookie = get_option('woocommerce_tapsi_cookie');
        return $this->cookie;
    }


    /**
     * Sends a request for Admin, like requesting an OTP or verifying it
     *
     * @param string $request_path The path to direct the request to
     * @param array $request_args An array of arguments
     * @return object|WP_Error The response array or a WP_Error on failure
     */
    public function admin_request(string $request_path, array $request_args)
    {
        $request_url = $this->base_url . $request_path;

        $response = $this->remote_request($request_url, $request_args);

        // Log WP error
        if (is_wp_error($response)) {
            return $response;
        } else {
            $response = json_decode(wp_remote_retrieve_body($response));
        }

        // Return the response object
        return $response;
    }

    /**
     * Sends a request for Admin, like requesting an OTP or verifying it
     *
     * @param string $request_path The path to direct the request to
     * @param array $request_args An array of arguments
     * @return object|WP_Error The response array or a WP_Error on failure
     */
    public function request_token(string $request_path, array $request_args)
    {

        $request_url = $this->base_url . $request_path;

        $response = $this->remote_request($request_url, $request_args);

        if (is_wp_error($response)) {
            return $response;
        }

        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code == 200) {
            $this->cookie = $this->extract_cookie($response);
            update_option('woocommerce_tapsi_cookie', $this->cookie, 'yes');
        }

        try {
            $response = json_decode(wp_remote_retrieve_body($response));
        } catch (Exception $e) {
        }


        return $response;
    }


    /**
     * Sends a request to the Drive API
     *
     * @param string $request_path The path to direct the request to
     * @param array $request_args An array of arguments
     * @return array|WP_Error The response array or a WP_Error on failure
     */
    public function client_request(string $request_path, array $request_args)
    {
        $response = $this->request_with_credentials($request_path, $request_args);

        // Log WP error
        if (is_wp_error($response)) {
            WCDD()->log->error('Error performing request to %s', $response);
            return $response;
        }

        // Log HTTP error
        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code !== 200) {

            $body = json_decode(wp_remote_retrieve_body($response));

            switch ($response_code) {
                case 400:
                    // The request was syntactically invalid
                    if (isset($body->field_errors[0]->field) && $body->field_errors[0]->field == 'dropoff_phone_number') {
                        wc_add_notice(__('Tapsi: ', 'woo-tapsi-delivery') . __(' Make sure the phone number is valid and belongs to the same country as the address.', 'woo-tapsi-delivery'), 'notice');
                    } elseif (isset($body->field_errors[0]->field) && $body->field_errors[0]->field == 'dropoff_address') {
                        wc_add_notice(__('Tapsi: ', 'woo-tapsi-delivery') . __('Delivery is not available from this pickup location to your selected address. Please enter another dropoff address or select a different delivery method.', 'woo-tapsi-delivery'), 'notice');
                    } else {
                        wc_add_notice(__('Tapsi: ', 'woo-tapsi-delivery') . __($body->details[0]->message, 'woo-tapsi-delivery'), 'notice');
                    }

                    break;
                case 401:
                case 403:
                    wc_add_notice(__('Tapsi: Authentication Error. Call shopper to authenticate again on Tapsi.', 'woo-tapsi-delivery'), 'notice');
                    break;
                case 404:
                    // Resource doesn't exist
                    wc_add_notice(__('Tapsi: Resource does not exist', 'woo-tapsi-delivery'), 'notice');
                    break;
                case 409:
                    // System state doesn't allow operation to proceed
                    // wc_add_notice( __( 'Tapsi: ', 'woo-tapsi-delivery' ) . __( $body->message, 'woo-tapsi-delivery' ), 'notice' );
                    break;
                case 422:
                    // Logical validation error
                    wc_add_notice(__('Tapsi: ', 'woo-tapsi-delivery') . __('Delivery is not available from this pickup location to your selected address. Please enter another dropoff address or select a different delivery method.', 'woo-tapsi-delivery'), 'notice');
                    // wc_add_notice( wc_print_r( $body->message, true ) );
                    break;
                case 429:
                    // Too many requests
                    wc_add_notice(__('Tapsi: Too many requests', 'woo-tapsi-delivery'), 'notice');
                    break;
            }

            // Add a notice for server connectivity issues
            if (500 >= $response_code && $response_code > 600) {
                wc_add_notice(__('There was a problem communicating with Tapsi. Please try again later.', 'woo-tapsi-delivery'), 'notice');
            }
        }

        // Return the response object
        return $response;
    }


    /**
     * Sends a request to the Drive API
     *
     * @param string $request_path The path to direct the request to
     * @param array $request_args An array of arguments
     * @return array|WP_Error The response array or a WP_Error on failure
     */
    public function request_with_credentials(string $request_path, array $request_args, bool $refresh_token_on_failure = true)
    {
        // Before making a request, make sure we have keys
        if (!$this->get_keys()) {
            $this->__construct();
        }

        // Set the URL for the request based on the request path and the API url
        $request_url = $this->base_url . $request_path;

        // Set up default arguments for WP Remote Request
        $default_args = array(
            'headers' => array(
                'cookie' => $this->get_cookie(),
                'Content-Type' => 'application/json',
                'x-agw-user-role' => $this->x_agw_user_role,
                'X-Agent' => $this->x_agent
            )
        );

        // Combine the defaults with the passed arguments
        $parsed_request_args = wp_parse_args($request_args, $default_args);
        $response = $this->remote_request($request_url, $parsed_request_args);
        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code == 401 || $response_code == 403) {
            if ($refresh_token_on_failure) {
                $this->refresh_tokens();
                $response = $this->request_with_credentials($request_path, $request_args, false);
            }
        }

        return $response;
    }

    /**
     * @param $response
     * @return string
     */
    public function extract_cookie($response): string
    {
        $headers = wp_remote_retrieve_headers($response);
        $set_cookie = $headers['set-cookie'];

        $access_token_details = explode(';', $set_cookie[0]);
        $refresh_token_details = explode(';', $set_cookie[1]);

        $access_token = '';
        $refresh_token = '';

        foreach ($access_token_details as $access_token_detail) {
            if (str_starts_with($access_token_detail, 'accessToken')) {
                $access_token = $access_token_detail;
            }
        }

        foreach ($refresh_token_details as $refresh_token_detail) {
            if (str_starts_with($refresh_token_detail, 'refreshToken')) {
                $refresh_token = $refresh_token_detail;
            }
        }

        if ($access_token != '' && $refresh_token != '') {
            return $access_token . ';' . $refresh_token . ';';
        } else {
            // TODO: raise error here
            return '';
        }
    }

    /**
     * Sends a request for Admin, like requesting an OTP or verifying it
     *
     * @return object|WP_Error The response array or a WP_Error on failure
     */
    private function refresh_tokens()
    {
        $request_path = 'v2/user/accessToken/web';

        $request_args = array(
            'method' => 'GET',
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-agent' => $this->x_agent,
                'cookie' => $this->get_cookie()
            ),
            'timeout' => 20
        );

        return $this->request_token($request_path, $request_args);
    }
}
