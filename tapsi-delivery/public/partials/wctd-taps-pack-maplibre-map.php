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

	function WCTD_tapsipack_get_map_loading_component() {
		return '<div id="wctd-tapsi-pack-map-loading-container">
				<!-- 	<div class="wctd-tapsi-pack-map-loading-loader">
				  <div class="wctd-tapsi-pack-map-loading-box wctd-tapsi-pack-box-1">
				    <div class="wctd-tapsi-pack-map-loading-side-left"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-right"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-top"></div>
				  </div>
				  <div class="wctd-tapsi-pack-map-loading-box wctd-tapsi-pack-box-2">
				    <div class="wctd-tapsi-pack-map-loading-side-left"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-right"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-top"></div>
				  </div>
				  <div class="wctd-tapsi-pack-map-loading-box wctd-tapsi-pack-box-3">
				    <div class="wctd-tapsi-pack-map-loading-side-left"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-right"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-top"></div>
				  </div>
				  <div class="wctd-tapsi-pack-map-loading-box wctd-tapsi-pack-box-4">
				    <div class="wctd-tapsi-pack-map-loading-side-left"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-right"></div>
				    <div class="wctd-tapsi-pack-map-loading-side-top"></div>
				  </div>
				</div> -->
				map is loading...
			</div> ';
	}


	echo '
		<!-- HTML Start Marker -->
		<div id="html-start-marker"></div>
		
		<button id="wctd-tapsi-pack-show-map-button" type="button">انتخاب آدرس</button>
		<div id="wctd-tapsi-pack-maplibre-map-modal-container"></div>
		<img id="wctd-tapsi-pack-maplibre-map-center-marker-id" src="http://localhost/tapsipack/wp-content/plugins/serve/map-center-marker.svg"/>
		
		<!-- HTML End Marker -->
		<div id="html-end-marker"></div>
		';
?>
