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

$tapsi_phone = get_option('woocommerce_tapsi_user_phone');
$is_token_valid = WCDD()->api->is_token_valid();

/**
 * Output the Location Information section
 */
echo '<h2><a href="' . admin_url( 'admin.php?page=wc-settings&tab=woo-tapsi-delivery&section=login' ) . '">' . __( 'Login' ) . '</a> > ' . __( 'Enter Phone for Login', 'woo-tapsi-delivery' ) . '</h2>';

echo '<h2>' . __( 'Login Information', 'woo-tapsi-delivery' ) . '</h2>';

    if ($is_token_valid) {
        echo '<p class="taspi-pack-authenticated-phone-number">' . __( 'Your phone number ' . $tapsi_phone .  ' has been verified, and you can update it at any time.', 'woo-tapsi-delivery' ) . '</p>';
    } else {
        echo '<p class="taspi-pack-unauthenticated-phone-number">' . __( 'Your phone number has not been verified. Please enter the phone number associated with your Tapsi account.', 'woo-tapsi-delivery' ) . '</p>';
    }

	echo '<section class="wc-tapsi-location name">';
		woocommerce_form_field( 'tapsi_phone', array(
			'type' => 'tel',  // TODO: check
			'required' => true,
			'label' => __( 'Phone Number: ', 'woo-tapsi-delivery' ),
			'placeholder' => __( '09', 'woo-tapsi-delivery' ),
		), $tapsi_phone );
	echo '</section>';


/**
 * Nonce field
 */
wp_nonce_field( 'woo-tapsi-delivery-update-phone', '_update-phone-nonce' );