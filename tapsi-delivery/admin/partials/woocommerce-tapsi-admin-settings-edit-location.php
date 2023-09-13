<?php

/**
 * Pickup Location Settings
 *
 * This file is used to output the fields for editing a pickup location
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin/partials
 */

/**
 * Output the Location Information section
 */
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations' ) . '">' . __( 'Locations' ) . '</a> > ' . __( 'Edit Location', 'tapsi-delivery' ) . '</h2>';

echo '<h2>' . __( 'Location Information', 'tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the information for the location you.', 'tapsi-delivery' ) . '</p>';

	echo '<section class="wc-tapsi-location hidden">';
		woocommerce_form_field( 'location_id', array(
			'type' => 'hidden',
		), $location->get_id() );
	echo '</section>';

	echo '<section class="wc-tapsi-location name">';
		woocommerce_form_field( 'location_name', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Location Name', 'tapsi-delivery' ),
			'placeholder' => __( 'Location Name', 'tapsi-delivery' ),
		), $location->get_name() );
	echo '</section>';

	echo '<section class="wc-tapsi-location option">';
		woocommerce_form_field( 'location_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Enabled', 'tapsi-delivery' ),
			'description' => __( 'Only enabled locations are able to be selected for Tapsi deliveries.', 'tapsi-delivery' ),
		), $location->is_enabled() );
	echo '</section>';

	echo '<section class="wc-tapsi-location info">';
		woocommerce_form_field( 'location_email', array(
			'type' => 'email',
			'label' => __( 'Email Address', 'tapsi-delivery' ),
			'required' => true,
			'description' => __( 'New order notifications for this location will be sent to this email address.', 'tapsi-delivery' )
		), $location->get_email() );

		woocommerce_form_field( 'location_phone', array(
			'type' => 'tel',
			'label' => __( 'Phone Number', 'tapsi-delivery' ),
			'required' => true,
		), $location->get_phone_number() );

		woocommerce_form_field( 'location_pickup_instructions', array(
			'type' => 'text',
			'label' => __( 'Pickup Instructions', 'tapsi-delivery' ),
			'description' => __( 'Pickup instructions for this location. Leave blank to use the default pickup instructions.', 'tapsi-delivery' )
		), $location->get_pickup_instructions() );
	echo '</section>';

/**
 * Output the Location Address section
 */
echo '<h2>' . __( 'Address', 'tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the address for the location.', 'tapsi-delivery' ) . '</p>';

	$address = $location->get_address();

	include 'wctd-taps-pack-maplibre-map.php';

	echo '<section class="wc-tapsi-location address">';
		woocommerce_form_field( 'location_address_1', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Address 1', 'tapsi-delivery' ),
		), $address['address_1'] );

		woocommerce_form_field( 'location_city', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'City', 'tapsi-delivery' ),
		), $address['city'] );

		woocommerce_form_field( 'location_state', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'State', 'tapsi-delivery' ),
		), $address['state'] );

		woocommerce_form_field( 'location_postcode', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Postcode', 'tapsi-delivery' ),
		), $address['postcode'] );

		woocommerce_form_field( 'location_country', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Country', 'tapsi-delivery' ),
		), $address['country'] );
	echo '</section>';

/**
 * Output the Location Hours section
 */
echo '<h2>' . __( 'Hours', 'tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the hours for the location.', 'tapsi-delivery' ) . '</p>';

	echo '<section class="wc-tapsi-location option hours-wrapper">';
		woocommerce_form_field( 'location_hours_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Customize hours for this location', 'tapsi-delivery' ),
		), $location->has_hours() );

		// TODO: This should follow the start_of_week option
		$weekdays = array(
			'sunday'    => __( 'Sunday', 'tapsi-delivery' ),
			'monday'    => __( 'Monday', 'tapsi-delivery' ),
			'tuesday'   => __( 'Tuesday', 'tapsi-delivery' ),
			'wednesday' => __( 'Wednesday', 'tapsi-delivery' ),
			'thursday'  => __( 'Thursday', 'tapsi-delivery' ),
			'friday'    => __( 'Friday', 'tapsi-delivery' ),
			'saturday'  => __( 'Saturday', 'tapsi-delivery' ),
		);

		echo '<section class="wc-tapsi-location hours">';
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
wp_nonce_field( 'woocommerce-tapsi-update-location', '_update-location-nonce' );