<?php

/**
 * Phone Settings
 *
 * This file is used to output the fields for entering a phone for login
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
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=login' ) . '">' . __( 'Login' ) . '</a> > ' . __( 'Enter the OTP sent:', 'tapsi-delivery' ) . '</h2>';

echo '<h2>' . __( 'Login Information', 'tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the OTP sent to your phone number.', 'tapsi-delivery' ) . '</p>';

	echo '<section class="wc-tapsi-location name">';
		woocommerce_form_field( 'tapsi_otp', array(
			'type' => 'tel',  // TODO: check
			'required' => true,
			'label' => __( 'Sent OTP: ', 'tapsi-delivery' ),
			'placeholder' => __( '12345', 'tapsi-delivery' ),
		), '12345' );
	echo '</section>';

///**
// * Output the Location Address section
// */
//echo '<h2>' . __( 'Address', 'tapsi-delivery' ) . '</h2>';
//
//	echo '<p>' . __( 'Enter the address for the location.', 'tapsi-delivery' ) . '</p>';
//
//	$address = $location->get_address();
//
//	echo '<section class="wc-tapsi-location address">';
//		woocommerce_form_field( 'location_address_1', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'Address 1', 'tapsi-delivery' ),
//		), $address['address_1'] );
//
//		woocommerce_form_field( 'location_address_2', array(
//			'type' => 'text',
//			'label' => __( 'Address 2', 'tapsi-delivery' ),
//		), $address['address_2'] );
//
//		woocommerce_form_field( 'location_city', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'City', 'tapsi-delivery' ),
//		), $address['city'] );
//
//		woocommerce_form_field( 'location_state', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'State', 'tapsi-delivery' ),
//		), $address['state'] );
//
//		woocommerce_form_field( 'location_postcode', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'Postcode', 'tapsi-delivery' ),
//		), $address['postcode'] );
//
//		woocommerce_form_field( 'location_country', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'Country', 'tapsi-delivery' ),
//		), $address['country'] );
//	echo '</section>';
//
///**
// * Output the Location Hours section
// */
//echo '<h2>' . __( 'Hours', 'tapsi-delivery' ) . '</h2>';
//
//	echo '<p>' . __( 'Enter the hours for the location.', 'tapsi-delivery' ) . '</p>';
//
//	echo '<section class="wc-tapsi-location option hours-wrapper">';
//		woocommerce_form_field( 'location_hours_enabled', array(
//			'type' => 'checkbox',
//			'label' => __( 'Customize hours for this location', 'tapsi-delivery' ),
//		), $location->has_hours() );
//
//		// TODO: This should follow the start_of_week option
//		$weekdays = array(
//			'sunday'    => __( 'Sunday', 'tapsi-delivery' ),
//			'monday'    => __( 'Monday', 'tapsi-delivery' ),
//			'tuesday'   => __( 'Tuesday', 'tapsi-delivery' ),
//			'wednesday' => __( 'Wednesday', 'tapsi-delivery' ),
//			'thursday'  => __( 'Thursday', 'tapsi-delivery' ),
//			'friday'    => __( 'Friday', 'tapsi-delivery' ),
//			'saturday'  => __( 'Saturday', 'tapsi-delivery' ),
//		);
//
//		echo '<section class="wc-tapsi-location hours">';
//			foreach ( $weekdays as $key => $label ) {
//				woocommerce_form_field( "location_{$key}_hours", array(
//					'type' => 'text',
//					'label' => sprintf( __( '%1$s Hours' ), $label ),
//				), $location->get_weekly_hours_meta( $key ) );
//			}
//		echo '</section>';
//	echo '</section>';

/**
 * Nonce field
 */
wp_nonce_field( 'woocommerce-tapsi-set-otp', '_update-otp-nonce' );