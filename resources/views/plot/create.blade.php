<x-layout>
    <x-slot:title>Plot Management - Create</x-slot:title>

    <script>
        const mapboxToken = @json(env('MAPBOX_TOKEN'));
    </script>

    <form method="POST" action="/plot" class="max-w-6xl mx-auto">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/plot">Plot Management</x-breadcrumb-link>
                    <x-breadcrumb-current>Create</x-breadcrumb-current>
                </x-breadcrumb-container>
                <x-information-box>
                    Use your mouse scroll to zoom in/out and left-click to drag.
                    Click the <i class="fa-solid fa-vector-square"></i> icon to enable drawing mode.
                    Use left-click to draw your polygon.
                    Double-click when you draw the final vortex to complete the polygon.
                    To cancel or delete your polygon, click the <i class="fa-solid fa-trash-can"></i> icon.
                </x-information-box>
                <div class="relative w-full h-1/6 my-4 bg-gray-50 rounded-lg shadow p-2">
                    <div id="map" class="w-full absolute"></div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <x-input-info type="name" placeholder="North Field or Plot A"/>
                    <x-input-info type="hectare"/>
                    <x-input-info-soil-type/>
                    <x-input-info-public/>
                    <x-input-info type="longitude" :readonly="true"/>
                    <x-input-info type="latitude" :readonly="true"/>
                    <x-input-info type="coordinates" :readonly="true"/>
                    <x-input-info-long type="description" placeholder="(Optional)"/>
                </div>

                @if ($errors->any())
                    <div class="col-span-6">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Whoops! Something went wrong.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif


            </div>
        </div>
        <div class="mt-6 flex justify-end items-center">
                <x-form-cancel-button href="/plot">Cancel</x-form-cancel-button>
                <x-form-submit-button>Confirm</x-form-submit-button>
        </div>
    </form>

    @vite([
        'resources/js/plot-create.js',
        'resources/css/plot-create.css'
    ])
</x-layout>
