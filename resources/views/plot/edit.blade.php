<x-layout>
    <x-slot:title>Interactive Map - Edit</x-slot:title>

    <script>
        const plotData = {
            longitude: @json($plot->longitude),
            latitude: @json($plot->latitude),
            coordinates: @json($plot->coordinates)
        };
        const mapboxToken = @json($apiKey);
    </script>

    <form method="POST" action="/plot/{{ $plot->id }}" class="max-w-6xl mx-auto">
        @csrf
        @method('PATCH')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/plot">Interactive Map</x-breadcrumb-link>
                    <x-breadcrumb-link href="/plot/{{ $plot->id }}">Record # {{ $plot->id }}</x-breadcrumb-link>
                    <x-breadcrumb-current>Edit</x-breadcrumb-current>
                </x-breadcrumb-container>
                <x-information-box>
                    Use your mouse scroll to zoom in/out and left-click to drag.
                    To delete your existing polygon, click it and click the <i class="fa-solid fa-trash-can"></i> icon.
                    Click the <i class="fa-solid fa-vector-square"></i> icon to enable drawing mode.
                    Use left-click to draw your polygon.
                    Double-click when you draw the final vortex to complete the polygon.
                </x-information-box>

                <div class="relative w-full h-1/6 my-4 bg-gray-50 rounded-lg shadow p-2 ">
                    <div id="map" class="w-full absolute"></div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <x-input-info type="name" :model="$plot"/>
                    <x-input-info type="hectare" :model="$plot"/>
                    <x-input-info-soil-type :plot="$plot"/>
                    <x-input-info-public :plot="$plot"/>
                    <x-input-info type="coordinates" :model="$plot" :readonly="true"/>
                    <x-input-info type="longitude" :model="$plot" :readonly="true"/>
                    <x-input-info type="latitude" :model="$plot" :readonly="true"/>
                    <x-input-info-long type="description" :model="$plot"/>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end items-center">
                <x-form-cancel-button href="/plot">Cancel</x-form-cancel-button>
                <x-form-submit-button>Confirm</x-form-submit-button>
        </div>
    </form>

    @vite([
        'resources/js/plot-edit.js',
        'resources/css/plot-create.css'
    ])
</x-layout>
