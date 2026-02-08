// Initialize Mapbox
mapboxgl.accessToken = 'YOUR_MAPBOX_ACCESS_TOKEN'; // Replace with your Mapbox token
const map = new mapboxgl.Map({
  container: 'map',
  style: 'mapbox://styles/mapbox/streets-v11', // Map style
  center: [77.5946, 12.9716], // Bangalore coordinates [lng, lat]
  zoom: 12,
});