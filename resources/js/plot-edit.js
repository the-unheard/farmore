import mapboxgl from 'mapbox-gl';
import MapboxDraw from '@mapbox/mapbox-gl-draw'; // Import Mapbox Draw
import 'mapbox-gl/dist/mapbox-gl.css';
import '@mapbox/mapbox-gl-draw/dist/mapbox-gl-draw.css';

mapboxgl.accessToken = mapboxToken;

// Extract existing plot data (coordinates, longitudes, latitudes)
const longitudeData = plotData.longitude || 0;
const latitudeData = plotData.latitude || 0;
const polygonCoordinates = JSON.parse(plotData.coordinates) || [];
const bounds = [[116, 5],[130, 22]];

// Initialize the map
const map = new mapboxgl.Map({
    container: 'map',
    center: [longitudeData, latitudeData],
    zoom: 18,
    maxBounds: bounds
});

// Add the marker for the center point
const el = document.createElement('div');
el.className = `marker fa-solid fa-location-dot text-xl text-center text-red-500`;

let marker = new mapboxgl.Marker(el)
    .setLngLat([longitudeData, latitudeData])
    .addTo(map);

// Initialize Mapbox Draw to allow polygon drawing
const draw = new MapboxDraw({
    displayControlsDefault: false,
    controls: {
        polygon: true,
        trash: true
    }
});

// Add the draw control to the map
map.addControl(draw);

// Load existing polygon if available
if (polygonCoordinates.length > 0) {
    const polygon = {
        'type': 'Feature',
        'geometry': {
            'type': 'Polygon',
            'coordinates': [polygonCoordinates] // Load the existing polygon coordinates
        }
    };

    // Add the polygon to MapboxDraw
    draw.add(polygon);

    // Fit map to the bounds of the polygon
    const bounds = polygon.geometry.coordinates[0].reduce(function (bounds, coord) {
        return bounds.extend(coord);
    }, new mapboxgl.LngLatBounds(polygon.geometry.coordinates[0][0], polygon.geometry.coordinates[0][0]));
    map.fitBounds(bounds, { padding: 20 });
}

// Event listeners
map.on('draw.create', updatePolygon);
map.on('draw.update', updatePolygon);
map.on('draw.delete', clearInputs);

// Update the form inputs with the drawn polygon's coordinates
function updatePolygon(e) {
    const data = draw.getAll();
    if (data.features.length > 0) {
        const polygon = data.features[0];
        const coordinates = polygon.geometry.coordinates[0]; // Get polygon coordinates

        const coordinatesBox = document.getElementById('coordinates');
        const longitudeInput = document.getElementById('longitude');
        const latitudeInput = document.getElementById('latitude');

        // Store coordinates as JSON in the hidden field
        coordinatesBox.value = JSON.stringify(coordinates);

        // Calculate and set the center of the polygon
        const center = getPolygonCenter(coordinates);
        longitudeInput.value = center[0];
        latitudeInput.value = center[1];
    } else {
        const coordinatesBox = document.getElementById('coordinates');
        coordinatesBox.value = '';
    }
}

// Calculate the center of the polygon
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

    // Clear the input fields
    coordinatesBox.value = '';
    longitudeInput.value = '';
    latitudeInput.value = '';
    marker.remove(); // Remove the marker from the map
    marker = null; // Clear the marker reference
}

// Add control buttons (zoom, compass)
map.addControl(new mapboxgl.NavigationControl());
