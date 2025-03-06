<x-layout>
    <x-slot:title>Interactive Map - Record # {{ $plot->id }}</x-slot:title>

    <script>
        const plotData = {
            longitude: @json($plot->longitude),
            latitude: @json($plot->latitude),
            coordinates: @json($plot->coordinates)
        };
        const mapboxToken = @json($apiKey);
    </script>

    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/plot">Plot Management</x-breadcrumb-link>
                    <x-breadcrumb-current>Plot # {{ $plot->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>

                <div class="relative w-full h-1/6 mb-4 bg-gray-50 rounded-lg shadow p-2 ">
                    <div id="map" class="w-full absolute"></div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-show-info label="Name" icon="fa-signature">{{  $plot->name }}</x-show-info>
                    <x-show-info label="Hectare" icon="fa-vector-square">{{  $plot->hectare }}</x-show-info>
                    <x-show-info label="Soil Type" icon="fa-mound">{{  ucwords($plot->soil_type) }}</x-show-info>
                    <x-show-info label="Public" icon="fa-earth-asia">{{  ($plot->public ?? 1) == 1 ? 'Yes' : 'No' }}</x-show-info>
                    <x-show-info label="Token" icon="fa-code" colspan="col-span-4">{{  $plot->plot_token }}</x-show-info>
                    <x-show-info label="Description" icon="fa-file-lines" colspan="col-span-6">{{  $plot->description }}</x-show-info>
                </div>
            </div>
        </div>

        @authId($plot->user_id)
        <div class="mt-6 flex justify-end items-center">
            <form method="POST" action="/plot/{{ $plot->id }}" class="mr-2">
                @csrf
                @method('DELETE')
                <x-form-delete-button>Delete</x-form-delete-button>
            </form>
            <x-common-anchor href="/plot/{{ $plot->id }}/edit">Edit</x-common-anchor>
        </div>
        @endauthId
    </div>

    @vite([
        'resources/css/plot-create.css',
        'resources/js/plot-show.js',
    ])

</x-layout>
