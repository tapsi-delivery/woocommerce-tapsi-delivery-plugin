/* global pickupLocationsLocalizeScript, ajaxurl */
( function( $, data, wp, ajaxurl ) {
	$( function() {
		var $table          = $( '.wc-doordash-locations' ),
			$tbody          = $( '.wc-doordash-location-rows' ),
			$save_button    = $( '.wc-doordash-location-save' ),
			$row_template   = wp.template( 'wc-doordash-location-row' ),
			$blank_template = wp.template( 'wc-doordash-location-row-blank' ),

			// Backbone model
			PickupLocation       = Backbone.Model.extend({
				changes: {},
				logChanges: function( changedRows ) {
					var changes = this.changes || {};

					_.each( changedRows, function( row, id ) {
						changes[ id ] = _.extend( changes[ id ] || { location_id : id }, row );
					} );

					this.changes = changes;
					this.trigger( 'change:locations' );
				},
				discardChanges: function( id ) {
					var changes      = this.changes || {},
						set_position = null,
						locations        = _.indexBy( this.get( 'locations' ), 'location_id' );

					// Find current set position if it has moved since last save
					if ( changes[ id ] && changes[ id ].location_order !== undefined ) {
						set_position = changes[ id ].location_order;
					}

					// Delete all changes
					delete changes[ id ];

					// If the position was set, and this location does exist in DB, set the position again so the changes are not lost.
					if ( set_position !== null && locations[ id ] && locations[ id ].location_order !== set_position ) {
						changes[ id ] = _.extend( changes[ id ] || {}, { location_id : id, location_order : set_position } );
					}

					this.changes = changes;

					// No changes? Disable save button.
					if ( 0 === _.size( this.changes ) ) {
						pickupLocationsView.clearUnloadConfirmation();
					}
				},
				save: function() {
					if ( _.size( this.changes ) ) {
						$.post( ajaxurl, { // + ( ajaxurl.indexOf( '?' ) > 0 ? '&' : '?' ) + 'action=woocommerce_doordash_pickup_locations_save_changes', {
							action: 'woocommerce_doordash_pickup_locations_save_changes',
							wc_doordash_pickup_locations_nonce : data.wc_doordash_pickup_locations_nonce,
							changes                 : this.changes
						}, this.onSaveResponse, 'json' );
					} else {
						pickupLocation.trigger( 'saved:locations' );
					}
				},
				onSaveResponse: function( response, textStatus ) {
					if ( 'success' === textStatus ) {
						if ( response.success ) {
							pickupLocation.set( 'locations', response.data.locations );
							pickupLocation.trigger( 'change:locations' );
							pickupLocation.changes = {};
							pickupLocation.trigger( 'saved:locations' );
						} else {
							window.alert( data.strings.save_failed );
						}
					}
				}
			} ),

			// Backbone view
			PickupLocationView = Backbone.View.extend({
				rowTemplate: $row_template,
				initialize: function() {
					this.listenTo( this.model, 'change:locations', this.setUnloadConfirmation );
					this.listenTo( this.model, 'saved:locations', this.clearUnloadConfirmation );
					this.listenTo( this.model, 'saved:locations', this.render );
					$tbody.on( 'change', { view: this }, this.updateModelOnChange );
					$tbody.on( 'sortupdate', { view: this }, this.updateModelOnSort );
					$( window ).on( 'beforeunload', { view: this }, this.unloadConfirmation );
					$( document.body ).on( 'click', '.wc-doordash-location-add', { view: this }, this.onAddNewRow );
				},
				onAddNewRow: function() {
					var $link = $( this );
					window.location.href = $link.attr( 'href' );
				},
				block: function() {
					$( this.el ).block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
				},
				unblock: function() {
					$( this.el ).unblock();
				},
				render: function() {
					var locations = _.indexBy( this.model.get( 'locations' ), 'location_id' ),
						view  = this;

					view.$el.empty();
					view.unblock();

					if ( _.size( locations ) ) {
						// Sort locations
						locations = _( locations )
							.chain()
							.sortBy( function ( location ) { return parseInt( location.location_id, 10 ); } )
							.sortBy( function ( location ) { return parseInt( location.location_order, 10 ); } )
							.value();

						// Populate $tbody with the current locations
						$.each( locations, function( id, rowData ) {
							view.renderRow( rowData );
						} );
					} else {
						view.$el.append( $blank_template );
					}

					view.initRows();
				},
				renderRow: function( rowData ) {
					var view = this;
					view.$el.append( view.rowTemplate( rowData ) );
					view.initRow( rowData );
				},
				initRow: function( rowData ) {
					var view = this;
					var $tr = view.$el.find( 'tr[data-id="' + rowData.location_id + '"]');

					// List shipping methods
					$tr.find( '.wc-doordash-location-delete' ).on( 'click', { view: this }, this.onDeleteRow );
				},
				initRows: function() {
					// Stripe
					if ( 0 === ( $( 'tbody.wc-doordash-location-rows tr' ).length % 2 ) ) {
						$table.find( 'tbody.wc-doordash-location-rows' ).next( 'tbody' ).find( 'tr' ).addClass( 'odd' );
					} else {
						$table.find( 'tbody.wc-doordash-location-rows' ).next( 'tbody' ).find( 'tr' ).removeClass( 'odd' );
					}
					// Tooltips
					$( '#tiptip_holder' ).removeAttr( 'style' );
					$( '#tiptip_arrow' ).removeAttr( 'style' );
					$( '.tips' ).tipTip({ 'attribute': 'data-tip', 'fadeIn': 50, 'fadeOut': 50, 'delay': 50 });
				},
				onDeleteRow: function( event ) {
					// var view    = event.data.view,
					// 	model   = view.model,
					// 	locations   = _.indexBy( model.get( 'locations' ), 'location_id' ),
					// 	changes = {},
					// 	row     = $( this ).closest('tr'),
					// 	location_id = row.data('id');

					
					if ( ! window.confirm( data.strings.delete_confirmation_msg ) ) {
						event.preventDefault();
						
					// 	if ( locations[ location_id ] ) {
					// 		delete locations[ location_id ];
					// 		changes[ location_id ] = _.extend( changes[ location_id ] || {}, { deleted : 'deleted' } );
					// 		model.set( 'locations', locations );
					// 		model.logChanges( changes );
					// 		event.data.view.block();
					// 		event.data.view.model.save();
					// 	}
					}
				},
				setUnloadConfirmation: function() {
					this.needsUnloadConfirm = true;
					$save_button.prop( 'disabled', false );
				},
				clearUnloadConfirmation: function() {
					this.needsUnloadConfirm = false;
					$save_button.prop( 'disabled', true );
				},
				unloadConfirmation: function( event ) {
					if ( event.data.view.needsUnloadConfirm ) {
						event.returnValue = data.strings.unload_confirmation_msg;
						window.event.returnValue = data.strings.unload_confirmation_msg;
						return data.strings.unload_confirmation_msg;
					}
				},
				updateModelOnChange: function( event ) {
					var model     = event.data.view.model,
						$target   = $( event.target ),
						location_id   = $target.closest( 'tr' ).data( 'id' ),
						attribute = $target.data( 'attribute' ),
						value     = $target.val(),
						locations   = _.indexBy( model.get( 'locations' ), 'location_id' ),
						changes = {};

					if ( ! locations[ location_id ] || locations[ location_id ][ attribute ] !== value ) {
						changes[ location_id ] = {};
						changes[ location_id ][ attribute ] = value;
					}

					model.logChanges( changes );
				},
				updateModelOnSort: function( event ) {
					var view    = event.data.view,
						model   = view.model,
						locations   = _.indexBy( model.get( 'locations' ), 'location_id' ),
						rows    = $( 'tbody.wc-doordash-location-rows tr' ),
						changes = {};

					// Update sorted row position
					_.each( rows, function( row ) {
						var location_id = $( row ).data( 'id' ),
							old_position = null,
							new_position = parseInt( $( row ).index(), 10 );

						if ( locations[ location_id ] ) {
							old_position = parseInt( locations[ location_id ].location_order, 10 );
						}

						if ( old_position !== new_position ) {
							changes[ location_id ] = _.extend( changes[ location_id ] || {}, { location_order : new_position } );
						}
					} );

					if ( _.size( changes ) ) {
						model.logChanges( changes );
						event.data.view.block();
						event.data.view.model.save();
					}
				}
			} ),
			pickupLocation = new PickupLocation({
				locations: data.locations
			} ),
			pickupLocationsView = new PickupLocationView({
				model:    pickupLocation,
				el:       $tbody
			} );

		pickupLocationsView.render();

		$tbody.sortable({
			items: 'tr',
			cursor: 'move',
			axis: 'y',
			handle: 'td.wc-doordash-location-sort',
			scrollSensitivity: 40
		});
	});
})( jQuery, wcDDLocalizeScript, wp, ajaxurl );