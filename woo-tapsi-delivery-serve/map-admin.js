const WCTD_MAP_CONTAINER_ID = 'wctd-tapsi-pack-maplibre-map-container-id';
const WCTD_MAP_STYLE = 'https://static.tapsi.cab/pack/wp-plugin/map/mapsi-style.json';
const WCTDgetMap = () => document.getElementById(WCTD_MAP_CONTAINER_ID);
const WCTDgetLat = () => document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id') ||
    document.getElementById('wctd_tapsi_origin_lat');
const WCTDgetLong = () => document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id') ||
    document.getElementById('wctd_tapsi_origin_long');
let WCTD_centerLocation = [51.337762, 35.699927]; // Azadi Square
let WCTD_map = null;

const WCTDscriptStart = () => {
    console.log('map is loading...');
    console.log('map center location: ', !!Number(WCTDgetLat()?.value) && Number(WCTDgetLong()?.value));
    if (Number(WCTDgetLat()?.value) && Number(WCTDgetLong()?.value)) WCTD_centerLocation = [Number(WCTDgetLong().value), Number(WCTDgetLat().value)];
    console.log(WCTD_centerLocation);
    WCTD_map = new maplibregl.Map({
        container: WCTD_MAP_CONTAINER_ID, // container id
        style: WCTD_MAP_STYLE,
        center: WCTD_centerLocation, // starting position
        zoom: 15, // starting zoom
    });
    maplibregl.setRTLTextPlugin(
        'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js',
        null,
        true, // Lazy load the plugin
    );
    WCTD_map.addControl(new maplibregl.NavigationControl());
    WCTD_map.on('move', () => {
        const center = WCTD_map.getCenter();
        if (WCTDgetLat() && WCTDgetLong() && center) {
            WCTDgetLong().value = center[0] || center.lng;
            WCTDgetLat().value = center[1] || center.lat;
        }
    })
}

const WCTDonDocumentLoaded = () => {
    console.log('document loaded');
    console.log(WCTDgetMap(), Boolean(WCTDgetMap()));
    console.log('loading the map inputs', WCTDgetLat(), WCTDgetLong());
    if (WCTDgetMap()) {
        WCTDscriptStart();
    } else {
        console.warn('Map container not found, waiting for map container load...');
        document.getElementById(WCTD_MAP_CONTAINER_ID)?.addEventListener('load', WCTDscriptStart);
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", WCTDonDocumentLoaded);
} else {
    WCTDonDocumentLoaded();
}
