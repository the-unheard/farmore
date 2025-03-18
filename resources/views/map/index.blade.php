<x-layout>
    <x-slot:title>Interactive Map</x-slot:title>

    <script>
        const mapboxToken = @json($apiKey);
    </script>

    <div class="relative w-full h-full p-3 flex bg-gray-50 rounded-lg shadow">
        <!-- Sidebar with Filters -->
        <div class="w-2/12 min-w-[200px] h-full bg-gray-100 px-4 py-2">

            <!-- Rating Filter -->
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-gray-900">Rating Filter</h4>
                </div>
                <x-custom-star-dropdown/>
            </div>

            <!-- Crop Filter (Scrollable) -->
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-gray-900">Crop Filter</h4>
                </div>
                <!-- Scrollable Crop List -->
                <div id="map-index-crop-filter" class="grid grid-cols-1 mt-1 overflow-y-auto">
                    <label class="flex items-center hover:bg-gray-200 py-[3px] px-2 m-0 cursor-pointer">
                        <input type="checkbox" id="toggle-all" class="rounded-sm checkbox-grad-blue" checked>
                        <span class="ml-2 text-gray-700 text-sm">Select/Deselect All</span>
                    </label>
                    @foreach ($crops as $crop)
                        <label class="flex items-center hover:bg-gray-200 py-[3px] px-2 m-0 cursor-pointer">
                            <input type="checkbox" class="crop-filter rounded-sm checkbox-grad-blue" value="{{ $crop->crop_name }}" checked>
                            <span class="ml-2 text-gray-700 text-sm">{{ $crop->crop_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div id="map" class="w-10/12 h-full"></div>
    </div>


    <x-footer/>

    @vite([
        'resources/js/map-index-load.js',
        'resources/js/map-index-controls.js',
        'resources/css/map-index.css'
    ])

</x-layout>
