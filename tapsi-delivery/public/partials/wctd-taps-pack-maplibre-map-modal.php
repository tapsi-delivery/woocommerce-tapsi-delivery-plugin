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
		</style>';
	echo '<div id="wctd-tapsi-pack-maplibre-map-public-root-id">';
	echo '<div id="wctd-tapsi-pack-maplibre-map-public-container-id"></div>';
	echo '<button id="wctd-tapsi-pack-mapliblre-map-public-close-modal-button" type="button">'.__('X','tapsi-delivery').'</button>';
	echo '<button id="wctd-tapsi-pack-mapliblre-map-public-submit-location-button" type="button">'.__('Choose This Location','tapsi-delivery').'</button>';
	echo '<img id="wctd-tapsi-pack-maplibre-map-public-center-marker-id" src="http://localhost/tapsipack/wp-content/plugins/serve/map-center-marker.svg"/>';
	echo '</div>';

?>
