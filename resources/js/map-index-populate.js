import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';
import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css';

export function populateMap() {

    const selectedCrops = Array.from(document.querySelectorAll('.crop-filter:checked'))
        .map(checkbox => checkbox.value);
    const selectedRating = parseFloat(document.getElementById('rating-filter-selected').getAttribute('data-value'));

    // Send AJAX request with selected crops and rating filter using fetch
    fetch(`/map?crops=${encodeURIComponent(selectedCrops.join(','))}&rating=${encodeURIComponent(selectedRating)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Tells Laravel it's an AJAX request
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.json(); // Parse the response directly as JSON
        })
        .then(data => {
            updateMapMarkers(data.plots);
        })
        .catch(error => {
            console.error('Error fetching filtered plots:', error);
        });

    // Update map markers based on filtered data from AJAX response
    function updateMapMarkers(plots) {
        // First, remove all existing markers and polygons
        removeAllMarkersAndPolygons();

        // Then add the new markers and polygons based on the response
        plots.forEach(plot => {
            addMarkerAndPolygon(plot);
        });
    }

    // Function to remove all markers and polygons from the map
    function removeAllMarkersAndPolygons() {
        // Remove all markers
        document.querySelectorAll('.marker').forEach(marker => marker.remove());

        // Remove all polygon layers
        map.getStyle().layers.forEach(layer => {
            if (layer.id.includes('polygon')) {
                map.removeLayer(layer.id); // Remove all polygon layers
            }
        });

        // Remove all polygon sources
        Object.keys(map.getStyle().sources).forEach(sourceId => {
            if (sourceId.includes('polygon')) {
                map.removeSource(sourceId); // Remove all polygon sources
            }
        });
    }


    // Function to add markers and polygons
    function addMarkerAndPolygon(plot) {
        const el = document.createElement('div');
        el.className = `marker fa-solid fa-location-dot text-xl text-center text-red-500`;
        el.setAttribute('data-rating', plot.rating_avg || 0);

        // Add marker
        new mapboxgl.Marker(el)
            .setLngLat([plot.longitude, plot.latitude])
            .setPopup(
                new mapboxgl.Popup({ offset: 25 })
                    .setHTML(`
                        <div class="text-left">
                            <p class="my-5 font-semibold text-lg bg-gray-600 text-white uppercase tracking-wider rounded-t-md">${plot.name}</p>
                            <div class="p-5">
                                <div class="flex flex-col gap-2 text-gray-800">
                                    <div class="flex justify-between">
                                        <b class="font-semibold">Created By</b> <span class="truncate max-w-[150px]">${plot.user.username}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <b class="font-semibold">Ratings</b>
                                        <span>${generateMarkerStars(plot.rating_avg)}</span>
                                    </div>
                                    <div class="flex justify-center">
                                        <a href="/map/${plot.id}" class="bg-grad-blue hover:bg-grad-blue text-white font-bold mt-2 py-1 w-full text-center rounded-md hover:bg-sky-700">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `)
            )
            .addTo(map);

        // Add polygon for the plot
        map.addSource(`polygon-${plot.id}`, {
            type: 'geojson',
            data: {
                type: 'Feature',
                geometry: {
                    type: 'Polygon',
                    coordinates: [JSON.parse(plot.coordinates)] // Parse coordinates for the polygon
                }
            }
        });

        map.addLayer({
            id: `polygon-fill-${plot.id}`,
            type: 'fill',
            source: `polygon-${plot.id}`,
            layout: {},
            paint: {
                'fill-color': '#888888',
                'fill-opacity': 0.5
            }
        });

        map.addLayer({
            id: `polygon-outline-${plot.id}`,
            type: 'line',
            source: `polygon-${plot.id}`,
            layout: {},
            paint: {
                'line-color': '#000000',
                'line-width': 2
            }
        });
    }

    // generates stars for the markers
    function generateMarkerStars(rating) {
        let starHTML = '';
        for (let i = 1; i <= 5; i++) {
            if (rating >= i)
                starHTML += '<i class="fa-solid fa-star text-yellow-400"></i>';
            else
                starHTML += '<i class="fa-solid fa-star text-gray-400"></i>';
        }
        return starHTML;
    }

}
