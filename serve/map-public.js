document.addEventListener('DOMContentLoaded', () => {
    const MAP_CONTAINER_ID = 'wctd-tapsi-pack-maplibre-map-public-container-id';
    const MAP_STYLE = 'http://localhost/tapsipack/wp-content/plugins/serve/mapsi-style.json';
    const getMap = () => document.getElementById(MAP_CONTAINER_ID);
    const getLat = () => document.getElementById('wctd-tapsi-pack-maplibre-map-public-location-form-lat-field-id');
    const getLong = () => document.getElementById('wctd-tapsi-pack-maplibre-map-public-location-form-lng-field-id');
    const getSubmitButton = () => document.getElementById('wctd-tapsi-pack-mapliblre-map-public-submit-location-button');
    const getCloseButton = () => document.getElementById('wctd-tapsi-pack-mapliblre-map-public-close-modal-button');
    let centerLocation = [51.337762, 35.699927]; // Azadi Square
    let map = null;

    const addButtonListeners = () => {
        console.log('adding buttom listeners');
        console.log('getCloseButton',getCloseButton());
        console.log('getSubmitButton',getSubmitButton());

        getCloseButton().addEventListener('click', () => {
            const mapRoot = document.getElementById('wctd-tapsi-pack-maplibre-map-public-root-id');
            mapRoot.style.visibilty = 'hidden';
        })

        getSubmitButton().addEventListener('click', () => {
            const mapRoot = document.getElementById('wctd-tapsi-pack-maplibre-map-public-root-id');
            const center = map.getCenter();
            if (getLat() && getLong() && center) {
                getLong().value = center[0] || center.lng;
                getLat().value = center[1] || center.lat;
            }
            mapRoot.style.visibilty = 'hidden';
        })
    }

    const scriptStart = () => {
        addButtonListeners();
        console.log('script start');
        console.log(!!Number(getLat()?.value) && Number(getLong()?.value));
        if(Number(getLat()?.value) && Number(getLong()?.value)) centerLocation = [Number(getLong().value), Number(getLat().value)];
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
    }

    console.log(getMap());
    if (getMap()) {
        scriptStart();
    } else {
        getMap()?.addEventListener('load', scriptStart);
    }
});

