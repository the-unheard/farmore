<x-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <!-- Plot Dropdown -->
    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlot" action="/dashboard"/>

    <h5 class="font-bold text-4xl text-gray-700 my-8">
        <i class="fas fa-chart-line"></i>
        Plot Statistics
    </h5>

    <!-- Row -->
    <div class="flex gap-5 mb-5">

        <!-- NPK pH Report -->
        <div class="w-1/2 grid grid-cols-2 gap-5 min-w-[300px] max-w-[850px]">
            <x-dashboard.single-highlight-analysis :value="$nutrientAnalysis['nitrogen']" :analysis="$nutrientAnalysis['nitrogenMessage']" label="Nitrogen" icon="fa-n"/>
            <x-dashboard.single-highlight-analysis :value="$nutrientAnalysis['phosphorus']" :analysis="$nutrientAnalysis['phosphorusMessage']" label="Phosphorus" icon="fa-p"/>
            <x-dashboard.single-highlight-analysis :value="$nutrientAnalysis['potassium']" :analysis="$nutrientAnalysis['potassiumMessage']" label="Potassium" icon="fa-k"/>
            <x-dashboard.single-highlight-analysis :value="$nutrientAnalysis['ph']" :analysis="$nutrientAnalysis['phMessage']" label="pH" icon="fa-gauge-simple"/>
        </div>

        <div class="w-1/2 flex min-w-[300px] max-w-[850px] gap-5">
            <!-- NPK pH Prediction -->
            <div class="w-1/2 bg-gray-50 rounded-lg shadow p-4">
                <x-dashboard.header header="Nutrients Prediction (30 Days)"/>
                <ul>
                    <x-dashboard.prediction-list-item label="Nitrogen" :value="$nutrientPredictions['nitrogen'] ?? 'Not Enough Data'"/>
                    <x-dashboard.prediction-list-item label="Phosphorus" :value="$nutrientPredictions['phosphorus'] ?? 'Not Enough Data'"/>
                    <x-dashboard.prediction-list-item label="Potassium" :value="$nutrientPredictions['potassium'] ?? 'Not Enough Data'"/>
                    <x-dashboard.prediction-list-item label="pH" :value="$nutrientPredictions['ph'] ?? 'Not Enough Data'"/>
                </ul>
            </div>
            <!-- Top Yields -->
            <div class="w-1/2 bg-gray-50 rounded-lg shadow p-4">
                <x-dashboard.header header="Best Crop Yields"/>
                <ul>
                    @foreach($bestCropYields as $cropYield)
                        <li class="border-b border-dashed border-gray-200 last:border-b-0 last:mb-0">
                            <a href="{{ url('crop-yield/' . $cropYield['id']) }}" class="flex justify-between px-1 py-1 rounded-sm hover:bg-gray-200">
                                <span class="text-gray-600 font-medium text-sm">{{ $cropYield['crop_name'] }}</span>
                                <span class="text-gray-500 text-sm">{{ $cropYield['performance'] }} %</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

    <!-- Row -->
    <div class="flex gap-5">

        <!-- Most frequently planted -->
        <div class="w-4/12 bg-gray-50 rounded-lg shadow p-6">
            <x-dashboard.header header="Most Frequently Planted Crops"/>
            <div class="pt-6" id="most-planted-chart"></div>
        </div>

        <div class="w-8/12 gap-5">
            <!-- Soil Health -->
            <div class="w-full min-w-[300px] mb-5 bg-gray-50 rounded-lg shadow p-6">
                <x-dashboard.header header="Most Recent Soil Health"/>
                <div id="dashboard-soil-chart"></div>
            </div>

            <!-- Crop Yield -->
            <div class="w-full min-w-[300px] bg-gray-50 rounded-lg shadow p-6">
                <x-dashboard.header header="Most Recent Crop Yield"/>
                <div id="dashboard-yield-chart"></div>
            </div>
        </div>

    </div>

    <h5 class="font-bold text-4xl text-gray-700 my-8">
        <i class="fas fa-chart-line"></i>
        General Statistics
    </h5>

    <!-- Row -->
    <div class="flex gap-5 mb-5">

        <!-- Total Users and Plots -->
        <div class="w-3/12 grid grid-cols-1 gap-5 min-w-[300px] max-w-[850px]">
            <x-dashboard.single-highlight :value="$totalUsers" label="Total Users" icon="fa-users"/>
            <x-dashboard.single-highlight :value="$totalPlots" label="Total Plots" icon="fa-vector-square"/>
            <x-dashboard.single-highlight :value="$totalPublicPlots" label="Public Plots" icon="fa-location-dot"/>
        </div>

        <!-- Top Contributors -->
        <div class="w-5/12 min-w-[300px] max-w-[850px] bg-gray-50 rounded-lg shadow p-4">
            <x-dashboard.header header="Top Interactive Map Contributors"/>
            <div id="top-contributors-chart"></div>
        </div>

        <!-- Top Rated Pins -->
        <div class="w-4/12 min-w-[300px] max-w-[850px] bg-gray-50 rounded-lg shadow p-4">
            <x-dashboard.header header="Top Rated Interactive Map Pins"/>
            <ul>
                @foreach($topRatedPins as $topRatedPin)
                    <li class="border-b border-dashed border-gray-200 last:border-b-0 last:mb-0">
                        <a href="{{ url('map/' . $topRatedPin->id) }}" class="flex justify-between px-1 py-1 rounded-sm hover:bg-gray-200 block">
                            <span class="text-gray-600 font-medium text-sm">{{ $topRatedPin->name }}</span>
                            <span class="text-gray-500 text-sm">
                            <x-show-star-rating :avg="$topRatedPin->rating_avg_rating"/>
                        </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Row -->
    <div class="flex flex-wrap justify-center gap-5 mb-2">

        <!-- Most recent pins -->
        <div class="flex-1 bg-gray-50 rounded-lg shadow p-4 md:p-6">
            <x-dashboard.header header="Most Recent Pins"/>
            <div class="relative overflow-x-auto sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                    <tr>
                        <x-table-header>Farm Name</x-table-header>
                        <x-table-header>Location</x-table-header>
                        <x-table-header>Hectare</x-table-header>
                        <x-table-header>Owner</x-table-header>
                        <x-table-header>Date Added</x-table-header>
                        <th scope="col" class="px-3 py-3"><span class="sr-only">View</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentPins as $recentPin)
                        <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                            <td class="px-3 py-4">{{ $recentPin->name }}</td>
                            <td class="px-3 py-4">{{ $recentPin->city }}</td>
                            <td class="px-3 py-4">{{ $recentPin->hectare }}</td>
                            <td class="px-3 py-4">{{ $recentPin->user->username }}</td>
                            <td class="px-3 py-4">{{ $recentPin->created_at->format('m-d-Y') }}</td>
                            <td class="px-3 py-4 text-right">
                                <x-table-view-button href="/map/{{ $recentPin->id }}">View</x-table-view-button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-5">
                    {{ $recentPins->appends(['plot_id' => request('plot_id')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-footer/>

    <script>
        const topContributors = {
            username: @json($topContributors->pluck('username')),
            pinCount: @json($topContributors->pluck('plot_count')),
        };

        const soilData = {
            nitrogen: @json($latestSoils->pluck('nitrogen')),
            phosphorus: @json($latestSoils->pluck('phosphorus')),
            potassium: @json($latestSoils->pluck('potassium')),
            temperature: @json($latestSoils->pluck('temperature')),
            humidity: @json($latestSoils->pluck('humidity')),
            ph: @json($latestSoils->pluck('ph')),
            recordDates: @json($latestSoils->pluck('record_date')->map(fn($date) => $date->format('m-d-Y')))
        };

        const cropYieldData = {
            id: @json($latestCropYields->pluck('id')),
            crop: @json($latestCropYields->pluck('crop_name')),
            cropYield: @json($latestCropYields->pluck('performance')),
            recordDates: @json($latestCropYields->pluck('harvest_date')->map(fn($date) => $date->format('m-d-Y')))
        };

        const mostPlantedData = {
            crop: @json($mostPlantedCrops->pluck('crop')),
            crop_count: @json($mostPlantedCrops->pluck('crop_count')),
        };
    </script>

    @vite([
        'resources/js/apexcharts-global.js',
        'resources/js/dashboard-index.js',
        'resources/css/dashboard.css']
    )

</x-layout>
