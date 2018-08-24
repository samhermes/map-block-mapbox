mapboxgl.accessToken = mapboxBlock.apiKey;

var mapboxMap = document.getElementById('mapbox-map'),
    mapLng = mapboxMap.getAttribute('data-lng'),
    mapLat = mapboxMap.getAttribute('data-lat'),
    mapZoom = mapboxMap.getAttribute('data-zoom');

var mapCenter = [
    mapLng,
    mapLat
];

var map = new mapboxgl.Map({
    container: 'mapbox-map',
    style: 'mapbox://styles/mapbox/streets-v9',
    center: mapCenter,
    zoom: mapZoom
});

var marker = new mapboxgl.Marker()
  .setLngLat(mapCenter)
  .addTo(map);