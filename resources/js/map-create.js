import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

mapboxgl.accessToken = mapboxToken;

// If cropYieldData is undefined, assign an empty object as a fallback
const mapDataObject = mapData || {};

// Access the data passed from Blade template
const usernameData = mapDataObject.usernames || [];
const cropData = mapDataObject.crop || [];
const longitudeData = mapDataObject.longitudes || [];
const latitudeData = mapDataObject.latitudes || [];
const recordDates = mapDataObject.recordDates || [];

const bounds = [
    [116, 5], // Southwest coordinates
    [130, 22] // Northeast coordinates
];

// Initialize the geojson object
const geojson = {
    type: 'FeatureCollection',
    features: []
};

// Loop through all data and create features
for (let i = 0; i < cropData.length; i++) {
    const feature = {
        type: 'Feature',
        geometry: {
            type: 'Point',
            coordinates: [longitudeData[i], latitudeData[i]]
        },
        properties: {
            title: cropData[i],
            description: `
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between">
                        <b class="font-semibold">Record Date</b> <span>${recordDates[i]}</span>
                    </div>
                    <div class="flex justify-between">
                        <b class="font-semibold">Uploaded By</b> <span>${usernameData[i]}</span>
                    </div>
                </div>
            `
        }
    };

    // Add the feature to the geojson.features array
    geojson.features.push(feature);
}

// Initialize the map
const map = new mapboxgl.Map({
    container: 'map', // container ID
    center: [120.96, 14.45], // starting position [lng, lat]
    zoom: 5, // starting zoom
    maxBounds: bounds
});

// Loop through the geojson features and add markers with popups
for (const feature of geojson.features) {
    const el = document.createElement('div');
    el.className = 'marker';

    // Create the marker with a popup and add to the map
    new mapboxgl.Marker(el)
        .setLngLat(feature.geometry.coordinates)
        .setPopup(
            new mapboxgl.Popup({ offset: 25 })
                .setHTML(`
                    <div class="text-left">
                        <p class="my-5 font-semibold text-lg bg-gray-600 text-white uppercase tracking-wider rounded-t-md">${feature.properties.title}</p>
                        <div class="p-5">${feature.properties.description}</div>
                    </div>
                `)
        )
        .addTo(map);
}

map.on('mousedown', (e) =>{
    const longitudeInput = document.getElementById('longitude');
    const latitudeInput = document.getElementById('latitude');

    // Set longitude and latitude values
    longitudeInput.value = e.lngLat.lng;
    latitudeInput.value = e.lngLat.lat;

    // Add the pulse class
    longitudeInput.classList.add('pulse');
    latitudeInput.classList.add('pulse');

    // Remove the pulse class after 1 second to allow for future pulses
    setTimeout(() => {
        longitudeInput.classList.remove('pulse');
        latitudeInput.classList.remove('pulse');
    }, 1000); // Matches the animation duration
});
