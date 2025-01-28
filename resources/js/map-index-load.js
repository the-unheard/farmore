import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';
import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css';
import { populateMap } from './map-index-populate.js';

mapboxgl.accessToken = mapboxToken;
const bounds = [ [116, 5], [130, 22] ]; // SW/NE

// initialize the map
window.map = new mapboxgl.Map({
    container: 'map',
    center: [120.911305, 14.225577],
    zoom: 11,
    maxBounds: bounds
});

map.on('load', function () {
    // Map loaded, now populate it with markers and polygons
    populateMap();
});

// adds search
const geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl, // Ensure this is the mapbox instance
    marker: false // You can add this if you don't want markers on search
});

map.addControl(geocoder);

// adds control buttons
map.addControl(new mapboxgl.NavigationControl());

