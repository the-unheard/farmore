import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/apexcharts-global.js',
                'resources/js/weather.js',
                'resources/js/soil-index.js',
                'resources/js/map-index-load.js',
            ],
            refresh: true,
        }),
    ],
    define: {
        'process.env.MAPBOX_TOKEN': JSON.stringify(process.env.MAPBOX_TOKEN),
    }
});
