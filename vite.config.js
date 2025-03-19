import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/crop-yield-index.css',
                'resources/css/dashboard.css',
                'resources/css/index.css',
                'resources/css/map-index.css',
                'resources/css/map-show.css',
                'resources/css/plot-create.css',
                'resources/css/soil-index.css',
                'resources/css/weather-index.css',
                'resources/js/app.js',
                'resources/js/apexcharts-global.js',
                'resources/js/crop-combobox.js',
                'resources/js/crop-populate-estimates.js',
                'resources/js/crop-recommendation-grid.js',
                'resources/js/crop-recommendation-tabs.js',
                'resources/js/crop-recommendation-weather.js',
                'resources/js/crop-yield-index.js',
                'resources/js/custom-star-dropdown.js',
                'resources/js/dashboard-index.js',
                'resources/js/map-create.js',
                'resources/js/map-index-controls.js',
                'resources/js/map-index-load.js',
                'resources/js/map-index-populate.js',
                'resources/js/map-rating-show.js',
                'resources/js/map-show.js',
                'resources/js/plot-create.js',
                'resources/js/plot-edit.js',
                'resources/js/plot-show.js',
                'resources/js/soil-index.js',
                'resources/js/toast-notification.js',
                'resources/js/weather.js',
            ],
            refresh: true,
        }),
    ],
    define: {
        'process.env.MAPBOX_TOKEN': JSON.stringify(process.env.MAPBOX_TOKEN),
    }
});
