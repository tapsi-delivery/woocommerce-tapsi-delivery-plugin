const MAP_CONTAINER_ID = 'wctd-tapsi-pack-maplibre-map-container-id';
const MAP_STYLE = 'http://localhost/tapsipack/wp-content/plugins/serve/mapsi-style.json';
const getMap = () => document.getElementById(MAP_CONTAINER_ID);
const getLat = () => document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id');
const getLong = () => document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id');
const getButton = () => document.getElementById('wctd-tapsi-pack-maplibre-map-submit-location-button-id');
let centerLocation = [51.337762, 35.699927]; // Azadi Square
let map = null;

const scriptStart = () => {
  console.log('map is loading...');
  console.log('map center location: ',!!Number(getLat()?.value) && Number(getLong()?.value));
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
    map.on('move', () => {
      const center = map.getCenter();
      if (getLat() && getLong() && center) {
        getLong().value = center[0] || center.lng;
        getLat().value = center[1] || center.lat;
      }
    })
}


document.addEventListener('DOMContentLoaded', () => {
  console.log('document loaded');
  console.log(getLat() && getMap() , getLat(), getMap());
  if (getLat() && getMap()) {
    scriptStart();
  } else {getLat()?.addEventListener('load', () => {
    if(getMap()) scriptStart();
    else {
      getMap()?.addEventListener('load', scriptStart)
    }
  })}
});

