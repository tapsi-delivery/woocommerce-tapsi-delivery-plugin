const MAP_CONTAINER_ID = 'wctd-tapsi-pack-maplibre-map-container-id';
const MAP_STYLE = 'http://localhost/tapsipack/wp-content/plugins/serve/mapsi-style.json';
let centerLocation = [51.337762, 35.699927]; // Azadi Square
let map = null;

const scriptStart = () => {
  console.log('script start');
  // console.log(!!Number(getLat()?.value) && Number(getLong()?.value));
  // if(Number(getLat()?.value) && Number(getLong()?.value)) centerLocation = [Number(getLong().value), Number(getLat().value)];
  console.log(centerLocation);
  console.log(document.getElementById(MAP_CONTAINER_ID))
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
      console.log(center);
    })
    console.log(map)
}


document.addEventListener('DOMContentLoaded', () => {
  console.log('document loaded');
  scriptStart();
});

