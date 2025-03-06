<x-layout>
    <x-slot:title>Crop Recommendation</x-slot:title>
    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlotId" action="/crop-recommendation"/>

    <div class="mt-4 bg-gray-50 rounded-lg shadow p-3 mb-4">
        @if(!empty($finalRecommendations) && is_array($finalRecommendations))
            <!-- Tab Buttons -->
            <div class="flex border-b pb-2" id="tab-buttons">
                @foreach ($finalRecommendations as $index => $recommendation)
                    <button data-tab="{{ $index }}"
                            class="py-2 px-4 cursor-pointer hover:text-blue-500 text-gray-700">
                        {{ $recommendation['crop_name'] }} ({{ $recommendation['other_name'] }})
                    </button>
                @endforeach
            </div>

            <!-- Tab Content -->
            <div id="tab-content">
                @foreach ($finalRecommendations as $index => $recommendation)
                    <div class="tab-content px-4 {{ $loop->first ? '' : 'hidden' }}" data-tab-content="{{ $index }}">
                        <!-- Tags Section -->
                        <div class="flex items-center mt-6">
                            <span class="text-gray-600 text-sm mr-2">Ideal conditions met for:</span>
                            <div class="flex gap-2 flex-wrap tags-container">
                                @foreach ($recommendation['reasons'] as $reason)
                                    <span class="px-3 py-1 text-sm font-semibold text-white bg-grad-blue rounded-full">
                                        {{ ucfirst($reason) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recommendation Details -->
                        <div class="flex pb-2">
                            <div class="w-1/3 relative py-2">
                                <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['ideal_soil'] }}" label="Is my soil type ideal?" icon="fa-mound" color="text-yellow-900"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['ideal_soonest_month'] }}" label="When should planting start?" icon="fa-wind" color="text-sky-500"/>
                                <x-crop-recommendation.timeline-item value="Checking..." label="Is the weather ideal?" icon="fa-cloud-sun" color="text-sky-500" class="weather-ideal"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['seeds_needed'] }}" label="How many seeds are needed?" icon="fa-cubes-stacked" color="text-lime-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['density'] }}" label="How many plants to grow?" icon="fa-seedling" color="text-green-500"/>
                            </div>
                            <div class="w-1/3 relative py-2">
                                <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['spacing_plant'] }}" label="What's the ideal plant spacing?" icon="fa-braille" color="text-stone-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['spacing_row'] }}" label="What's the ideal row spacing?" icon="fa-braille" color="text-stone-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['yield'] }}" label="What is the expected yield?" icon="fa-dolly" color="text-zinc-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['total_actual_yield'] . ' tons'}}" label="How much have other farms harvested recently?" icon="fa-dolly" color="text-green-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['total_expected'] }}" label="How much are other farms expecting to harvest soon?" icon="fa-dolly" color="text-amber-500"/>
                            </div>
                            <div class="w-1/3 relative py-2">
                                <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['npk'] }}" label="How should fertilization be done?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['npk_fertilizer'] }}" label="Which NPK fertilizer is ideal?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['ph'] }}" label="How should pH be adjusted?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['ph_fertilizer'] }}" label="Which pH fertilizer is ideal?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                                <x-crop-recommendation.timeline-item value="{{ $recommendation['maturity'] }}" label="How long until harvest?" icon="fa-carrot" color="text-amber-500"/>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="w-2/3 h-[200px] mt-10 bg-gray-50 rounded-lg shadow p-6 flex items-center justify-center">
                <h2 class="text-lg font-semibold text-gray-800">No crop recommendations available</h2>
            </div>
        @endif
        <div class="flex space-x-4 bg-gray-50 border-t border-gray-200 px-4 pt-7 pb-4 mt-4">
            <!-- Left: Polygon Plot -->
            <div class="w-5/12">
                <h2 class="text-base font-semibold text-gray-800">Plot Grid</h2>
                <canvas id="polygonCanvas" width="650" height="650" class="border rounded-lg mt-2"></canvas>
            </div>

            <!-- Middle: Controls -->
            <div class="w-2/12 py-7">
                <!-- Plot Grid Toggle -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm py-2">Plot Grid Size</label>
                    <div class="flex rounded-lg bg-gray-200 p-2">
                        <button id="plotGrid10m" class="w-1/2 text-sm font-semibold px-4 py-1 rounded bg-white shadow text-gray-800 hover:text-gray-800">10m<sup>2</sup></button>
                        <button id="plotGrid1m" class="w-1/2 text-sm font-semibold px-4 py-1 text-gray-600 hover:text-gray-800">1m<sup>2</sup></button>
                    </div>
                </div>

                <!-- Crop Grid Toggle -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm py-2">Crop Spacing Area</label>
                    <div class="flex rounded-lg bg-gray-200 p-2">
                        <button id="cropGrid10m" class="w-1/3 text-sm font-semibold px-4 py-1 rounded bg-white shadow text-gray-800 hover:text-gray-800">10m<sup>2</sup></button>
                        <button id="cropGrid1m" class="w-1/3 text-sm font-semibold px-4 py-1 text-gray-600 hover:text-gray-800">1m<sup>2</sup></button>
                        <button id="cropGrid0_1m" class="w-1/3 text-sm font-semibold px-4 py-1 text-gray-600 hover:text-gray-800">0.1m<sup>2</sup></button>
                    </div>
                </div>

                <!-- Plant Spacing Slider -->
                <div id="spacingPlantSlider">
                    <label class="block text-gray-700 text-sm py-2">Plant Spacing (<span id="plantSpacingValue">25 cm</span>)</label>
                    <input type="range" id="plantSpacingSlider" min="10" max="40" value="25" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer my-4">
                </div>

                <!-- Row Spacing Slider -->
                <div id="spacingRowSlider">
                    <label class="block text-gray-700 text-sm py-2">Row Spacing (<span id="rowSpacingValue">25 cm</span>)</label>
                    <input type="range" id="rowSpacingSlider" min="10" max="40" value="25" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer my-4">
                </div>
            </div>

            <!-- Right: 1m x 1m Grid -->
            <div class="w-5/12">
                <div class="text-base text-gray-800 font-semibold mb-2">
                    <span id="cropGridLabel">Crop Spacing</span>
                </div>
                <canvas id="cropLayoutCanvas" width="650" height="650" class="border rounded-lg mt-2"></canvas>
            </div>
        </div>
    </div>




</x-layout>

<script>
    const plotCoordinates = @json($plotCoordinates);
    const plotHectare = @json($plotHectare);
    const cropData = @json($finalRecommendations);
    const plotLatitude = {{ $latitude }};
    const plotLongitude = {{ $longitude }};
</script>

@vite([
        'resources/js/crop-recommendation-tabs.js',
        'resources/js/crop-recommendation-grid.js',
        'resources/js/crop-recommendation-weather.js'
    ])
