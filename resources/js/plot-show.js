import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

mapboxgl.accessToken = mapboxToken;

const longitudeData = plotData.longitude || 0;
const latitudeData = plotData.latitude || 0;
const polygonCoordinates = JSON.parse(plotData.coordinates) || [];
const bounds = [[116, 5], [130, 22]];

// Create the Mapbox map
const map = new mapboxgl.Map({
    container: 'map',
    center: [longitudeData, latitudeData],
    zoom: 10,
    maxBounds: bounds
});

// Add the marker for the center point
const el = document.createElement('div');
el.className = `marker fa-solid fa-location-dot text-xl text-center text-red-500`;

new mapboxgl.Marker(el)
    .setLngLat([longitudeData, latitudeData])
    .addTo(map);

// Define the GeoJSON feature for the polygon
const polygonFeature = {
    'type': 'Feature',
    'geometry': {
        'type': 'Polygon',
        'coordinates': [polygonCoordinates]
    }
};

// When the map loads, add the polygon layer and outline
map.on('load', () => {
    // Add GeoJSON source for the polygon
    map.addSource('plotPolygon', {
        'type': 'geojson',
        'data': polygonFeature  // Use the GeoJSON feature directly
    });

    // Add fill layer
    map.addLayer({
        'id': 'plotPolygonFill',
        'type': 'fill',
        'source': 'plotPolygon',
        'layout': {},
        'paint': {
            'fill-color': '#088',  // Fill color
            'fill-opacity': 0.5    // Fill opacity
        }
    });

    // Add outline layer
    map.addLayer({
        'id': 'plotPolygonOutline',
        'type': 'line',
        'source': 'plotPolygon',
        'layout': {},
        'paint': {
            'line-color': '#000',  // Outline color
            'line-width': 2        // Outline width
        }
    });
});
