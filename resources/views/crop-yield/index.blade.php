<x-layout>
    <x-slot:title>Crop Yield Tracking</x-slot:title>

    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlot" action="/crop-yield"/>

    <!-- History columns -->
    <div class="w-full mb-4 bg-gray-50 rounded-lg shadow p-4 md:p-6">
        <div class="flex justify-between pb-4 mb-4">
            <div class="flex items-center">
                <div>
                    <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">Crop Yield Tracking</h5>
                    <p class="text-base font-normal text-gray-500">Most recent harvested crops record</p>
                </div>
            </div>
        </div>
        <div id="column-chart"></div>
    </div>

    <div class="w-full flex gap-5 mb-4">
        <!-- Most frequently planted -->
        <div class="w-3/12 bg-gray-50 rounded-lg shadow p-6">
            <x-dashboard.header header="Most Frequently Planted Crops"/>
            <div id="most-planted-chart"></div>
        </div>

        <!-- Best Crop Rotation -->
        <div class="w-4/12 bg-gray-50 rounded-lg shadow p-6">
            <x-dashboard.header header="Crop Pair Performance"/>
            <table class="w-full border-none text-sm">
                <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">Pair</th>
                    <th class="px-4 py-2 text-left">Performance</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bestRotationPairs as $pair)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-gray-600">{{ $pair['pair'] }}</td>
                        <td class="px-4 py-2">{{ round($pair['averagePerformance'], 2) }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Best Yields Table -->
        <div class="w-5/12 bg-gray-50 rounded-lg shadow p-6">
            <x-dashboard.header header="Individual Crop Performance"/>

            <table class="w-full border-none text-sm">
                <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">Crop</th>
                    <th class="px-4 py-2 text-left">Expected Yield (Tons)</th>
                    <th class="px-4 py-2 text-left">Actual Yield (Tons)</th>
                    <th class="px-4 py-2 text-left">Performance</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bestCropYields as $cropYield)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-gray-600">
                            <a href="{{ url('crop-yield/' . $cropYield['id']) }}" class="hover:underline">
                                {{ $cropYield['crop_name'] }}
                            </a>
                        </td>
                        <td class="px-4 py-2 text-gray-500">
                            @if($cropYield['expected_min'] == $cropYield['expected_max'])
                                {{ $cropYield['expected_max'] }}
                            @else
                                {{ $cropYield['expected_min'] }} - {{ $cropYield['expected_max'] }}
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $cropYield['actual_yield'] }}</td>
                        <td class="px-4 py-2">{{ $cropYield['performance'] }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- History table -->
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                <tr>
                    <x-table-header>Crop</x-table-header>
                    <x-table-header>Yield (Tons)</x-table-header>
                    <x-table-header>Planting Date</x-table-header>
                    <x-table-header>Harvest Date</x-table-header>
                    <th scope="col" class="px-3 py-3"><span class="sr-only">Edit</span></th>
                </tr>
            </thead>
            <tbody>
            @foreach($yields as $yield)
                <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                    <td class="px-3 py-4">{{ $yield->crop }}</td>
                    <td class="px-3 py-4">{{ $yield->actual_yield ?? 'N/A' }}</td> <!-- Handle null yield -->
                    <td class="px-3 py-4">{{ $yield->planting_date ? $yield->planting_date->format('m-d-Y') : 'N/A' }}</td> <!-- Handle null planting_date -->
                    <td class="px-3 py-4">{{ $yield->harvest_date ? $yield->harvest_date->format('m-d-Y') : 'N/A' }}</td> <!-- Handle null harvest_date -->
                    <td class="px-3 py-4 text-right">
                        <x-table-view-button href="/crop-yield/{{ $yield->id }}">View</x-table-view-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $yields->links() }}
    </div>

    <div class="mt-5">
        <x-common-anchor href="crop-yield/create?plot_id={{ $selectedPlot }}">Add New</x-common-anchor>
    </div>

    <script>
        const cropYieldData = {
            id: @json(collect($latestCropYields)->pluck('id')),
            crop: @json(collect($latestCropYields)->pluck('crop_name')),
            performance: @json(collect($latestCropYields)->pluck('performance')),
            harvestDates: @json(collect($latestCropYields)->pluck('harvest_date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('m-d-Y')))
        };

        console.log(cropYieldData);

        const mostPlantedData = {
            crop: @json($mostPlantedCrops->pluck('crop')),
            crop_count: @json($mostPlantedCrops->pluck('crop_count')),
        };
    </script>

    @vite('resources/js/apexcharts-global.js')
    @vite('resources/js/crop-yield-index.js')
    @vite('resources/css/crop-yield-index.css')
</x-layout>
