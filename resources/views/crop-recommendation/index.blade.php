<x-layout>
    <x-slot:title>Soil Health Tracking - Create</x-slot:title>
    <x-index-plot-dropdown :model="$plots" :selected="$selectedPlotId" action="/crop-recommendation"/>

    <form method="POST" action="/crop-recommendation" class="max-w-6xl mx-auto">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/crop-recommendation">Crop Recommendation</x-breadcrumb-link>
                    <x-breadcrumb-current>Create</x-breadcrumb-current>
                </x-breadcrumb-container>
                <x-information-box>
                    Not sure where to find your soil health data?
                    You can get this information from a soil testing kit or by consulting with local agricultural experts.
                </x-information-box>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <x-input-info type="nitrogen" placeholder="140"/>
                    <x-input-info type="phosphorus" placeholder="145"/>
                    <x-input-info type="potassium" placeholder="205"/>
                    <x-input-info type="temperature" placeholder="44"/>
                    <x-input-info type="humidity" placeholder="100"/>
                    <x-input-info type="ph" placeholder="10"/>
                    <x-input-info type="rainfall" placeholder="300"/>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end items-center">
            <x-form-cancel-button href="/soil">Cancel</x-form-cancel-button>
            <x-form-submit-button>Confirm</x-form-submit-button>
        </div>
    </form>
</x-layout>
