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
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woo-tapsi-delivery&section=login' ) . '">' . __( 'Login' ) . '</a> > ' . __( 'Enter the OTP sent:', 'woo-tapsi-delivery' ) . '</h2>';

echo '<h2>' . __( 'Login Information', 'woo-tapsi-delivery' ) . '</h2>';

	echo '<p>' . __( 'Enter the OTP sent to your phone number.', 'woo-tapsi-delivery' ) . '</p>';

	echo '<section class="wc-tapsi-location name">';
		woocommerce_form_field( 'tapsi_otp', array(
			'type' => 'tel',  // TODO: check
			'required' => true,
			'label' => __( 'Sent OTP: ', 'woo-tapsi-delivery' ),
			'placeholder' => __( '12345', 'woo-tapsi-delivery' ),
		), '12345' );
	echo '</section>';

///**
// * Output the Location Address section
// */
//echo '<h2>' . __( 'Address', 'woo-tapsi-delivery' ) . '</h2>';
//
//	echo '<p>' . __( 'Enter the address for the location.', 'woo-tapsi-delivery' ) . '</p>';
//
//	$address = $location->get_address();
//
//	echo '<section class="wc-tapsi-location address">';
//		woocommerce_form_field( 'location_address_1', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'Address 1', 'woo-tapsi-delivery' ),
//		), $address['address_1'] );
//
//		woocommerce_form_field( 'location_address_2', array(
//			'type' => 'text',
//			'label' => __( 'Address 2', 'woo-tapsi-delivery' ),
//		), $address['address_2'] );
//
//		woocommerce_form_field( 'location_city', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'City', 'woo-tapsi-delivery' ),
//		), $address['city'] );
//
//		woocommerce_form_field( 'location_state', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'State', 'woo-tapsi-delivery' ),
//		), $address['state'] );
//
//		woocommerce_form_field( 'location_postcode', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'Postcode', 'woo-tapsi-delivery' ),
//		), $address['postcode'] );
//
//		woocommerce_form_field( 'location_country', array(
//			'type' => 'text',
//			'required' => true,
//			'label' => __( 'Country', 'woo-tapsi-delivery' ),
//		), $address['country'] );
//	echo '</section>';
//
///**
// * Output the Location Hours section
// */
//echo '<h2>' . __( 'Hours', 'woo-tapsi-delivery' ) . '</h2>';
//
//	echo '<p>' . __( 'Enter the hours for the location.', 'woo-tapsi-delivery' ) . '</p>';
//
//	echo '<section class="wc-tapsi-location option hours-wrapper">';
//		woocommerce_form_field( 'location_hours_enabled', array(
//			'type' => 'checkbox',
//			'label' => __( 'Customize hours for this location', 'woo-tapsi-delivery' ),
//		), $location->has_hours() );
//
//		// TODO: This should follow the start_of_week option
//		$weekdays = array(
//			'sunday'    => __( 'Sunday', 'woo-tapsi-delivery' ),
//			'monday'    => __( 'Monday', 'woo-tapsi-delivery' ),
//			'tuesday'   => __( 'Tuesday', 'woo-tapsi-delivery' ),
//			'wednesday' => __( 'Wednesday', 'woo-tapsi-delivery' ),
//			'thursday'  => __( 'Thursday', 'woo-tapsi-delivery' ),
//			'friday'    => __( 'Friday', 'woo-tapsi-delivery' ),
//			'saturday'  => __( 'Saturday', 'woo-tapsi-delivery' ),
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
wp_nonce_field( 'woo-tapsi-delivery-set-otp', '_update-otp-nonce' );