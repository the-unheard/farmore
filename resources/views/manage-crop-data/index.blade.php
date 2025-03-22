<x-layout>
    <x-slot:title>Manage Crop Data</x-slot:title>

    <h5 class="font-bold text-4xl text-gray-700 my-8">
        <i class="fas fa-carrot"></i>
        Manage Crop Data
    </h5>
    <!-- Table -->
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
                <x-table-header>Id</x-table-header>
                <x-table-header>Crop Name</x-table-header>
                <x-table-header>Crop Name</x-table-header>
                <x-table-header>Climate 1</x-table-header>
                <x-table-header>Climate 2</x-table-header>
                <x-table-header>Climate 3</x-table-header>
                <x-table-header>Climate 4</x-table-header>
                <th scope="col" class="px-3 py-3"><span class="sr-only">Edit</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cropData as $crop)
                <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                    <td class="px-3 py-4">{{ $crop->id }}</td>
                    <td class="px-3 py-4">{{ $crop->crop_name }}</td>
                    <td class="px-3 py-4">{{ $crop->other_name }}</td>
                    <td class="px-3 py-4">{{ $crop->climate_1 }}</td>
                    <td class="px-3 py-4">{{ $crop->climate_2 }}</td>
                    <td class="px-3 py-4">{{ $crop->climate_3 }}</td>
                    <td class="px-3 py-4">{{ $crop->climate_4 }}</td>
                    <td class="px-3 py-4 text-right">
                        <x-table-view-button href="/manage-crop-data/{{ $crop->id }}">View</x-table-view-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $cropData->links() }}
    </div>


</x-layout>
