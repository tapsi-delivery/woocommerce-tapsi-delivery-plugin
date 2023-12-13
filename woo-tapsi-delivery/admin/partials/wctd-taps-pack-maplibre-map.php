<?php

	/**
	 * Map Component
	 *
	 * @link       https://www.inverseparadox.com
	 * @since      1.0.0
	 *
	 * @package    Woocommerce_Tapsi
	 * @subpackage maplibre-js
	 */

	echo '<section class="wctd-tapsi-pack-map-form">';
    echo '<p class="form-row"><label for="wctd-tapsi-pack-maplibre-map-container-id">'. __('Coordinates', 'woo-tapsi-delivery') .'</label>&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr></p>';
	echo '<p>' . __( 'Please choose the precise coordinate that matches your address on the map.', 'woo-tapsi-delivery' ) . '</p>';
	echo '<p class="wctd-tapsi-pack-delivery-map-notification privacy_requests">' . __( "Make sure to check the compatibility between the address and the pin. In case of incompatibility, The pin location is Tapsi Pack's reference for your delivery!!!", 'woo-tapsi-delivery' ) . '</p>';
	echo '<div id="wctd-tapsi-pack-maplibre-map-root-id">';
	echo '<div id="wctd-tapsi-pack-maplibre-map-container-id"></div>';
	echo '<img id="wctd-tapsi-pack-maplibre-map-center-marker-id" src="https://static.tapsi.cab/pack/wp-plugin/map/map-center-marker.svg"/>';
	echo '</div>';
	echo '</section>';

?>
