(function( $ ) {
	'use strict';

	/**
	 * Public-facing JS for checkout and cart
	 */

	// Run on DOM ready
	$(function() {
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


		// Enhanced select on location dropdown
		// Need to make this fire when the shipping method is selected/updated as well
		// $('#doordash_pickup_location').selectWoo();


		// Updates session when changing pickup location on cart
		$('body.woocommerce-cart').on( 'change', '#doordash_pickup_location', function() {
			block( $('.cart_totals') );
			$.ajax({
				type: 'POST',
				url: woocommerce_params.ajax_url,
				data: {
					"action": "wcdd_update_pickup_location", 
					"location_id":this.value,
					"nonce":$('#wcdd_set_pickup_location_nonce').val()
				},
				success: function( data ) {
					$(document).trigger('wc_update_cart');
				},
				fail: function( data ) {
					unblock( $('.cart_totals') );
				}
			});
		} );

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
			console.log('Updated DoorDash delivery quote', $('#doordash_external_delivery_id').val());
		}, 1000 * 60 * 4 );

		// Add tabindex to tip radio labels for accessibility
		$('.wcdd-delivery-options label.radio').each( function() {
			$(this).attr('tabindex', '0');
		} );

		// Add mobile view classes if necessary
		mobileViews();
	} );

})( jQuery );
