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

	echo '<p>' . __( 'Please choose the precise coordinate that matches your address on the map.', 'tapsi-delivery' ) . '</p>';
	echo '<p class="wctd-tapsi-pack-delivery-map-notification privacy_requests">' . __( "Make sure to check the pin location with your address. The pin location is Tapsi Pack's reference for delivering your package.", 'tapsi-delivery' ) . '</p>';
	echo '<section class="wctd-tapsi-pack-map-form">';
    echo '<p class="form-row"><label for="wctd-tapsi-pack-maplibre-map-container-id">'. __('Coordinates', 'tapsi-delivery') .'</label>&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr></p>';
	echo '<div id="wctd-tapsi-pack-maplibre-map-root-id">';
	echo '<div id="wctd-tapsi-pack-maplibre-map-container-id"></div>';
	echo '<button id="wctd-tapsi-pack-maplibre-map-submit-location-button-id">submit location</button>';
	echo '<img id="wctd-tapsi-pack-maplibre-map-center-marker-id" src="http://localhost:9700/map-center-marker.svg"/>';
	echo '</div>';
	echo '<script src="http://localhost:9700/map-handler.js"></script>';
	echo '</section>';

?>
