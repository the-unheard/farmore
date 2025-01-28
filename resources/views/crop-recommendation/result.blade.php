<x-layout>
    <x-slot:title>Crop Recommendation - Result</x-slot:title>
    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/crop-recommendation">Crop Recommendation</x-breadcrumb-link>
                    <x-breadcrumb-current>Result</x-breadcrumb-current>
                </x-breadcrumb-container>
                <!-- Result Box -->
                <div class="mt-10 p-8 bg-sky-500 text-white rounded-lg shadow-lg flex flex-col items-center">
                    <i class="fa-solid fa-seedling text-5xl mb-4"></i>
                    <h2 class="text-xl font-semibold mb-2">Recommended Crop</h2>
                    <p class="text-sm ">The best crop to plant based on your soil health is:</p>
                    <p class="text-4xl font-semibold mt-4 result-crop" data-value="{{ $best }}">{{ $best }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
