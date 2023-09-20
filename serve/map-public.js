document.addEventListener('DOMContentLoaded', () => {
  console.log('document loaded');
  console.log(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id') && document.getElementById('wctd-tapsi-pack-maplibre-map-container-id') , document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id'), document.getElementById('wctd-tapsi-pack-maplibre-map-container-id'));
  if (document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id') && document.getElementById('wctd-tapsi-pack-maplibre-map-container-id')) {
      let wctdmappubliccenterLocation = [51.337762, 35.699927]; // Azadi Square
      let wctdtapsipackmappublic = null;
      console.log('script start');
      console.log(!!Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.value) && Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id')?.value));
      if(Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.value) && Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id')?.value)) wctdmappubliccenterLocation = [Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id').value), Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id').value)];
      console.log(wctdmappubliccenterLocation);
      wctdtapsipackmappublic = new maplibregl.Map({
        container: 'wctd-tapsi-pack-maplibre-map-container-id', // container id
        style: 'http://localhost/tapsipack/wp-content/plugins/serve/mapsi-style.json',
        center: wctdmappubliccenterLocation, // starting position
        zoom: 15, // starting zoom
      });
      maplibregl.setRTLTextPlugin(
          'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js',
          null,
          true, // Lazy load the plugin
      );
      wctdtapsipackmappublic.addControl(new maplibregl.NavigationControl());
      wctdtapsipackmappublic.on('move', () => {
        const center = wctdtapsipackmappublic.getCenter();
        if (document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id') && document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id') && center) {
          document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id').value = center[0] || center.lng;
          document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id').value = center[1] || center.lat;
        }
      })
  } else {document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.addEventListener('load', () => {
    if(document.getElementById('wctd-tapsi-pack-maplibre-map-container-id')) {
        let wctdmappubliccenterLocation = [51.337762, 35.699927]; // Azadi Square
        let wctdtapsipackmappublic = null;
        console.log('script start');
        console.log(!!Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.value) && Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id')?.value));
        if(Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.value) && Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id')?.value)) wctdmappubliccenterLocation = [Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id').value), Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id').value)];
        console.log(wctdmappubliccenterLocation);
        wctdtapsipackmappublic = new maplibregl.Map({
          container: 'wctd-tapsi-pack-maplibre-map-container-id', // container id
          style: 'http://localhost/tapsipack/wp-content/plugins/serve/mapsi-style.json',
          center: wctdmappubliccenterLocation, // starting position
          zoom: 15, // starting zoom
        });
        maplibregl.setRTLTextPlugin(
            'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js',
            null,
            true, // Lazy load the plugin
        );
        wctdtapsipackmappublic.addControl(new maplibregl.NavigationControl());
        wctdtapsipackmappublic.on('move', () => {
          const center = wctdtapsipackmappublic.getCenter();
          if (document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id') && document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id') && center) {
            document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id').value = center[0] || center.lng;
            document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id').value = center[1] || center.lat;
          }
        })
    }
    else {
      document.getElementById('wctd-tapsi-pack-maplibre-map-container-id')?.addEventListener('load', () => {
        let wctdmappubliccenterLocation = [51.337762, 35.699927]; // Azadi Square
        let wctdtapsipackmappublic = null;
        console.log('script start');
        console.log(!!Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.value) && Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id')?.value));
        if(Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id')?.value) && Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id')?.value)) wctdmappubliccenterLocation = [Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id').value), Number(document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id').value)];
        console.log(wctdmappubliccenterLocation);
        wctdtapsipackmappublic = new maplibregl.Map({
          container: 'wctd-tapsi-pack-maplibre-map-container-id', // container id
          style: 'http://localhost/tapsipack/wp-content/plugins/serve/mapsi-style.json',
          center: wctdmappubliccenterLocation, // starting position
          zoom: 15, // starting zoom
        });
        maplibregl.setRTLTextPlugin(
            'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js',
            null,
            true, // Lazy load the plugin
        );
        wctdtapsipackmappublic.addControl(new maplibregl.NavigationControl());
        wctdtapsipackmappublic.on('move', () => {
          const center = wctdtapsipackmappublic.getCenter();
          if (document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id') && document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id') && center) {
            document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lng-field-id').value = center[0] || center.lng;
            document.getElementById('wctd-tapsi-pack-maplibre-map-location-form-lat-field-id').value = center[1] || center.lat;
          }
        })
      })
    }
  })}
});

