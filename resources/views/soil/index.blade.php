<x-layout>
    <x-slot:title>Soil Health Tracking</x-slot:title>

    <script>
        const soilData = {
            nitrogen: @json($latestSoils->pluck('nitrogen')),
            phosphorus: @json($latestSoils->pluck('phosphorus')),
            potassium: @json($latestSoils->pluck('potassium')),
            temperature: @json($latestSoils->pluck('temperature')),
            humidity: @json($latestSoils->pluck('humidity')),
            ph: @json($latestSoils->pluck('ph')),
            recordDates: @json($latestSoils->pluck('record_date')->map(fn($date) => $date->format('m-d-Y')))
        };
    </script>

    <!-- Plot Dropdown -->
    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlot" action="/soil"/>

    <!-- Graph -->
    <div class="w-full mb-4 bg-gray-50 rounded-lg shadow p-4 md:p-6">
        <div class="flex justify-between mb-5">
            <div>
                <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">Soil Health Tracking</h5>
                <p class="text-base font-normal text-gray-500">Most recent soil health record</p>
            </div>
        </div>
        <div id="legend-chart"></div>
    </div>


    <!-- Analytics and Trend -->
    <div class="flex my-5 gap-5">

        <!-- Analytics -->
        <div class="w-2/3 grid grid-cols-3 gap-5">
            <x-soil-min-max label="Nitrogen Levels"
                            :minValue="optional($nMinRecord)->nitrogen ?? 'N/A'"
                            :minDate="optional(optional($nMinRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :avgValue="$nAvgRecord == 0 ? 'N/A' : $nAvgRecord"
                            :maxValue="optional($nMaxRecord)->nitrogen ?? 'N/A'"
                            :maxDate="optional(optional($nMaxRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :prediction="$predictedNitrogen"/>

            <x-soil-min-max label="Phosphorus Levels"
                            :minValue="optional($pMinRecord)->phosphorus ?? 'N/A'"
                            :minDate="optional(optional($pMinRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :avgValue="$pAvgRecord == 0 ? 'N/A' : $pAvgRecord"
                            :maxValue="optional($pMaxRecord)->phosphorus ?? 'N/A'"
                            :maxDate="optional(optional($pMaxRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :prediction="$predictedPhosphorus"/>

            <x-soil-min-max label="Potassium Levels"
                            :minValue="optional($kMinRecord)->potassium ?? 'N/A'"
                            :minDate="optional(optional($kMinRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :avgValue="$kAvgRecord == 0 ? 'N/A' : $kAvgRecord"
                            :maxValue="optional($kMaxRecord)->potassium ?? 'N/A'"
                            :maxDate="optional(optional($kMaxRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :prediction="$predictedPotassium"/>

            <x-soil-min-max label="Temperature Levels"
                            :minValue="optional($tMinRecord)->temperature ?? 'N/A'"
                            :minDate="optional(optional($tMinRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :avgValue="$tAvgRecord == 0 ? 'N/A' : $tAvgRecord"
                            :maxValue="optional($tMaxRecord)->temperature ?? 'N/A'"
                            :maxDate="optional(optional($tMaxRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :prediction="$predictedTemperature"/>

            <x-soil-min-max label="Humidity Levels"
                            :minValue="optional($hMinRecord)->humidity ?? 'N/A'"
                            :minDate="optional(optional($hMinRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :avgValue="$hAvgRecord == 0 ? 'N/A' : $hAvgRecord"
                            :maxValue="optional($hMaxRecord)->humidity ?? 'N/A'"
                            :maxDate="optional(optional($hMaxRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :prediction="min($predictedPh, 100)"/>

            <x-soil-min-max label="pH Levels"
                            :minValue="optional($phMinRecord)->ph ?? 'N/A'"
                            :minDate="optional(optional($phMinRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :avgValue="$phAvgRecord == 0 ? 'N/A' : $phAvgRecord"
                            :maxValue="optional($phMaxRecord)->ph ?? 'N/A'"
                            :maxDate="optional(optional($phMaxRecord)->record_date)->format('M-d-Y') ?? 'N/A'"
                            :prediction="min($predictedPh, 10)"/>
        </div>


        <!-- Trend -->
        <div class="w-1/3 bg-white shadow rounded-lg p-6">
            <div class="relative flex justify-end items-end">
                <div class="absolute top-0 inline-flex rounded-lg shadow-sm border-gray-200 border">
                    <button id="prevNutrient" class="bg-white text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none rounded-l-lg px-4 py-2 border-r">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="nextNutrient" class="bg-white text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none rounded-r-lg px-4 py-2">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <x-soil-trend-analysis nutrient="nitrogen" :rateOfChange="$nitrogenRateOfChange" unit="mg/kg"/>
            <x-soil-trend-analysis nutrient="phosphorus" :rateOfChange="$phosphorusRateOfChange" unit="mg/kg"/>
            <x-soil-trend-analysis nutrient="potassium" :rateOfChange="$potassiumRateOfChange" unit="mg/kg"/>
            <x-soil-trend-analysis nutrient="temperature" :rateOfChange="$temperatureRateOfChange" unit="°C"/>
            <x-soil-trend-analysis nutrient="humidity" :rateOfChange="$humidityRateOfChange" unit="%"/>
            <x-soil-trend-analysis nutrient="ph" :rateOfChange="$phRateOfChange"/>
        </div>

    </div>

    <!-- Table -->
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
                <x-table-header>Nitrogen</x-table-header>
                <x-table-header>Phosphorus</x-table-header>
                <x-table-header>Potassium</x-table-header>
                <x-table-header>Temperature</x-table-header>
                <x-table-header>Humidity</x-table-header>
                <x-table-header>ph Level</x-table-header>
                <x-table-header>Record Date</x-table-header>
                <th scope="col" class="px-3 py-3"><span class="sr-only">Edit</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach($soils as $soil)
                <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                    <td class="px-3 py-4">{{ $soil->nitrogen }}</td>
                    <td class="px-3 py-4">{{ $soil->phosphorus }}</td>
                    <td class="px-3 py-4">{{ $soil->potassium }}</td>
                    <td class="px-3 py-4">{{ $soil->temperature }}</td>
                    <td class="px-3 py-4">{{ $soil->humidity }}</td>
                    <td class="px-3 py-4">{{ $soil->ph }}</td>
                    <td class="px-3 py-4">{{ $soil->record_date->format('m-d-Y') }}</td>
                    <td class="px-3 py-4 text-right">
                        <x-table-view-button href="/soil/{{ $soil->id }}">View</x-table-view-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $soils->links() }}
    </div>
    <div class="mt-5">
        <x-common-anchor href="soil/create?plot_id={{ $selectedPlot }}">Add New</x-common-anchor>
    </div>

    @vite('resources/css/soil-index.css')
    @vite('resources/js/apexcharts-global.js')
    @vite('resources/js/soil-index.js')

</x-layout>
