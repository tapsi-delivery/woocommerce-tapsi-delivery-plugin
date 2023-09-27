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

	echo '<style>
			#wctd-tapsi-pack-maplibre-map-public-root-id {
			    visibility: hidden;
			    position: fixed !important;
			    width: 100vw !important;
			    top: 0 !important;
			    left: 0 !important;
			    bottom: 0 !important;
			    right: 0 !important;
			    z-index: 99999 !important;
			    background-color: rgb(0,0,0,0.5) !important;
			}
			
			#wctd-tapsi-pack-maplibre-map-public-container-id {
				position: absolute !important;
			    top: 0 !important;
			    bottom: 0 !important;
			    right: 0 !important;
			    left: 0 !important;
			    /* removing margin */
			    width: calc(100% - 80px) !important;
			    height: calc(100% - 80px) !important;
			    overflow: visible !important;
			    margin: 40px !important;
			    border: 2px solid black !important;
			}
			
			#wctd-tapsi-pack-maplibre-map-public-center-marker-id {
			    position: absolute !important;
			    top: 50% !important; 
			    left: 50% !important;
			    transform: translate(-50%, calc(-100% + 3px)) !important;
			}
			
			#wctd-tapsi-pack-mapliblre-map-public-submit-location-button {
			    position: absolute !important;
			    bottom: 60px !important;
			    margin: 0 auto !important;
			    left: 50% !important;
			    transform: translate(-50%, 0%) !important;
			    background: #000 !important;
			    color: #fff !important;
			    min-height: 40px !important;
			    min-width: calc(100% - 100px) !important; /* padding + parent margin */
			    border: none !important;
			    border-radius: 50px !important;
			    font-weight: 600 !important;
			    padding: 10px 20px !important;
			}
			
			#wctd-tapsi-pack-mapliblre-map-public-close-modal-button {
				position: absolute !important;
		        top: 50px !important;
			    left: 50px !important;
			    border-radius: 15px !important;
			    height: 30px !important;
			    width: 30px !important;
			    background-color: #000 !important;
			    border: none !important;
			    color: #fff !important;
			    font-weight: 600 !important;
			}
			#wctd-tapsi-pack-maplibre-map-public-preview-img-container {
				position: relative !important;
				margin: 10px !important;
				padding: 0 !important;
				display: flex !important;
				justify-content: center !important;
				align-items: center !important;
			}
			#wctd-tapsi-pack-maplibre-map-public-preview-img-container #wctd-tapsi-pack-maplibre-map-public-preview-img-dot {
				position: absolute !important;
				top: 50% !important; 
			    left: 50% !important;
			    transform: translate(-50%, calc(-100% + 10px)) !important; 
			    margin: 0 !important;
				padding: 0 !important;
			}
			#wctd-tapsi-pack-maplibre-map-public-preview-img-container #wctd-tapsi-pack-maplibre-map-public-preview-img {
			    margin: 0 !important;
				padding: 0 !important;
				cursor: pointer;
			}
			#wctd-tapsi-pack-maplibre-map-public-warning {
				color: #c91d02 !important;
				margin: 10px !important;
				display: block;
				width: calc(100% - 20px) !important;
			}
			#wctd-tapsi-pack-maplibre-map-public-warning img {
				margin: 0 5px !important;
			}
			#wctd-tapsi-pack-show-map-button-checkout-page {
				margin: 5px 10px !important;
				width: calc(100% - 20px) !important;
			}
		</style>';
	echo '<div id="wctd-tapsi-pack-maplibre-map-public-root-id">';
	echo '<div id="wctd-tapsi-pack-maplibre-map-public-container-id"></div>';
	echo '<button id="wctd-tapsi-pack-mapliblre-map-public-close-modal-button" type="button">'.__('X','woo-tapsi-delivery').'</button>';
	echo '<button id="wctd-tapsi-pack-mapliblre-map-public-submit-location-button" type="button">'.__('Choose This Location','woo-tapsi-delivery').'</button>';
	echo '<img id="wctd-tapsi-pack-maplibre-map-public-center-marker-id" src="https://static.tapsi.cab/pack/wp-plugin/map/map-center-marker.svg"/>';
	echo '</div>';

?>
