(function( $ ) {
	'use strict';

	// Internationalization support
	const { __, _x, _n, _nx } = wp.i18n;

	$(function() {

		/**
		 * Show the appropriate API key inputs based on selected API mode
		 */
		function toggleKeyInputs() {
			// Get the current selected and deselected modes
			var selectedMode = $('#woocommerce_doordash_api_environment').find(':selected').val();
			var deselectedMode = selectedMode == 'production' ? 'sandbox' : 'production';

			// Show/hide
			$('.wcdd-api-keys__' + deselectedMode).parents('tr').hide();
			$('.wcdd-api-keys__' + selectedMode).parents('tr').show();
		}
		$('#woocommerce_doordash_api_environment').on('change', toggleKeyInputs);
		toggleKeyInputs();

		/**
		 * Shows and hides hours section based on checkbox
		 */
		function toggleHours() {
			if ( $('#location_hours_enabled').is(':checked') ) {
				$('.wc-doordash-location.hours').addClass('is-visible').removeClass('is-hidden');
			} else {
				$('.wc-doordash-location.hours').addClass('is-hidden').removeClass('is-visible');
			}
		}
		$('#location_hours_enabled').on('click tap', toggleHours);
		toggleHours();

		/**
		 * Shows and hides Delivery Fee input based on Delivery Fees Mode selection
		 */
		function toggleFeeInput() {
			if ( $('#woocommerce_doordash_fees_mode').find( ':selected' ).val() == 'no_rate' ) {
				$('#woocommerce_doordash_delivery_fee').parents('tr').hide();
			} else {
				$('#woocommerce_doordash_delivery_fee').parents('tr').show();
			}
		}
		$('#woocommerce_doordash_fees_mode').on('change', toggleFeeInput);
		toggleFeeInput();

		/**
		 * Adds Copy buttons for some inputs
		 */
		function addCopyButtons() {
			// $('.has-copy-button').after('<button class="copy-button">' + __('Copy', 'local-delivery-by-doordash') + '</button>');
			$('.copy-button').on( 'click', function(e) {
				e.preventDefault();
				var $button = $(this), $input = $button.siblings('.has-copy-button'), $row = $button.parents('.form-row');

				$('.copied').removeClass('copied').find('.copy-button').text( __('Copy', 'local-delivery-by-doordash') );
				navigator.clipboard.writeText( $input.val() );
				$row.addClass('copied');
				$button.text( __('Copied!', 'local-delivery-by-doordash') );
			});
		}
		addCopyButtons();
	});



})( jQuery );