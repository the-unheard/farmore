import mapboxgl from 'mapbox-gl';
import MapboxDraw from '@mapbox/mapbox-gl-draw'; // Import Mapbox Draw
import 'mapbox-gl/dist/mapbox-gl.css';
import '@mapbox/mapbox-gl-draw/dist/mapbox-gl-draw.css';
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';
import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css';

mapboxgl.accessToken = mapboxToken;

const bounds = [
    [116, 5], // Southwest coordinates
    [130, 22] // Northeast coordinates
];

// Initialize the map
const map = new mapboxgl.Map({
    container: 'map', // container ID
    center: [120.96, 14.45], // starting position [lng, lat]
    zoom: 5, // starting zoom
    maxBounds: bounds
});


// adds search
const geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl, // Ensure this is the mapbox instance
    marker: false // You can add this if you don't want markers on search
});

map.addControl(geocoder);

// Initialize Mapbox Draw to allow polygon drawing
const draw = new MapboxDraw({
    displayControlsDefault: false,
    controls: {
        polygon: true, // Enable polygon drawing
        trash: true // Enable delete control
    }
});

// Add the draw control to the map
map.addControl(draw);

// Event listeners
map.on('draw.create', updatePolygon);
map.on('draw.update', updatePolygon);
map.on('draw.delete', clearInputs);

function updatePolygon(e) {
    const data = draw.getAll();
    if (data.features.length > 0) {
        const polygon = data.features[0];
        const coordinates = polygon.geometry.coordinates[0]; // Array of coordinates

        const coordinatesBox = document.getElementById('coordinates');
        const longitudeInput = document.getElementById('longitude');
        const latitudeInput = document.getElementById('latitude');

        // Store coordinates in the box as a JSON string
        coordinatesBox.value = JSON.stringify(coordinates);

        // Calculate the center of the polygon
        const center = getPolygonCenter(coordinates);

        // Set the center coordinates into longitude and latitude inputs
        longitudeInput.value = center[0];
        latitudeInput.value = center[1];
    } else {
        // No polygon is drawn
        const coordinatesBox = document.getElementById('coordinates');
        coordinatesBox.value = '';
    }
}

function getPolygonCenter(coordinates) {
    let lngSum = 0;
    let latSum = 0;
    const numPoints = coordinates.length;

    coordinates.forEach(coord => {
        lngSum += coord[0];
        latSum += coord[1];
    });

    return [lngSum / numPoints, latSum / numPoints];
}

function clearInputs() {
    const coordinatesBox = document.getElementById('coordinates');
    const longitudeInput = document.getElementById('longitude');
    const latitudeInput = document.getElementById('latitude');

    coordinatesBox.value = '';
    longitudeInput.value = '';
    latitudeInput.value = '';
}

// adds control buttons (zoom, compass)
map.addControl(new mapboxgl.NavigationControl());
