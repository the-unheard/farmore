<x-layout>
    <x-slot:title>Soil Health Tracking - Soil # {{ $soil->id }}</x-slot:title>
    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/soil">Soil Health Tracking</x-breadcrumb-link>
                    <x-breadcrumb-current>Record # {{ $soil->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-show-info label="Plot Name" icon="fa-vector-square">{{ $soil->plot->name }}</x-show-info>
                    <x-show-info label="Nitrogen (mg/kg)" icon="fa-n">{{ $soil->nitrogen }}</x-show-info>
                    <x-show-info label="Phosphorus (mg/kg)" icon="fa-p">{{ $soil->phosphorus }}</x-show-info>
                    <x-show-info label="Potassium (mg/kg)" icon="fa-k">{{ $soil->potassium }}</x-show-info>
                    <x-show-info label="Temperature (°C)" icon="fa-temperature-half">{{ $soil->temperature }}</x-show-info>
                    <x-show-info label="Humidity (%)" icon="fa-droplet">{{ $soil->humidity }}</x-show-info>
                    <x-show-info label="pH" icon="fa-gauge-simple">{{ $soil->ph }}</x-show-info>
                    <x-show-date :model="$soil" field="record_date" label="Record Date" icon="fa-calendar"/>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-end mt-6">
{{--            <div class="flex items-center space-x-2">--}}
{{--                <x-common-anchor href="{{ route('crop-recommendation.index', [--}}
{{--                                            'nitrogen' => $soil->nitrogen,--}}
{{--                                            'phosphorus' => $soil->phosphorus,--}}
{{--                                            'potassium' => $soil->potassium,--}}
{{--                                            'temperature' => $soil->temperature,--}}
{{--                                            'humidity' => $soil->humidity,--}}
{{--                                            'ph' => $soil->ph,--}}
{{--                                        ]) }}">Get Crop Recommendation--}}
{{--                </x-common-anchor>--}}
{{--            </div>--}}
            <div class="flex items-center space-x-2">
                <form method="POST" action="/soil/{{ $soil->id }}">
                    @csrf
                    @method('DELETE')
                    <x-form-delete-button>Delete</x-form-delete-button>
                </form>
                <x-common-anchor href="/soil/{{ $soil->id }}/edit">Edit</x-common-anchor>
            </div>
        </div>
    </div>
</x-layout>
