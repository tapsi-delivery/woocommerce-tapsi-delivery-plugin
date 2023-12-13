<?php

/**
 * Phone Settings
 *
 * This file is used to output the fields for entering a phone for login
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin/partials
 */

/**
 * Output the Location Information section
 */
echo '<h2><a href="' . admin_url('admin.php?page=wc-settings&tab=woo-tapsi-delivery&section=login') . '">' . __('Login') . '</a> > ' . __('Enter the OTP sent:', 'woo-tapsi-delivery') . '</h2>';

echo '<h2>' . __('Login Information', 'woo-tapsi-delivery') . '</h2>';

echo '<p>' . __('Enter the OTP sent to your phone number.', 'woo-tapsi-delivery') . '</p>';

echo '<section class="wc-tapsi-location name">';
woocommerce_form_field('tapsi_otp', array(
    'type' => 'tel',  // TODO: check
    'required' => true,
    'label' => __('Sent OTP: ', 'woo-tapsi-delivery'),
    'placeholder' => __('12345', 'woo-tapsi-delivery'),
), '12345');
echo '</section>';

/**
 * Nonce field
 */
wp_nonce_field('woo-tapsi-delivery-set-otp', '_update-otp-nonce');