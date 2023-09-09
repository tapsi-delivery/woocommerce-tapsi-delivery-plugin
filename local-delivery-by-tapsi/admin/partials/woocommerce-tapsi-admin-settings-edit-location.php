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
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations' ) . '">' . __( 'Locations' ) . '</a> > ' . __( 'Edit Location', 'local-delivery-by-tapsi' ) . '</h2>';

echo '<h2>' . __( 'Location Information', 'local-delivery-by-tapsi' ) . '</h2>';

	echo '<p>' . __( 'Enter the information for the location you.', 'local-delivery-by-tapsi' ) . '</p>';

	echo '<section class="wc-tapsi-location hidden">';
		woocommerce_form_field( 'location_id', array(
			'type' => 'hidden',
		), $location->get_id() );
	echo '</section>';

	echo '<section class="wc-tapsi-location name">';
		woocommerce_form_field( 'location_name', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Location Name', 'local-delivery-by-tapsi' ),
			'placeholder' => __( 'Location Name', 'local-delivery-by-tapsi' ),
		), $location->get_name() );
	echo '</section>';

	echo '<section class="wc-tapsi-location option">';
		woocommerce_form_field( 'location_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Enabled', 'local-delivery-by-tapsi' ),
			'description' => __( 'Only enabled locations are able to be selected for Tapsi deliveries.', 'local-delivery-by-tapsi' ),
		), $location->is_enabled() );
	echo '</section>';

	echo '<section class="wc-tapsi-location info">';
		woocommerce_form_field( 'location_email', array(
			'type' => 'email',
			'label' => __( 'Email Address', 'local-delivery-by-tapsi' ),
			'required' => true,
			'description' => __( 'New order notifications for this location will be sent to this email address.', 'local-delivery-by-tapsi' )
		), $location->get_email() );

		woocommerce_form_field( 'location_phone', array(
			'type' => 'tel',
			'label' => __( 'Phone Number', 'local-delivery-by-tapsi' ),
			'required' => true,
		), $location->get_phone_number() );

		woocommerce_form_field( 'location_pickup_instructions', array(
			'type' => 'text',
			'label' => __( 'Pickup Instructions', 'local-delivery-by-tapsi' ),
			'description' => __( 'Pickup instructions for this location. Leave blank to use the default pickup instructions.', 'local-delivery-by-tapsi' )
		), $location->get_pickup_instructions() );
	echo '</section>';

/**
 * Output the Location Address section
 */
echo '<h2>' . __( 'Address', 'local-delivery-by-tapsi' ) . '</h2>';

	echo '<p>' . __( 'Enter the address for the location.', 'local-delivery-by-tapsi' ) . '</p>';

	$address = $location->get_address();

	echo '<section class="wc-tapsi-location address">';
		woocommerce_form_field( 'location_address_1', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Address 1', 'local-delivery-by-tapsi' ),
		), $address['address_1'] );

		woocommerce_form_field( 'location_address_2', array(
			'type' => 'text',
			'label' => __( 'Address 2', 'local-delivery-by-tapsi' ),
		), $address['address_2'] );

		woocommerce_form_field( 'location_city', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'City', 'local-delivery-by-tapsi' ),
		), $address['city'] );

		woocommerce_form_field( 'location_state', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'State', 'local-delivery-by-tapsi' ),
		), $address['state'] );

		woocommerce_form_field( 'location_postcode', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Postcode', 'local-delivery-by-tapsi' ),
		), $address['postcode'] );

		woocommerce_form_field( 'location_country', array(
			'type' => 'text',
			'required' => true,
			'label' => __( 'Country', 'local-delivery-by-tapsi' ),
		), $address['country'] );
	echo '</section>';

/**
 * Output the Location Hours section
 */
echo '<h2>' . __( 'Hours', 'local-delivery-by-tapsi' ) . '</h2>';

	echo '<p>' . __( 'Enter the hours for the location.', 'local-delivery-by-tapsi' ) . '</p>';

	echo '<section class="wc-tapsi-location option hours-wrapper">';
		woocommerce_form_field( 'location_hours_enabled', array(
			'type' => 'checkbox',
			'label' => __( 'Customize hours for this location', 'local-delivery-by-tapsi' ),
		), $location->has_hours() );

		// TODO: This should follow the start_of_week option
		$weekdays = array(
			'sunday'    => __( 'Sunday', 'local-delivery-by-tapsi' ),
			'monday'    => __( 'Monday', 'local-delivery-by-tapsi' ),
			'tuesday'   => __( 'Tuesday', 'local-delivery-by-tapsi' ),
			'wednesday' => __( 'Wednesday', 'local-delivery-by-tapsi' ),
			'thursday'  => __( 'Thursday', 'local-delivery-by-tapsi' ),
			'friday'    => __( 'Friday', 'local-delivery-by-tapsi' ),
			'saturday'  => __( 'Saturday', 'local-delivery-by-tapsi' ),
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