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

                <!-- ROW -->
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
                        <div class="bg-gray-100 text-gray-500 p-4 rounded-lg shadow-sm">
                            <label class="block text-sm font-medium">Soil Health</label>
                            <div class="flex gap-5">
                                <!-- N -->
                                <div class="w-1/2 flex items-center justify-between my-4 p-6 border-r">
                                    <div>
                                        <i class="p-2 bg-gray-600 w-8 text-center rounded fas fa-n text-white"></i>
                                        <span class="ml-1">Nitrogen</span>
                                    </div>
                                    <span>50</span>
                                </div>
                                <!-- P -->
                                <div class="w-1/2 flex items-center justify-between my-4">
                                    <div>
                                        <i class="p-2 bg-gray-600 w-8 text-center rounded fas fa-n text-white"></i>
                                        <span class="ml-1">Nitrogen</span>
                                    </div>
                                    <span>50</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="relative w-1/2 h-[600px] mb-4 bg-gray-50 rounded-lg shadow p-2 ">
                        <div id="map" class="w-full absolute"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @vite([
        'resources/css/map-show.css',
        'resources/js/map-show.js',
        'resources/js/map-rating-show.js',
    ])

</x-layout>
