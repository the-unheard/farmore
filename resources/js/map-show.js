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

const mostPlantedObject = mostPlantedData || {};
const cropMost = mostPlantedObject.crop || [];
const cropCountMost = mostPlantedObject.crop_count || [];

const bestCropYieldsDataObject = bestCropYieldsData || {};

// crop yield data
const idYieldChart = bestCropYieldsDataObject.id || [];
const cropYieldChart = bestCropYieldsDataObject.crop || [];
const performanceYieldChart = bestCropYieldsDataObject.performance || [];
const recordYieldChart = bestCropYieldsDataObject.harvestDates || [];



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

// map cropYieldChart and yieldYieldChart into the required format for ApexCharts
const chartData = cropYieldChart.map((crop, index) => ({
    x: crop,
    y: performanceYieldChart[index] || 0,
    date: recordYieldChart[index] || 'N/A',
    id: idYieldChart[index] || null,
}));

// most frequently planted
const optionsMostPlanted = {
    colors: [
        "#4776E6",
        "#8E54E9",
        "#E9724C",
        "#70877F",
        "#E05263"
    ],
    series: cropCountMost,
    chart: {
        height: 400,
        type: "donut",
    },
    labels: cropMost,
    legend: {
        position: "bottom",
        formatter: function (seriesName, opts) {
            // Custom legend formatter
            const percentage = parseFloat(opts.w.globals.seriesPercent[opts.seriesIndex]) || 0; // Convert to number safely
            const count = opts.w.globals.series[opts.seriesIndex]; // Get count (number of times planted)
            const sliceColor = opts.w.globals.colors[opts.seriesIndex]; // Get the color of the slice

            return `
            <span class="most-planted-crop text-left ml-1 min-w-[100px]">${seriesName}</span>
            <span class="most-planted-count text-center">${count}</span>
            <span class="most-planted-percentage text-right">${percentage.toFixed(1)}%</span>
            <div class="progress-bar-container relative w-20 h-3 bg-gray-200 rounded-sm overflow-hidden inline-block ml-14 top-0.5">
                <div class="progress-bar h-full rounded-sm" style="width: ${percentage}%; background-color: ${sliceColor};"></div>
            </div>
        `;
        },
        itemMargin: {
            horizontal: 10,
            vertical: 5
        },
    },
    tooltip: { fillSeriesColor: false, }
}

// yield chart options
const optionsYield = {
    plotOptions: {
        bar: {
            columnWidth: "50%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    series: [{ data: performanceYieldChart }],
    chart: {
        type: "bar",
        height: 400,
        events: {
            click: (event, chartContext, config) => {
                const clickedId = chartData[config.dataPointIndex].id;
                window.location.href = `/crop-yield/${clickedId}`;
            },
        },
    },
    xaxis: { categories: cropYieldChart, },
    grid: { strokeDashArray: 4, },
    dataLabels: { enabled: false },
    legend: { show: false },
    tooltip: {
        intersect: false,
        x: {
            formatter: (value, {dataPointIndex}) => {
                const date = chartData[dataPointIndex].date;
                return value + '<br/><b>' + date + '</b>';
            },
        },
        y: { title: { formatter: function(value) { return 'Performance';  } } } // formats the tooltip text
    },
    colors: ['#8E54E9'],
    fill: {
        type: 'gradient',
        gradient: {
            type: 'horizontal',
            gradientToColors: ['#4776E6'],
            stops: [0, 100]
        }
    },

};

// render function
const renderChart = (elementId, options) => {
    if (document.getElementById(elementId) && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById(elementId), options);
        chart.render();
    }
};

// render the charts
renderChart("most-planted-chart", optionsMostPlanted);
renderChart("column-chart", optionsYield);
