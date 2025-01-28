<x-layout>
    <x-slot:title>Weather</x-slot:title>

    <script>
        const plotData = {
            longitude: @json($longitude),
            latitude: @json($latitude)
        };
    </script>

    <div id="loading-screen" class="flex flex-col items-center justify-center w-full h-full">
        <div class="relative flex items-center justify-center h-32">
            <div class="sun w-32 h-32 rounded-full absolute z-0 bg-gray-400"></div>
            <i class="fa-solid fa-cloud text-white text-7xl cloud absolute z-10 -left-[65px] bottom-[5px]"></i>
        </div>
        <p class="text-lg font-semibold text-gray-700 mt-6">Fetching Weather Data</p>
    </div>

    <div id="weather-content" class="relative w-full h-auto hidden">
        <!-- Plot Dropdown -->
        <x-index-plot-dropdown :model="$plots" action="/weather"/>

        <div class="w-full flex flex-col mb-4">
            <div class="grid grid-cols-12 gap-4">

                <!-- Today's Weather -->
                <div class="col-span-2 bg-gray-50 text-gray-500 p-6 rounded-md shadow-md flex flex-col justify-between">
                    <div class="text-center bg-grad-blue rounded-full p-4 inline-block">
                        <img id="today-weather-icon" src="" alt="Weather Icon" class="mx-auto">
                    </div>
                    <div id="today-weather-temp" class="text-5xl text-center mt-4"></div>
                    <div id="today-weather-feel" class="text-md text-center mt-1"></div>
                    <hr class="my-4 border-gray-300">
                    <div class="flex items-center">
                        <div class="bg-gray-400 rounded-full p-2 w-12 h-12">
                            <img id="today-weather-icon-small" alt="Weather Description" class="w-8 h-8">
                        </div>
                        <span id="today-weather-desc" class="ml-2 capitalize"></span>
                    </div>
                    <div class="flex items-center mt-2">
                        <div class="bg-gray-400 rounded-full p-2 w-12 h-12 text-center">
                            <i class="fa-solid fa-cloud-showers-heavy text-lg text-white"></i>
                        </div>
                        <span id="today-weather-rain" class="ml-2"></span>
                    </div>
                </div>

                <!-- Today's Highlights -->
                <div class="col-span-10">
                    <div class="flex space-x-2 bg-gray-200 my-4 rounded-md">
                        <h4 class="text-xl text-gray-900 font-semibold">Today's Highlights</h4>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-gray-400">
                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Pressure</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div id="today-weather-pressure" class="text-4xl text-gray-900"></div>
                                <i class="fa-solid fa-gauge text-6xl"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Humidity</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div id="today-weather-humidity" class="text-4xl text-gray-900"></div>
                                <i class="fa-solid fa-droplet text-6xl text-grad-blue"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Visibility</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div id="today-weather-visibility" class="text-4xl text-gray-900"></div>
                                <i class="fa-solid fa-eye-low-vision text-6xl"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Cloudiness</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div id="today-weather-cloudiness" class="text-4xl text-gray-900"></div>
                                <i class="fa-solid fa-cloud text-6xl text-grad-blue"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Min/Max Temperature</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-temperature-low text-xl mr-2"></i>
                                        <span id="today-weather-min-temp" class="text-xl text-gray-900"></span>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-temperature-high text-xl mr-2"></i>
                                        <span id="today-weather-max-temp" class="text-xl text-gray-900"></span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-temperature-full text-6xl"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Wind</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-gauge-simple-high text-xl mr-2"></i>
                                        <span id="today-weather-wind-speed" class="text-xl text-gray-900"></span>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-compass text-xl mr-2"></i>
                                        <span id="today-weather-wind-direction" class="text-xl text-gray-900"></span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-wind text-6xl text-grad-blue"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Sunrise & Sunset</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-circle-up text-xl mr-2"></i>
                                        <span id="today-weather-sunrise" class="text-xl text-gray-900"></span>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-circle-down text-xl mr-2"></i>
                                        <span id="today-weather-sunset" class="text-xl text-gray-900"></span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-sun text-6xl text-yellow-200"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Atmospheric Pressure</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-water text-xl mr-2"></i>
                                        <span id="today-weather-sea" class="text-xl text-gray-900"></span>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <i class="fa-solid fa-mountain text-xl mr-2"></i>
                                        <span id="today-weather-ground" class="text-xl text-gray-900"></span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-down-up-across-line text-6xl"></i>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-md shadow-md">
                            <h3 class="font-semibold text-lg text-gray-500">Air Pollution</h3>
                            <div class="flex justify-between items-center mt-4">
                                <div id="today-weather-pollution" class="text-4xl text-gray-900"></div>
                                <i class="fa-solid fa-smog text-6xl text-green-300"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Forecast  -->
        <div class="w-full bg-gray-200 my-4 rounded-md">
            <h4 class="text-xl text-gray-900 font-semibold">Weather Forecast</h4>
            <div id="weather-forecast" class="flex gap-4 bg-gray-200 rounded-md my-4">
                <!-- Forecast cards go here -->
            </div>
        </div>
    </div>

    <x-footer/>


    @vite('resources/css/weather-index.css')
    @vite('resources/js/weather.js')
</x-layout>
