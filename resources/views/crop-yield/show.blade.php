<x-layout>
    <x-slot:title>Crop Yield Tracking - Record # {{ $yield->id }}</x-slot:title>

    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/crop-yield">Crop Yield Tracking</x-breadcrumb-link>
                    <x-breadcrumb-current>Record # {{ $yield->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-show-info label="Plot Name" icon="fa-vector-square">{{ $yield->plot->name }}</x-show-info>
                    <x-show-info label="Crop" icon="fa-seedling">{{ $yield->crop }}</x-show-info>
                    <x-show-info label="Yield (Tons)" icon="fa-plate-wheat">{{ $yield->actual_yield ?? 'N/A' }}</x-show-info>
                    <x-show-date :model="$yield" icon="fa-calendar-day" field="planting_date" label="Planting Date"/>
                    <x-show-date :model="$yield" icon="fa-calendar-xmark" field="harvest_date" label="Harvest Date"/>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end items-center">
            <form method="POST" action="/crop-yield/{{ $yield->id }}" class="mr-2">
                @csrf
                @method('DELETE')
                <x-form-delete-button>Delete</x-form-delete-button>
            </form>
            <x-common-anchor href="/crop-yield/{{ $yield->id }}/edit">Edit</x-common-anchor>
        </div>
    </div>

</x-layout>
