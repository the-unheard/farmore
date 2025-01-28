<x-layout>
    @vite('resources/js/crop-combobox.js')
    <x-slot:title>Crop Yield Tracking - Create</x-slot:title>
    <form method="POST" action="/crop-yield" class="max-w-6xl mx-auto mb-40">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/crop-yield">Crop Yield Tracking</x-breadcrumb-link>
                    <x-breadcrumb-current>Create</x-breadcrumb-current>
                </x-breadcrumb-container>

                <div class="my-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <x-input-plot-dropdown :plots="$plots" :selected="old('plot_id', request()->input('plot_id') ?? '')" />
                    <x-crop-combobox :crops="$crops" :selected="old('crop', request()->input('crop', ''))" type="crop"/>
                    <x-input-info type="actual_yield" label="Actual Yield" placeholder="100" comment="(Tons) (Optional)"/>
                    <x-form-date-picker input_id="planting_date" placeholder="" comment="(Optional)">Planting Date</x-form-date-picker>
                    <x-form-date-picker input_id="harvest_date" placeholder="" comment="(Optional)">Harvest Date</x-form-date-picker>
                </div>

                <div class="w-2/3 h-[450px] mt-10 bg-gray-50 rounded-lg shadow p-6">
                    <h2 id="yield-predict-title" class="text-lg font-semibold text-gray-800">Recommendations</h2>
                    <div class="flex">
                        <div class="w-1/2 relative py-4">
                            <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                            <x-crop-yield-timeline-item id="yield-predict-soil-type" label="Is my soil type ideal?" icon="fa-mound" color="text-yellow-900"/>
                            <x-crop-yield-timeline-item id="yield-predict-month" label="When should planting start?" icon="fa-wind" color="text-sky-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-seed" label="How many seeds are needed?" icon="fa-cubes-stacked" color="text-lime-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-density" label="How many plants to grow?" icon="fa-seedling" color="text-green-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-plant-spacing" label="What's the ideal plant spacing?" icon="fa-braille" color="text-stone-500"/>
                        </div>
                        <div class="w-1/2 relative py-4">
                            <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                            <x-crop-yield-timeline-item id="yield-predict-row-spacing" label="What's the ideal row spacing?" icon="fa-braille" color="text-stone-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-fertilizer" label="How should fertilization be done?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-ph" label="How should pH be adjusted?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-maturity" label="How long until harvest?" icon="fa-carrot" color="text-amber-500"/>
                            <x-crop-yield-timeline-item id="yield-predict-produce" label="What is the expected yield?" icon="fa-dolly" color="text-zinc-500"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end items-center">
                <x-form-cancel-button href="/crop-yield">Cancel</x-form-cancel-button>
                <x-form-submit-button>Confirm</x-form-submit-button>
        </div>
    </form>

</x-layout>
