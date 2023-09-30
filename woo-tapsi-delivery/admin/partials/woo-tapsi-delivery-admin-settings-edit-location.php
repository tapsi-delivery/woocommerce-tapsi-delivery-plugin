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
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woo-tapsi-delivery&section=locations' ) . '">' . __( 'Locations' ) . '</a> > ' . __( 'Edit Location', 'woo-tapsi-delivery' ) . '</h2>';

$address = $location->get_address();
if (  $_GET['location_id'] !== 'new' && (!$address['latitude'] || $address['latitude'] === 'undefined' || !$address['longitude'] || $address['longitude'] === 'undefined')) {
	echo '<p class="wctd-tapsi-pack-coordinate-error">'. __('There is something wrong with your coordinate or this address may be new to Tapsi Pack! Please find the exact coordinate of your address on the map and submit the changes', 'woo-tapsi-delivery') .'</p>';
}
echo '<h2>' . __( 'Location Information', 'woo-tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the information for the location you.', 'woo-tapsi-delivery' ) . '</p>';

	echo '<section class="wc-tapsi-location hidden">';
		woocommerce_form_field( 'location_id', array(
			'type' => 'hidden',
		), $location->get_id() );
	echo '</section>';

	echo '<section class="wc-tapsi-location name">';
		woocommerce_form_field( 'location_name', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Location Name', 'woo-tapsi-delivery' ),
			'placeholder' => __( 'Location Name', 'woo-tapsi-delivery' ),
		), $location->get_name() );
	echo '</section>';

	echo '<section class="wc-tapsi-location option">';
		woocommerce_form_field( 'location_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Enabled', 'woo-tapsi-delivery' ),
			'description' => __( 'Only enabled locations are able to be selected for Tapsi deliveries.', 'woo-tapsi-delivery' ),
		), $location->is_enabled() );
	echo '</section>';

	echo '<section class="wc-tapsi-location info">';
		woocommerce_form_field( 'location_email', array(
			'type' => 'email',
			'label' => __( 'Email Address', 'woo-tapsi-delivery' ),
			'required' => true,
			'description' => __( 'New order notifications for this location will be sent to this email address.', 'woo-tapsi-delivery' )
		), $location->get_email() );

		woocommerce_form_field( 'location_phone', array(
			'type' => 'tel',
			'label' => __( 'Phone Number', 'woo-tapsi-delivery' ),
			'required' => true,
		), $location->get_phone_number() );

//		woocommerce_form_field( 'location_pickup_instructions', array(
//			'type' => 'text',
//			'label' => __( 'Pickup Instructions', 'woo-tapsi-delivery' ),
//			'description' => __( 'Pickup instructions for this location. Leave blank to use the default pickup instructions.', 'woo-tapsi-delivery' )
//		), $location->get_pickup_instructions() );

	echo '</section>';

/**
 * Output the Location Address section
 */
echo '<h2>' . __( 'Address', 'woo-tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the address for the location.', 'woo-tapsi-delivery' ) . '</p>';

	$address = $location->get_address();
	$azadi_coordinate = array(51.337762, 35.699927);

	echo '<section class="wc-tapsi-location address">';

		woocommerce_form_field( 'location_lat', array(
			'type' => 'hidden',
			'required' => true,
			'id' => 'wctd-tapsi-pack-maplibre-map-location-form-lat-field-id',
		), !$address['latitude'] || $address['latitude'] === 'undefined' ?  $azadi_coordinate[1] : $address['latitude']);

		woocommerce_form_field( 'location_lng', array(
			'type' => 'hidden',
			'required' => true,
			'id' => 'wctd-tapsi-pack-maplibre-map-location-form-lng-field-id',
		), !$address['longitude'] || $address['longitude'] === 'undefined' ?  $azadi_coordinate[0] : $address['longitude']);

		woocommerce_form_field( 'location_address_1', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Address', 'woo-tapsi-delivery' ),
		), $address['address_1'] );

		woocommerce_form_field( 'location_city', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'City', 'woo-tapsi-delivery' ),
		), $address['city'] );

		woocommerce_form_field( 'location_state', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'State', 'woo-tapsi-delivery' ),
		), $address['state'] );

		woocommerce_form_field( 'location_postcode', array(
			'type' => 'text',
			'label' => __( 'Postcode', 'woo-tapsi-delivery' ),
		), $address['postcode'] );

		woocommerce_form_field( 'location_country', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Country', 'woo-tapsi-delivery' ),
		), $address['country'] );
	echo '</section>';

	include 'wctd-taps-pack-maplibre-map.php';


	echo '<section class="wc-tapsi-location option">';
		woocommerce_form_field( 'hide_location_address', array(
			'type' => 'checkbox',
			'label' => __( 'Hide', 'woo-tapsi-delivery' ),
			'description' => __( 'The exact address of your shop will be hidden to the customers but they can still see the coordinates of your shop in the track link that is sent to them.', 'woo-tapsi-delivery' ),
		), $address['should_hide'] );
	echo '</section>';


/**
 * Output the Location Hours section
 */
echo '<h2>' . __( 'Hours', 'woo-tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the hours for the location.', 'woo-tapsi-delivery' ) . '</p>';

	echo '<section class="wc-tapsi-location option hours-wrapper">';
		woocommerce_form_field( 'location_hours_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Customize hours for this location', 'woo-tapsi-delivery' ),
		), $location->has_hours() );

		// TODO: This should follow the start_of_week option
		$weekdays = array(
			'sunday'    => __( 'Sunday', 'woo-tapsi-delivery' ),
			'monday'    => __( 'Monday', 'woo-tapsi-delivery' ),
			'tuesday'   => __( 'Tuesday', 'woo-tapsi-delivery' ),
			'wednesday' => __( 'Wednesday', 'woo-tapsi-delivery' ),
			'thursday'  => __( 'Thursday', 'woo-tapsi-delivery' ),
			'friday'    => __( 'Friday', 'woo-tapsi-delivery' ),
			'saturday'  => __( 'Saturday', 'woo-tapsi-delivery' ),
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
wp_nonce_field( 'woo-tapsi-delivery-update-location', '_update-location-nonce' );