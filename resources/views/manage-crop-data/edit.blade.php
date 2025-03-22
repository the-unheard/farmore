<x-layout>
    <x-slot:title>Crop Data Management - Edit</x-slot:title>
    <x-slot:heading></x-slot:heading>
    <form method="POST" action="/manage-crop-data/{{ $cropData->id }}" class="max-w-6xl mx-auto">
        @csrf
        @method('PATCH')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/manage-crop-data">Manage Crop Data</x-breadcrumb-link>
                    <x-breadcrumb-link href="/manage-crop-data/{{ $cropData->id }}">Crop Data # {{ $cropData->id }}</x-breadcrumb-link>
                    <x-breadcrumb-current>Edit</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <x-input-info type="crop_name" :model="$cropData"/>
                    <x-input-info type="other_name" :model="$cropData"/>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end items-center">
            <x-form-cancel-button href="/manage-crop-data">Cancel</x-form-cancel-button>
            <x-form-submit-button>Confirm</x-form-submit-button>
        </div>
    </form>
</x-layout>
