<x-layout>
    <x-slot:title>Interactive Map - Record # {{ $plot->id }}</x-slot:title>

    <script>
        const mapData = {
            plotId: @json($plot->id),
            coordinates: @json($plot->coordinates),
            longitude: @json($plot->longitude),
            latitude: @json($plot->latitude)
        };
        const mapboxToken = @json($apiKey);
    </script>

    <div class="max-w-full mx-auto">
        <div class="space-y-12">
            <div class="pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/map">Interactive Map</x-breadcrumb-link>
                    <x-breadcrumb-current>Plot Record # {{ $plot->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>

                <!-- Main Details and Map -->
                <div class="w-full flex gap-5">
                    <div class="w-1/2 flex flex-col gap-5">
                        <div class="flex gap-5">
                            <x-map.show-info label="Plot Name" icon="fa-vector-square" :value="$plot->name"/>
                            <x-map.show-info label="Owner" icon="fa-user" :value="$username"/>
                        </div>
                        <div class="flex gap-5">
                            <x-map.show-star-info :average="$ratingAvg"/>
                            <x-map.show-star-input :action="$plot->id" :plot="$plot"/>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-5 shadow mb-5 text-gray-500">
                            <label class="mb-2 block text-sm font-medium">Soil Health</label>
                            <div class="flex gap-5">
                                <x-map.show-info-soil icon="fa-n" label="Nitrogen" value="{{ $latest_soil['nitrogen'] }}"/>
                                <x-map.show-info-soil icon="fa-gauge-simple " label="pH" value="{{ $latest_soil['ph'] }}"/>
                            </div>
                            <div class="flex gap-5">
                                <x-map.show-info-soil icon="fa-p" label="Phosphorus" value="{{ $latest_soil['phosphorus'] }}"/>
                                <x-map.show-info-soil icon="fa-droplet" label="Humidity" value="{{ $latest_soil['humidity'] }}"/>
                            </div>
                            <div class="flex gap-5">
                                <x-map.show-info-soil icon="fa-k" label="Potassium" value="{{ $latest_soil['potassium'] }}"/>
                                <x-map.show-info-soil icon="fa-temperature-half" label="Temperature" value="{{ $latest_soil['temperature'] }}"/>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="relative w-1/2 h-[465px] mb-4 bg-gray-50 rounded-lg shadow-sm p-2 ">
                        <div id="map" class="w-full absolute"></div>
                    </div>
                </div>

                <div class="w-full flex gap-5">
                    <!-- Most frequently planted -->
                    <div class="w-3/12 mb-4 bg-gray-50 rounded-lg shadow p-6">
                        <label class="mb-2 block text-sm font-medium text-gray-500">Most Frequently Planted Crops</label>
                        <div class="pt-6" id="most-planted-chart"></div>
                    </div>

                    <!-- Best performing crops -->
                    <div class="w-9/12 mb-4 bg-gray-50 rounded-lg shadow p-4 md:p-6">
                        <div class="flex justify-between pb-4 mb-4">
                            <div class="flex items-center">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-500">Highest Performing Crops</label>
                                </div>
                            </div>
                        </div>
                        <div id="column-chart"></div>
                    </div>

                </div>

                <!-- Table -->
                <div class="w-full relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                            <x-table-header>Crop</x-table-header>
                            <x-table-header>Yield (Tons)</x-table-header>
                            <x-table-header>Planting Date</x-table-header>
                            <x-table-header>Harvest Date</x-table-header>
                            <x-table-header>Performance</x-table-header>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($crop_yields as $yield)
                            <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                                <td class="px-3 py-4">{{ $yield['crop_name'] }}</td>
                                <td class="px-3 py-4">{{ $yield['actual_yield'] }}</td>
                                <td class="px-3 py-4">{{ $yield['planting_date']->format('Y-m-d') }}</td>
                                <td class="px-3 py-4">{{ $yield['harvest_date']->format('Y-m-d') }}</td>
                                <td class="px-3 py-4">{{ $yield['performance'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $crop_yields->links() }} <!-- Pagination links -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const mostPlantedData = {
            crop: @json($mostPlantedCrops->pluck('crop')),
            crop_count: @json($mostPlantedCrops->pluck('crop_count')),
        };

        const bestCropYieldsData = {
            id: @json(collect($bestCropYields)->pluck('id')),
            crop: @json(collect($bestCropYields)->pluck('crop_name')),
            performance: @json(collect($bestCropYields)->pluck('performance')),
            harvestDates: @json(collect($bestCropYields)->pluck('harvest_date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('m-d-Y')))
        };

        console.log(bestCropYieldsData);
    </script>

    @vite([
        'resources/js/apexcharts-global.js',
        'resources/css/map-show.css',
        'resources/js/map-show.js',
        'resources/js/map-rating-show.js',
    ])

</x-layout>
