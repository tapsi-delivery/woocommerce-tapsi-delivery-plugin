<?php

/**
 * Pickup Location Settings
 *
 * This file is used to output the fields for editing a pickup location
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/admin/partials
 */

/**
 * Output the Location Information section
 */
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations' ) . '">' . __( 'Locations' ) . '</a> > ' . __( 'Edit Location', 'local-delivery-by-doordash' ) . '</h2>';

echo '<h2>' . __( 'Location Information', 'local-delivery-by-doordash' ) . '</h2>';

	echo '<p>' . __( 'Enter the information for the location you.', 'local-delivery-by-doordash' ) . '</p>';

	echo '<section class="wc-doordash-location hidden">';
		woocommerce_form_field( 'location_id', array(
			'type' => 'hidden',
		), $location->get_id() );
	echo '</section>';

	echo '<section class="wc-doordash-location name">';
		woocommerce_form_field( 'location_name', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Location Name', 'local-delivery-by-doordash' ),
			'placeholder' => __( 'Location Name', 'local-delivery-by-doordash' ),
		), $location->get_name() );
	echo '</section>';

	echo '<section class="wc-doordash-location option">';
		woocommerce_form_field( 'location_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Enabled', 'local-delivery-by-doordash' ),
			'description' => __( 'Only enabled locations are able to be selected for DoorDash deliveries.', 'local-delivery-by-doordash' ),
		), $location->is_enabled() );
	echo '</section>';

	echo '<section class="wc-doordash-location info">';
		woocommerce_form_field( 'location_email', array(
			'type' => 'email',
			'label' => __( 'Email Address', 'local-delivery-by-doordash' ),
			'required' => true,
			'description' => __( 'New order notifications for this location will be sent to this email address.', 'local-delivery-by-doordash' )
		), $location->get_email() );

		woocommerce_form_field( 'location_phone', array(
			'type' => 'tel',
			'label' => __( 'Phone Number', 'local-delivery-by-doordash' ),
			'required' => true,
		), $location->get_phone_number() );

		woocommerce_form_field( 'location_pickup_instructions', array(
			'type' => 'text',
			'label' => __( 'Pickup Instructions', 'local-delivery-by-doordash' ),
			'description' => __( 'Pickup instructions for this location. Leave blank to use the default pickup instructions.', 'local-delivery-by-doordash' )
		), $location->get_pickup_instructions() );
	echo '</section>';

/**
 * Output the Location Address section
 */
echo '<h2>' . __( 'Address', 'local-delivery-by-doordash' ) . '</h2>';

	echo '<p>' . __( 'Enter the address for the location.', 'local-delivery-by-doordash' ) . '</p>';

	$address = $location->get_address();

	echo '<section class="wc-doordash-location address">';
		woocommerce_form_field( 'location_address_1', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Address 1', 'local-delivery-by-doordash' ),
		), $address['address_1'] );

		woocommerce_form_field( 'location_address_2', array(
			'type' => 'text',
			'label' => __( 'Address 2', 'local-delivery-by-doordash' ),
		), $address['address_2'] );

		woocommerce_form_field( 'location_city', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'City', 'local-delivery-by-doordash' ),
		), $address['city'] );

		woocommerce_form_field( 'location_state', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'State', 'local-delivery-by-doordash' ),
		), $address['state'] );

		woocommerce_form_field( 'location_postcode', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Postcode', 'local-delivery-by-doordash' ),
		), $address['postcode'] );

		woocommerce_form_field( 'location_country', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Country', 'local-delivery-by-doordash' ),
		), $address['country'] );
	echo '</section>';

/**
 * Output the Location Hours section
 */
echo '<h2>' . __( 'Hours', 'local-delivery-by-doordash' ) . '</h2>';

	echo '<p>' . __( 'Enter the hours for the location.', 'local-delivery-by-doordash' ) . '</p>';

	echo '<section class="wc-doordash-location option hours-wrapper">';
		woocommerce_form_field( 'location_hours_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Customize hours for this location', 'local-delivery-by-doordash' ),
		), $location->has_hours() );

		// TODO: This should follow the start_of_week option
		$weekdays = array(
			'sunday'    => __( 'Sunday', 'local-delivery-by-doordash' ),
			'monday'    => __( 'Monday', 'local-delivery-by-doordash' ),
			'tuesday'   => __( 'Tuesday', 'local-delivery-by-doordash' ),
			'wednesday' => __( 'Wednesday', 'local-delivery-by-doordash' ),
			'thursday'  => __( 'Thursday', 'local-delivery-by-doordash' ),
			'friday'    => __( 'Friday', 'local-delivery-by-doordash' ),
			'saturday'  => __( 'Saturday', 'local-delivery-by-doordash' ),
		);

		echo '<section class="wc-doordash-location hours">';
			foreach ( $weekdays as $key => $label ) {
				woocommerce_form_field( "location_{$key}_hours", array(
					'type' => 'text',
					'label' => sprintf( __( '%1$s Hours' ), $label ),
				), $location->get_weekly_hours_meta( $key ) );
			}
		echo '</section>';
	echo '</section>';

/**
 * Nonce field
 */
wp_nonce_field( 'woocommerce-doordash-update-location', '_update-location-nonce' );