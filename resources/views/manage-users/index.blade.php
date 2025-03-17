<x-layout>
    <x-slot:title>Manage Users</x-slot:title>

    <!-- Table -->
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
                <x-table-header>Id</x-table-header>
                <x-table-header>Username</x-table-header>
                <x-table-header>Email</x-table-header>
                <x-table-header>Role</x-table-header>
                <x-table-header>Created At</x-table-header>
                <th scope="col" class="px-3 py-3"><span class="sr-only">Edit</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="border-b text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100">
                    <td class="px-3 py-4">{{ $user->id }}</td>
                    <td class="px-3 py-4">{{ $user->username }}</td>
                    <td class="px-3 py-4">{{ $user->email }}</td>
                    <td class="px-3 py-4">{{ $user->roles->isNotEmpty() ? $user->roles->pluck('name')->join(', ') : 'member' }}</td>
                    <td class="px-3 py-4">{{ $user->created_at->format('m-d-Y') }}</td>
                    <td class="px-3 py-4 text-right">
                        <x-table-view-button href="/manage-users/{{ $user->id }}">View</x-table-view-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $users->links() }}
    </div>


</x-layout>
