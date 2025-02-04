<x-layout>
    <x-slot:title>Soil Health Tracking - Create</x-slot:title>
    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlotId" action="/crop-recommendation"/>

    <div>
        @if(!empty($finalRecommendations) && is_array($finalRecommendations))
            @foreach ($finalRecommendations as $recommendation)
                <div class="w-2/3 h-[450px] mt-10 bg-gray-50 rounded-lg shadow p-6">
                    <h2 id="yield-predict-title" class="text-lg font-semibold text-gray-800">
                        {{ $recommendation['crop_name'] . ' (' . $recommendation['other_name'] . ')' }}
                    </h2>
                    <div class="flex">
                        <div class="w-1/2 relative py-4">
                            <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['ideal_soil'] }}" label="Is my soil type ideal?" icon="fa-mound" color="text-yellow-900"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['ideal_soonest_month'] }}" label="When should planting start?" icon="fa-wind" color="text-sky-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['seeds_needed'] }}" label="How many seeds are needed?" icon="fa-cubes-stacked" color="text-lime-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['density'] }}" label="How many plants to grow?" icon="fa-seedling" color="text-green-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['spacing_plant'] }}" label="What's the ideal plant spacing?" icon="fa-braille" color="text-stone-500"/>
                        </div>
                        <div class="w-1/2 relative py-4">
                            <div class="absolute w-0.5 bg-gray-300 h-72 left-4 top-10"></div>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['spacing_row'] }}" label="What's the ideal row spacing?" icon="fa-braille" color="text-stone-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['npk'] }}" label="How should fertilization be done?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['ph'] }}" label="How should pH be adjusted?" icon="fa-spray-can-sparkles" color="text-teal-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['maturity'] }}" label="How long until harvest?" icon="fa-carrot" color="text-amber-500"/>
                            <x-crop-recommendation.timeline-item value="{{ $recommendation['yield'] }}" label="What is the expected yield?" icon="fa-dolly" color="text-zinc-500"/>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-2/3 h-[200px] mt-10 bg-gray-50 rounded-lg shadow p-6 flex items-center justify-center">
                <h2 class="text-lg font-semibold text-gray-800">No crop recommendations available</h2>
            </div>
        @endif
    </div>

{{--    <form method="POST" action="/crop-recommendation" class="max-w-6xl mx-auto">--}}
{{--        @csrf--}}
{{--        <div class="space-y-12">--}}
{{--            <div class="border-b border-gray-900/10 pb-12">--}}
{{--                <x-breadcrumb-container>--}}
{{--                    <x-breadcrumb-link href="/crop-recommendation">Crop Recommendation</x-breadcrumb-link>--}}
{{--                    <x-breadcrumb-current>Create</x-breadcrumb-current>--}}
{{--                </x-breadcrumb-container>--}}
{{--                <x-information-box>--}}
{{--                    Not sure where to find your soil health data?--}}
{{--                    You can get this information from a soil testing kit or by consulting with local agricultural experts.--}}
{{--                </x-information-box>--}}
{{--                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">--}}
{{--                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">--}}
{{--                    <x-input-info type="nitrogen" placeholder="140"/>--}}
{{--                    <x-input-info type="phosphorus" placeholder="145"/>--}}
{{--                    <x-input-info type="potassium" placeholder="205"/>--}}
{{--                    <x-input-info type="temperature" placeholder="44"/>--}}
{{--                    <x-input-info type="humidity" placeholder="100"/>--}}
{{--                    <x-input-info type="ph" placeholder="10"/>--}}
{{--                    <x-input-info type="rainfall" placeholder="300"/>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="mt-6 flex justify-end items-center">--}}
{{--            <x-form-cancel-button href="/soil">Cancel</x-form-cancel-button>--}}
{{--            <x-form-submit-button>Confirm</x-form-submit-button>--}}
{{--        </div>--}}
{{--    </form>--}}
</x-layout>
