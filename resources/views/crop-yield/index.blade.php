<x-layout>
    <x-slot:title>Crop Yield Tracking</x-slot:title>

    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlot" action="/crop-yield"/>

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
            id: @json($latestCropYields->pluck('id')),
            crop: @json($latestCropYields->pluck('crop')),
            cropYield: @json($latestCropYields->pluck('actual_yield')),
            harvestDates: @json($latestCropYields->pluck('harvest_date')->map(fn($date) => $date->format('m-d-Y')))
        };
    </script>

    @vite('resources/js/apexcharts-global.js')
    @vite('resources/js/crop-yield-index.js')
    @vite('resources/css/crop-yield-index.css')
</x-layout>
