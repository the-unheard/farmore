<x-layout>
    <x-slot:title>Manage Crop Data - Crop Data # {{ $cropData->id }}</x-slot:title>
    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/manage-plots">Manage Crop Data</x-breadcrumb-link>
                    <x-breadcrumb-current>Crop Data # {{ $cropData->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-show-info label="Crop Data ID" icon="fa-id-badge">{{ $cropData->id }}</x-show-info>
                    <x-show-info label="Crop Name" icon="fa-user">{{ $cropData->crop_name }}</x-show-info>
                    <x-show-info label="Other Name" icon="fa-user">{{ $cropData->other_name }}</x-show-info>

                    <x-show-info label="Climate 1" icon="fa-envelope">{{ $cropData->climate_1 }}</x-show-info>
                    <x-show-info label="Climate 2" icon="fa-envelope">{{ $cropData->climate_2 }}</x-show-info>
                    <x-show-info label="Climate 3" icon="fa-envelope">{{ $cropData->climate_3 }}</x-show-info>
                    <x-show-info label="Climate 4" icon="fa-envelope">{{ $cropData->climate_4 }}</x-show-info>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-end mt-6">
            <div class="flex items-center space-x-2">
                <x-common-anchor href="/manage-crop-data/{{ $cropData->id }}/edit">Edit</x-common-anchor>
            </div>
        </div>
    </div>
</x-layout>
