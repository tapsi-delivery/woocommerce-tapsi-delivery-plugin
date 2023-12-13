(function( $ ) {
	'use strict';

	/**
	 * Public-facing JS for checkout and cart
	 */

	var map = null;

	// Run on DOM ready
	$(function() {

		addListeners();
		const chooseLocationButton = $('#wctd-tapsi-pack-show-map-button-checkout-page');
		chooseLocationButton.html('در حال بارگزاری...');
		prepareMapBeforeLoad();

		/**
		 * Check if a node is blocked for processing.
		 *
		 * @param {JQuery Object} $node
		 * @return {bool} True if the DOM Element is UI Blocked, false if not.
		 */
		var is_blocked = function( $node ) {
			return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
		};

		/**
		 * Block a node visually for processing.
		 *
		 * @param {JQuery Object} $node
		 */
		var block = function( $node ) {
			if ( ! is_blocked( $node ) ) {
				$node.addClass( 'processing' ).block( {
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				} );
			}
		};

		/**
		 * Unblock a node after processing is complete.
		 *
		 * @param {JQuery Object} $node
		 */
		var unblock = function( $node ) {
			$node.removeClass( 'processing' ).unblock();
		};

		function onLocationChange(payload, src = '') {
			$( document.body ).trigger( 'update_checkout' );

			if(src !== 'map') block( $('.cart_totals') );
			$.ajax({
				type: 'POST',
				url: woocommerce_params.ajax_url,
				data: {
					"action": "wcdd_update_pickup_location",
					...payload,
					"nonce":$('#wcdd_set_pickup_location_nonce').val()
				},
				success: function( data ) {
					$(document).trigger('wc_update_cart');
				},
				fail: function( data ) {
					if(src !== 'map') unblock( $('.cart_totals') );
				}
			});
		}


		// Updates session when changing pickup location on cart
		$('body.woocommerce-cart').on( 'change', '#tapsi_pickup_location', () => {
			onLocationChange({"location_id":this.value}, '');
		} );

		function prepareMapBeforeLoad() {
			console.log('TAPSI Delivery says hello. loading the scripts...');

			// Define the MapLibre CSS and JavaScript URLs
			var maplibreCSSUrl = 'https://unpkg.com/maplibre-gl@3.3.1/dist/maplibre-gl.css';
			var maplibreJSUrl = 'https://unpkg.com/maplibre-gl@3.3.1/dist/maplibre-gl.js';

			// Load MapLibre CSS dynamically (optional)
			var maplibreCSS = document.createElement('link');
			maplibreCSS.rel = 'stylesheet';
			maplibreCSS.id = 'wctd-tapsi-pack-maplibre-stylesheet';
			maplibreCSS.href = maplibreCSSUrl;
			document.head.appendChild(maplibreCSS);

			// var mapContainer = document.createElement('div');
			// maplibreCSS.id = 'wctd-tapsi-pack-maplibre-map-modal-container';
			// document.body.appendChild(mapContainer)

			// Load MapLibre JavaScript dynamically
			var maplibreJS = document.createElement('script');
			maplibreJS.src = maplibreJSUrl;
			maplibreJS.id = 'wctd-tapsi-pack-maplibre-library-source';
			maplibreJS.onload = () => {
				initializeMap();
			};
			document.head.appendChild(maplibreJS);

		}

		function addListeners() {
			function openMap(event) {
				// Check if the clicked element s your button
				console.log('open event');
				event?.preventDefault();
				event?.stopPropagation();
				const lat = $('#wctd_tapsi_destination_lat');
				const lng = $('#wctd_tapsi_destination_long');

				let centerLocation = [51.337762, 35.699927]; // Azadi Square
				if (Number(lat.val()) && Number(lng.val())) centerLocation = [Number(lng.val()), Number(lat.val())];
				map.setCenter(centerLocation);
				map.zoomTo(15, {duration: 1000});
				$('#wctd-tapsi-pack-maplibre-map-public-root-id').css({visibility: "visible"});
			}
			// click event on show map button to open map modal
			$(document.body).on('click', '#wctd-tapsi-pack-show-map-button-checkout-page', undefined, function (event) {
				openMap(event);
			});

			$(document.body).on('click', '#wctd-tapsi-pack-maplibre-map-public-preview-img', undefined, function (event) {
				openMap(event);
			});

			// close the modal by click on the back drop
			$(document.body).on('click', '#wctd-tapsi-pack-maplibre-map-public-root-id', undefined, function (event) {
				// Check if the clicked element is your button
				if (event.target.id === 'wctd-tapsi-pack-maplibre-map-public-root-id') {
					console.log('close event');
					event?.preventDefault();
					event?.stopPropagation();
					$('#wctd-tapsi-pack-maplibre-map-public-root-id').css({visibility: "hidden"});
				}
			});

			// close the map modal by clicking the close button (x)
			$(document.body).on('click', '#wctd-tapsi-pack-mapliblre-map-public-close-modal-button', undefined, function (event) {
				console.log('close event');
				event?.preventDefault();
				event?.stopPropagation();
				$('#wctd-tapsi-pack-maplibre-map-public-root-id').css({visibility: "hidden"});
			});

			function submitLocation(event) {
				console.log('submit event');
				event?.preventDefault();
				event?.stopPropagation();
				const lat = $('#wctd_tapsi_destination_lat');
				const lng = $('#wctd_tapsi_destination_long');

				console.log('map center', map.getCenter());
				const azadiCoordinate = [51.337762, 35.699927];

				const mapCenter = map.getCenter();
				const center = [(mapCenter[0] || mapCenter.lng), (mapCenter[1] || mapCenter.lat)];

				if (lat && lng && center[0] && center[1]) {
					lng.val(center[0]);
					lat.val(center[1]);
				} else {
					alert('خطایی در ذخیره‌سازی مقصد شما پیش آمد. لطفا دوباره تلاش کنید.');
					lng?.val('');
					lat?.val('');
					return;
				}

				$('#wctd-tapsi-pack-maplibre-map-public-root-id').css({visibility: "hidden"});

				const lng1 = center[0];
				const lng2 = lng1 + 0.0000001;
				const lat1 = center[1];
				const lat2 = lat1 + 0.0000001;
				const path = `${lng1},${lat1}|${lng2},${lat2}`;


				$('#wctd-tapsi-pack-maplibre-map-public-preview-img').attr('src', `https://tap30.services/styles/passenger/static/auto/500x500@2x.png?path=${path}&stroke=black&width=200&padding=50000`);
				onLocationChange({
					"wctd_tapsi_destination_long": center[0],
					"wctd_tapsi_destination_lat": center[1],
				}, 'map');
			}

			// submit the center location and close the map modal
			$(document.body).on('click', '#wctd-tapsi-pack-mapliblre-map-public-submit-location-button', undefined, (event) => {
				submitLocation(event);
			})

			// open | close rules section
			$(document.body).on('click', '#wctd-tapsi-pack-rules-button', undefined, (event) => {
				event?.preventDefault();
				event?.stopPropagation();
				const rules = $('#wctd-tapsi-pack-rules');
				const isHidden = rules.css('display') === 'none';
				console.log('rules click', rules, isHidden);
				if (isHidden) rules.css({display: 'block'});
				else rules.css({display: 'none'});
			});
		}

		function initializeMap() {
			console.log('initializing the map...')
			// Add other map-related code here
			if (window.location.pathname.includes('checkout')){
			const MAP_CONTAINER_ID = 'wctd-tapsi-pack-maplibre-map-public-container-id';
			const MAP_STYLE = 'https://static.tapsi.cab/pack/wp-plugin/map/mapsi-style.json';
			const lat = $('#wctd_tapsi_destination_lat');
			const lng = $('#wctd_tapsi_destination_long');
			let centerLocation = [51.337762, 35.699927]; // Azadi Square
			if(Number(lat.val()) && Number(lng.val())) centerLocation = [Number(lng.val()), Number(lat.val())];
			console.log('map center', centerLocation);
				map = new maplibregl.Map({
					container: MAP_CONTAINER_ID, // container id
					style: MAP_STYLE,
					center: centerLocation, // starting position
					zoom: 15, // starting zoom
				});
				maplibregl.setRTLTextPlugin(
					'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js',
					null,
					false, // Lazy load the plugin
				);
				map.addControl(new maplibregl.NavigationControl());
			}

			$('#wctd-tapsi-pack-show-map-button-checkout-page').html('آدرس مقصد را انتخاب کنید.');
		}
	});

	/**
	 * Adds mobile classes to containers based on the width of the shipping method container.
	 * This can probably be reworked in the future with CSS container queries.
	 */
	var mobileViews = function() {
		var containerWidth = $( "tr.woocommerce-shipping-totals.shipping td" ).width();
		var $deliveryOptions = $('.wcdd-delivery-options');
		if ( containerWidth < 195 && containerWidth >= 155 ) {
			// if the width of the shipping container is less than 195px and greater than 155px, then add the class to the options container
			$deliveryOptions.addClass('mobile-view');
		} else if ( containerWidth < 155 ) {
			// if the width of the shipping container is less than 155px
			$deliveryOptions.addClass('tiny-view');
		} else if ( containerWidth >= 195 ) {
			// if the width of the shipping container is greater than 155px and less than 195px, then remove the class to the options container
			$deliveryOptions.removeClass('tiny-view mobile-view');	
		}
	}

	// Run mobileViews on resize
	var resizeTimeout;
	window.onresize = function() {
		clearTimeout( resizeTimeout );
		resizeTimeout = setTimeout( mobileViews, 100 );
	};

	/**
	 * This runs each time the quote/totals are updated.
	 */
	var updateTimeout;
	$(window).on( 'updated_checkout', function() {
		clearTimeout( updateTimeout );
		// Automatically update the quote every four minutes to avoid expirations
		updateTimeout = setTimeout( function() {
			$( document.body ).trigger( 'update_checkout' );
			console.log('Updated Tapsi delivery quote', $('#tapsi_external_delivery_id').val());
		}, 1000 * 60 * 4 );

		// Add tabindex to tip radio labels for accessibility
		$('.wcdd-delivery-options label.radio').each( function() {
			$(this).attr('tabindex', '0');
		} );

		// Add mobile view classes if necessary
		mobileViews();
	} );

	jQuery(document).ready(function ($) {
		// Listen for changes in the country field
		$('select#billing_country, select#shipping_country').change(function () {
			// Trigger an update of shipping methods
			$(document.body).trigger('update_checkout');
		});

		// Listen for changes in the state/province field
		$('select#billing_state, select#shipping_state').change(function () {
			// Trigger an update of shipping methods
			$(document.body).trigger('update_checkout');
		});
	});

})( jQuery );
