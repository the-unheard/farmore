<x-layout>
    <x-slot:title>Manage Plots</x-slot:title>

    <h5 class="font-bold text-4xl text-gray-700 my-8">
        <i class="fas fa-vector-square"></i>
        Manage Plots
    </h5>
    <!-- Table -->
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
                <x-table-header>Id</x-table-header>
                <x-table-header>Plot Name</x-table-header>
                <x-table-header>Owner</x-table-header>
                <x-table-header>City</x-table-header>
                <x-table-header>Hectare</x-table-header>
                <x-table-header>Public</x-table-header>
                <x-table-header>Average Rating</x-table-header>
                <x-table-header>Rating Count</x-table-header>
                <th scope="col" class="px-3 py-3"><span class="sr-only">Edit</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach($plots as $plot)
                <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                    <td class="px-3 py-4">{{ $plot->id }}</td>
                    <td class="px-3 py-4">{{ $plot->name }}</td>
                    <td class="px-3 py-4">{{ $plot->username }}</td>
                    <td class="px-3 py-4">{{ $plot->city }}</td>
                    <td class="px-3 py-4">{{ $plot->hectare }}</td>
                    <td class="px-3 py-4">{{ $plot->is_public }}</td>
                    <td class="px-3 py-4">{{ $plot->average_rating }}</td>
                    <td class="px-3 py-4">{{ $plot->rating_count }}</td>
                    <td class="px-3 py-4 text-right">
                        <x-table-view-button href="/manage-plots/{{ $plot->id }}">View</x-table-view-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $plots->links() }}
    </div>


</x-layout>
