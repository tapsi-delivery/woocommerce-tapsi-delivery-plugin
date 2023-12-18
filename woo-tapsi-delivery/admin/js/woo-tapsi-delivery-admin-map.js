(function( $ ) {
	'use strict';

	$(function() {
		const MAP_CONTAINER_ID = 'wctd-tapsi-pack-maplibre-map-container-id';
		const MAP_STYLE = 'https://static.tapsi.cab/pack/wp-plugin/map/mapsi-style.json';
		const getMap = () => $(`#${MAP_CONTAINER_ID}`);
		const lat = (value) => {
			const oldLat = $('#wctd-tapsi-pack-maplibre-map-location-form-lat-field-id');
			const id1Value = value ? oldLat.val(value): oldLat.val();
			const newLat =  $('#wctd_tapsi_origin_lat');
			const id2Value = value ? newLat.val(value) : newLat.val();
			return id1Value || id2Value;
		};
		const long = (value) => {
			const oldLong = $('#wctd-tapsi-pack-maplibre-map-location-form-lng-field-id');
			const id1Value = value ? oldLong.val(value) :oldLong.val();
			const newLong = $('#wctd_tapsi_origin_long');
			const id2Value = value ? newLong.val(value) : newLong.val();
			return id1Value || id2Value;
		};
		let centerLocation = [51.337762, 35.699927]; // Azadi Square
		let map = null;
		prepareMapOnAdminLoad();

		function prepareMapOnAdminLoad() {

			console.log('document loaded');
			console.log('map elements status:', 'map:', getMap(), 'lat:', lat(), 'long:', long());
			if (getMap()) {
				loadMap();
			} else {
				console.warn('Map container not found, waiting for map container load...');
				$(document.body).on('load', `#${MAP_CONTAINER_ID}`, undefined, function (event) {
					loadMap();
				});
			}
		}

		function loadMap() {
			console.log('map is loading...');
			console.log('map center location from inputs?', !!(Number(lat()) && Number(long())));
			if (Number(lat()) && Number(long())) centerLocation = [Number(long()), Number(lat())];
			console.log(centerLocation);
			map = new maplibregl.Map({
				container: MAP_CONTAINER_ID, // container id
				style: MAP_STYLE,
				center: centerLocation, // starting position
				zoom: 15, // starting zoom
			});
			maplibregl.setRTLTextPlugin(
				'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js',
				null,
				true, // Lazy load the plugin
			);
			map.addControl(new maplibregl.NavigationControl());
			map.on('move', () => {
				const center = map.getCenter();
				if (center) {
					long(center[0] || center.lng);
					lat(center[1] || center.lat);
				}
			})
		}
	});

})( jQuery );