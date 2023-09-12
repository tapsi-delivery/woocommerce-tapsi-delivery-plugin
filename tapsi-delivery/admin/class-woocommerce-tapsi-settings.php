<?php

/**
 * Define the settings for the plugin
 *
 * Adds WooCommerce settings and settings pages for the plugin
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Define the settings for the plugin
 *
 * Adds WooCommerce settings and settings pages for the plugin
 *
 * @since      1.0.0
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Settings extends WC_Settings_Page {

	public $id;

	public $label;

	/**
	 * Constructor
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->id = 'woocommerce-tapsi';
		$this->label = __( 'Tapsi Delivery', 'tapsi-delivery' );

		// Define all hooks instead of inheriting from parent
		add_filter( 'woocommerce_settings_tabs_array',        array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_sections_' . $this->id,      array( $this, 'output_sections' ) );
		add_action( 'woocommerce_settings_' . $this->id,      array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

	}

	/**
	 * Get the settings sections for the settings page
	 *
	 * @return void
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'Settings', 'tapsi-delivery' ),
			'webhooks' => __( 'Webhooks', 'tapsi-delivery' ),
			'locations' => __( 'Locations', 'tapsi-delivery' ),
            'login' => __( 'Login', 'tapsi-delivery' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get the settings for the current page
	 *
	 * @return void
	 */
	public function get_settings() {

		global $current_section;

		$prefix = 'woocommerce_tapsi_'; // used in the partials

		switch( $current_section ) {
			case 'locations':
			case 'webhooks':
				$settings = array(
					array(),
				);
				break;
			default:
				$settings = include 'partials/woocommerce-tapsi-admin-settings-main.php';
				break;
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Output the settings page
	 *
	 * @return void
	 */
	public function output() {
		global $current_section, $hide_save_button;
		if ( '' == $current_section ) {
			$settings = $this->get_settings();
			WC_Admin_Settings::output_fields( $settings );
		} elseif ( 'locations' == $current_section ) {
			if ( array_key_exists( 'location_id', $_GET ) ) {
				$this->output_location_edit_screen();
			} else {
				$hide_save_button = true;
				$this->output_locations_screen();
			}
		} elseif ( 'webhooks' == $current_section ) {
			$hide_save_button = true;
			$this->output_webhooks_screen();
		} elseif ( 'login' == $current_section ) {
//            $hide_save_button = true;

            if ( array_key_exists( 'tapsi_phone', $_GET ) ) {
                $this->output_enter_otp_screen();
            } else {
                $this->output_enter_phone_screen();
            }
        }
	}

	/**
	 * Save the settings
	 *
	 * @return void
	 */
	public function save() {
		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );

		// If we're updating a location, set the data for that post
		if ( isset( $_REQUEST['_update-location-nonce'] ) && wp_verify_nonce( $_REQUEST['_update-location-nonce'], 'woocommerce-tapsi-update-location' ) ) {
			$hours = new Woocommerce_Tapsi_Hours();
			
			$location_id = $_REQUEST['location_id'] == 'new' ? 'new' : intval( $_REQUEST['location_id'] );
			$location = new Woocommerce_Tapsi_Pickup_Location( $_REQUEST['location_id'] );

			$phone = str_replace( [ '-', '(', ')', ' ', '+' ], '', sanitize_text_field( $_REQUEST['location_phone'] ) );
			if ( strlen( $phone ) == 10 ) $phone = '1' . $phone;
			// $phone = '+' . $phone;

			$data = array(
				'ID'            => $location_id,
				'name'          => sanitize_text_field( $_REQUEST['location_name'] ),
				'enabled'       => isset( $_REQUEST['location_enabled'] ) ? true : false,
				'email'         => sanitize_email( $_REQUEST['location_email'] ),
				'phone'         => $phone,
				'address_1'     => sanitize_text_field( $_REQUEST['location_address_1'] ),
				'address_2'     => sanitize_text_field( $_REQUEST['location_address_2'] ),
				'city'          => sanitize_text_field( $_REQUEST['location_city'] ),
				'state'         => sanitize_text_field( $_REQUEST['location_state'] ),
				'postcode'      => sanitize_text_field( $_REQUEST['location_postcode'] ),
				'country'       => sanitize_text_field( $_REQUEST['location_country'] ),
				'pickup_instructions' => sanitize_textarea_field( $_REQUEST['location_pickup_instructions'] ),
				'has_hours'     => isset( $_REQUEST['location_hours_enabled'] ) ? true : false,
				'weekly_hours'  => array(
					'sunday'    => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_sunday_hours'] ) ),
					'monday'    => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_monday_hours'] ) ),
					'tuesday'   => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_tuesday_hours'] ) ),
					'wednesday' => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_wednesday_hours'] ) ),
					'thursday'  => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_thursday_hours'] ) ),
					'friday'    => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_friday_hours'] ) ),
					'saturday'  => $hours->normalize_hour_ranges( sanitize_text_field( $_REQUEST['location_saturday_hours'] ) ),
				),
			);
			// Update the location and get the location ID from the saved post
			$location_id = $location->update( $data );

			if ( $_REQUEST['location_id'] != $location_id ) {
				// If this was a new location, redirect to the newly created location
				wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations&location_id=' . $location_id ) );
			}
		} elseif ( isset( $_REQUEST['_update-phone-nonce'] ) && wp_verify_nonce( $_REQUEST['_update-phone-nonce'], 'woocommerce-tapsi-update-phone' ) ) {
            error_log('$_REQUEST: ' . print_r($_REQUEST, true));
            $tapsi_phone = sanitize_text_field($_REQUEST['tapsi_phone']);
            $location->set_phone( $tapsi_phone );
        }
	}

	/**
	 * Handle location deletion from the locations listing screen
	 *
	 * @return bool True on deletion, false otherwise
	 */
	protected function maybe_delete_location() {
		if ( array_key_exists( 'delete_location', $_GET ) && wp_verify_nonce( $_GET['_wpnonce'], 'delete_location' ) ) {
			// Delete the post, save the posts's data so we can display the title
			$deleted = wp_delete_post( intval( $_GET['delete_location'] ) );
			if ( $deleted ) {
				// If the post deletion was successful, show the message. (Otherwise this is probably a refresh)
				$message = sprintf( __( 'Location "%s" deleted.', 'tapsi-delivery' ), $deleted->post_title );
				printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );
				return true;
			}
		}
		return false;
	}

	/**
	 * Handle location hours toggle
	 *
	 * @return bool True on toggle, false otherwise
	 */
	protected function maybe_toggle_location_hours() {
		if ( array_key_exists( 'location_toggle_hours', $_GET ) && wp_verify_nonce( $_GET['_wpnonce'], 'location_toggle_hours' ) ) {
			// Get the post we're operating on
			$toggle_hours_location = get_post( intval( $_GET['location_toggle_hours'] ) );
			// Get the current status of the hours
			$enabled = $toggle_hours_location->has_hours;
			// Swap the status
			$updated = update_post_meta( $toggle_hours_location->ID, 'has_hours', !$enabled );
			// Display a message to the user
			if ( $updated ) {
				$message = sprintf( __('%s hours %s.', 'tapsi-delivery' ), $toggle_hours_location->post_title, !$enabled ? __( 'enabled', 'tapsi-delivery' ) : __( 'disabled', 'tapsi-delivery' ) );
				printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );
				return true;
			}
		}
		return false;
	}

	/**
	 * Handle location enabled toggle
	 *
	 * @return bool True on location toggled, false otherwise
	 */
	protected function maybe_toggle_location_enabled() {
		if ( array_key_exists( 'location_toggle_enabled', $_GET ) && wp_verify_nonce( $_GET['_wpnonce'], 'location_toggle_enabled' ) ) {
			// Get the post we're toggling
			$toggle_enabled_location = get_post( intval( $_GET['location_toggle_enabled'] ) );
			// Get the current post status
			$enabled = $toggle_enabled_location->post_status == 'publish';
			// Update the post with the new status
			$updated = wp_update_post( array( 'ID' => $toggle_enabled_location->ID, 'post_status' => !$enabled ? 'publish' : 'draft' ) );
			// Display a message to the user
			if ( $updated ) {
				$message = sprintf( '%s %s.', $toggle_enabled_location->post_title, !$enabled ? __( 'enabled', 'tapsi-delivery' ) : __( 'disabled', 'tapsi-delivery' ) );
				printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );
				return true;
			}
		}
		return false;
	}

	/**
	 * Output the locations table screen
	 * Handles localizing javascript for the display
	 * Also calls the handles for delete, hours toggle, and location toggle
	 *
	 * @return void
	 */
	public function output_locations_screen() {

		// Handle delete locations
		$this->maybe_delete_location();

		// Handle Hours toggle
		$this->maybe_toggle_location_hours();

		// Handle location enabled toggle
		$this->maybe_toggle_location_enabled();

		// Grab all the locations saved in the site
		$locations = $this->get_all_locations();

		// Array to store localized variables for JS
		$location_localized = array();

		// Loop the locations and convert them to the format the JS is expecting
		foreach ( $locations as $location ) {
			$location_localized[] = array(
				'location_id' => $location->get_id(),
				'location_name' => $location->get_name(),
				'formatted_location_address' => $location->get_formatted_address(),
				'location_hours' => $location->has_hours() ? 'enabled' : 'disabled',
				'location_hours_yesno' => $location->has_hours() ? 'Yes' : 'No',
				'location_enabled' => $location->is_enabled() ? 'enabled' : 'disabled',
				'location_enabled_yesno' => $location->is_enabled() ? 'Yes' : 'No',
			);
		}

		// Send our data over to the JavaScript
		wp_localize_script(
			'woocommerce-tapsi-admin-locations',
			'wcDDLocalizeScript',
			array(
				'locations'                          => $location_localized,
				'wc_tapsi_pickup_locations_nonce' => wp_create_nonce( 'wc_tapsi_pickup_locations_nonce' ),
				'strings'                            => array(
					'unload_confirmation_msg'        => __( 'Your changed data will be lost if you leave this page without saving.', 'tapsi-delivery' ),
					'save_changes_prompt'            => __( 'Do you wish to save your changes first? Your changed data will be discarded if you choose to cancel.', 'tapsi-delivery' ),
					'save_failed'                    => __( 'Your changes were not saved. Please retry.', 'tapsi-delivery' ),
					'delete_confirmation_msg'        => __( 'Are you sure you want to delete this pickup location?', 'tapsi-delivery' ),
					'add_method_failed'              => __( 'Pickup location could not be added. Please retry.', 'tapsi-delivery' ),
					'yes'                            => __( 'Yes', 'tapsi-delivery' ),
					'no'                             => __( 'No', 'tapsi-delivery' ),
					'default_location_name'          => __( 'Location', 'tapsi-delivery' ),
				),
			)
		);
		wp_enqueue_script( 'woocommerce-tapsi-admin-locations' );
		// Include the partial containing the table and templates
		include 'partials/woocommerce-tapsi-admin-settings-locations.php';
	}

	/**
	 * Show the individual location editor
	 *
	 * @return void
	 */
	public function output_location_edit_screen() {
		$location = new Woocommerce_Tapsi_Pickup_Location( intval( $_GET['location_id'] ) );
		include 'partials/woocommerce-tapsi-admin-settings-edit-location.php';
	}


    /**
     * Show the individual phone field
     *
     * @return void
     */
    public function output_enter_phone_screen() {
        include 'partials/woocommerce-tapsi-admin-settings-enter-phone.php';
    }

	/**
	 * Get all the locations and create them as objects in an array
	 *
	 * @return array Array of Woocommerce_Tapsi_Pickup_Location objects
	 */
	public function get_all_locations() {
		$locations = get_posts( array(
			'post_type' => 'dd_pickup_location',
			'post_status' => array( 'publish', 'draft' ),
			'orderby' => 'title',
			'order' => 'ASC',
			'numberposts' => -1,
		) );

		foreach ( $locations as &$location ) {
			$location = new Woocommerce_Tapsi_Pickup_Location( $location );
		}

		return $locations;
	}

	/**
	 * Display the screen to generate API credentials for Tapsi webhooks
	 *
	 * @return void
	 */
	public function output_webhooks_screen() {
		printf( '<h1>%s</h1>', __( 'Webhooks Configuration', 'tapsi-delivery' ) );
		$header = get_transient( 'woocommerce_tapsi_auth_header' );

		if ( ! empty( $header ) ) {
			printf( '<p>%s</p>', __( 'Your authorization header has been generated.', 'tapsi-delivery' ) );
			printf( '<h2>%s</h2>', __( 'This information will only be displayed once.', 'tapsi-delivery' ) );
			echo '<ol>';
			printf( '<li>%s</li>', __( 'Visit the <a target="_blank" href="https://developer.tapsi.com/portal/integration/drive/webhooks">Webhooks configuration in the Tapsi Developer Portal</a>.', 'tapsi-delivery' ) );
			printf( '<li>%s</li>', __( 'Click the button to configure a Sandbox or Production endpoint.', 'tapsi-delivery' ) );
			printf( '<li>%s</li>', __( 'Copy the values below into the form and click <strong>Configure Endpoint</strong>.', 'tapsi-delivery' ) );
			echo '</ol>';

			printf( '<p class="form-row"><label>%s</label><span class="woocommerce-input-wrapper"><input type="text" class="widefat input-text has-copy-button" readonly value="%s" /><button class="copy-button">%s</button></span></p>', __( 'Webhook Delivery URL', 'tapsi-delivery' ), rest_url( 'wc/v3/tapsi/status_updated' ), __( 'Copy', 'tapsi-delivery' ) );
			printf( '<p class="form-row"><label>%s</label><span class="woocommerce-input-wrapper"><input type="text" class="widefat input-text has-copy-button" readonly value="%s" /><button class="copy-button">%s</button></span></p>', __( 'Authentication Type', 'tapsi-delivery' ), 'Basic', __( 'Copy', 'tapsi-delivery' ) );
			printf( '<p class="form-row"><label>%s</label><span class="woocommerce-input-wrapper"><input type="text" class="widefat input-text has-copy-button" readonly value="%s" /><button class="copy-button">%s</button></span></p>', __( 'Authorization Header', 'tapsi-delivery' ), $header, __( 'Copy', 'tapsi-delivery' ) );
			delete_transient( 'woocommerce_tapsi_auth_header' );
		} else {
			
			printf( '<p>%s</p>', __( 'Tapsi webhooks are used to update your WooCommerce orders with delivery status from Tapsi in real-time as the order is being delivered.', 'tapsi-delivery' ) );
			printf( '<p>%s</p>', __( 'Use this page to generate WooCommerce credentials that you can paste into the Tapsi developer portal to connect your application.', 'tapsi-delivery' ) );
			printf( '<p>%s <a href="%s"><em>%s</em></a></p>', __( 'Previously generated credentials can be managed under', 'tapsi-delivery' ), admin_url( 'admin.php?page=wc-settings&tab=advanced&section=keys' ), __( 'WooCommerce Settings > Advanced > REST API', 'tapsi-delivery' ) );
			$auth_url = get_site_url() . '/wc-auth/v1/authorize';
			$auth_url = add_query_arg( array(
				'app_name' => 'Tapsi', 
				'scope' => 'write', 
				'user_id' => get_current_user_id(), 
				'return_url' => urlencode( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=webhooks' ) ),
				'callback_url' => urlencode( rest_url( 'wc/v3/tapsi/save_auth_header' ) ),
			), $auth_url );
			
			printf( '<a href="%s" class="button">%s</a>', esc_url( $auth_url ), __( 'Generate Credentials', 'tapsi-delivery' ) );	
		}
	}
}

return new Woocommerce_Tapsi_Settings();