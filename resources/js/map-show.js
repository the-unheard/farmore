import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import 'mapbox-gl/dist/mapbox-gl.css';
import '@mapbox/mapbox-gl-draw/dist/mapbox-gl-draw.css';

mapboxgl.accessToken = mapboxToken;

// If cropYieldData is undefined, assign an empty object as a fallback
const mapDataObject = mapData || {};
const idData = mapDataObject.plotId || [];
const polygonCoordinates = JSON.parse(mapData.coordinates) || [];
const longitudeData = mapDataObject.longitude || [];
const latitudeData = mapDataObject.latitude || [];
const bounds = [[116, 5],[130, 22]]; // southwest and northeast coordinates

const feature = {
    type: 'Feature',
    geometry: {
        type: 'Point',
        coordinates: [longitudeData, latitudeData]
    }
};

const map = new mapboxgl.Map({
    container: 'map', // container ID
    center: [longitudeData, latitudeData], // starting position [lng, lat]
    zoom: 16, // starting zoom
    maxBounds: bounds
});

const el = document.createElement('div');
el.className = `marker fa-solid fa-location-dot text-xl text-center text-red-500`;

new mapboxgl.Marker(el)
    .setLngLat(feature.geometry.coordinates)
    .addTo(map);

// Add polygon for the plot
const polygonFeature = {
    'type': 'Feature',
    'geometry': {
        'type': 'Polygon',
        'coordinates': [polygonCoordinates]
    }
};

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
