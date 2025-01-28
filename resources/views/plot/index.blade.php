<x-layout>
    <x-slot:title>Plot Management - Index</x-slot:title>

    <div id="plot-content" class="relative w-full h-auto">
        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($plots as $plot)
                <div class="bg-gray-50 rounded-lg shadow-md p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-start mb-4">
                        <i class="text-4xl text-gray-400 fa-solid fa-vector-square"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 ml-3">{{ $plot->name }}</h3>
                    </div>
                    <p class="text-gray-600 mb-4 italic">{{ $plot->description ?? 'No plot description available.' }}</p>
                    <!-- Soil Health Container -->
                    <div class="relative mt-4 mb-4">
                        <span class="absolute -top-3 left-4 bg-gray-50 px-2 text-sm font-semibold text-gray-700">Soil Health</span>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="flex flex-col gap-4">
                                <a href="/soil/create?plot_id={{ $plot->id }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                                    <i class="fa-solid fa-plus mr-2"></i> Add a new record
                                </a>
                                <a href="/soil?plot_id={{ $plot->id }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                                    <i class="fa-solid fa-history mr-2"></i> View history
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Crop Yield Container -->
                    <div class="relative mt-4 mb-4">
                        <span class="absolute -top-3 left-4 bg-gray-50 px-2 text-sm font-semibold text-gray-700">Crop Yield</span>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="flex flex-col gap-4">
                                <a href="/crop-yield/create?plot_id={{ $plot->id }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                                    <i class="fa-solid fa-plus mr-2"></i> Add a new record
                                </a>
                                <a href="/crop-yield?plot_id={{ $plot->id }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                                    <i class="fa-solid fa-history mr-2"></i> View history
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-auto">
                        <a href="/plot/{{ $plot->id }}" class="bg-grad-blue text-white px-3 py-2 rounded-md">View</a>
                    </div>
                </div>
            @endforeach
            <a href="/plot/create" class="flex flex-col justify-center items-center bg-gray-100 hover:bg-white rounded-lg p-6 shadow-md">
                <i class="fa-solid fa-plus text-4xl text-gray-400"></i>
                <p class="text-gray-500 mt-2">Add Plot</p>
            </a>
        </div>
    </div>
</x-layout>
